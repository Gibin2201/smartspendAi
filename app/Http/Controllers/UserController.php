<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Models\AddIncome;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\AddExpense;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Budget;
use App\Models\Dept;
use App\Models\Savings;
use App\Models\SavingsTransaction;

class UserController extends Controller
{
public function userhome()
    {
        $user = Auth::user();
        $balance = $user->income;

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $monthlyIncome = AddIncome::where('userid', $user->userid)
            ->whereBetween('income_date', [$startOfMonth, $endOfMonth])
            ->sum('incomeamount');

        $monthlyExpense = AddExpense::where('userid', $user->userid)
            ->whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->sum('expenseamount');

        // --- Expense Category Breakdown Data for Chart ---
        $expenseCategories = AddExpense::where('userid', $user->userid)
            ->whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->with('category')
            ->get()
            ->groupBy(function($item) {
                return $item->category->expensecategory ?? 'Uncategorized';
            })
            ->map(function ($group) {
                return $group->sum('expenseamount');
            });

        $categories = $expenseCategories->keys();
        $totals = $expenseCategories->values();

        // --- Previous Month Comparison Logic ---
        $startOfPrevMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfPrevMonth = Carbon::now()->subMonth()->endOfMonth();

        $prevMonthExpense = AddExpense::where('userid', $user->userid)
            ->whereBetween('expense_date', [$startOfPrevMonth, $endOfPrevMonth])
            ->sum('expenseamount');

        $percentageChange = 0;
        $isIncrease = false;

        if ($prevMonthExpense > 0) {
            $percentageChange = (($monthlyExpense - $prevMonthExpense) / $prevMonthExpense) * 100;
            $isIncrease = $percentageChange >= 0;
        } elseif ($monthlyExpense > 0) {
            $percentageChange = 100;
            $isIncrease = true;
        }

        $formattedPercentage = abs(round($percentageChange, 1));

        // Fetch current month and year for budget lookup
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->year;

        // Fetch the monthly budget set by the user for this current month
        $userBudget = Budget::where('userid', $user->userid)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        // Extract the budget amount (default to 0 if not set)
        $budgetLimit = $userBudget ? $userBudget->budget_amount : 0;

        // Calculate progress percentage safely (prevent division by zero)
        $budgetPercentage = ($budgetLimit > 0) ? min(round(($monthlyExpense / $budgetLimit) * 100), 100) : 0;

        // Determine progress bar color and warning text dynamically
        if ($budgetPercentage >= 90) {
            $progressBarColor = 'bg-danger';
            $budgetWarning = '90%+ used - Nearing budget limit!';
        } elseif ($budgetPercentage >= 75) {
            $progressBarColor = 'bg-warning';
            $budgetWarning = 'Caution: Getting close to limit.';
        } else {
            $progressBarColor = 'bg-primary';
            $budgetWarning = 'On track with your spending.';
        }

       // 1. Fetch recent incomes with created_at tie-breaker
$incomes = AddIncome::where('userid', $user->userid)
    ->with('category')
    ->orderBy('income_date', 'desc')
    ->orderBy('created_at', 'desc') // Safe tie-breaker using timestamps
    ->take(5)
    ->get()
    ->map(function ($item) {
        return [
            'date'        => $item->income_date,
            'created_at'  => $item->created_at,
            'description' => $item->Description ?? 'Income Deposit',
            'category'    => $item->category->incomecategory ?? 'Income',
            'amount'      => $item->incomeamount,
            'type'        => 'income'
        ];
    });

// 2. Fetch recent expenses with created_at tie-breaker
$expenses = AddExpense::where('userid', $user->userid)
    ->with('category')
    ->orderBy('expense_date', 'desc')
    ->orderBy('created_at', 'desc') // Safe tie-breaker using timestamps
    ->take(5)
    ->get()
    ->map(function ($item) {
        return [
            'date'        => $item->expense_date,
            'created_at'  => $item->created_at,
            'description' => $item->Description ?? 'Expense Payment',
            'category'    => $item->category->expensecategory ?? 'Expense',
            'amount'      => $item->expenseamount,
            'type'        => 'expense'
        ];
    });

// 3. Merge and sort safely
$recentTransactions = $incomes->concat($expenses)
    ->sort(function ($a, $b) {
        if ($a['date'] == $b['date']) {
            return $b['created_at'] <=> $a['created_at'];
        }
        return $b['date'] <=> $a['date'];
    })
    ->take(5);

        // Balance warning logic
        if ($balance <= 0) {
            $statusMessage = 'Low Balance / Overdrawn';
            $statusClass = 'text-danger';
            $statusIcon = 'bx-down-arrow-alt';
        } elseif ($balance < 1000) {
            $statusMessage = 'You are on a tight budget';
            $statusClass = 'text-warning';
            $statusIcon = 'bx-error-alt';
        } else {
            $statusMessage = 'Safe to spend';
            $statusClass = 'text-success';
            $statusIcon = 'bx-up-arrow-alt';
        }

        return view('user.userhome', compact(
            'user',
            'monthlyIncome',
            'monthlyExpense',
            'categories',
            'totals',
            'prevMonthExpense',
            'formattedPercentage',
            'isIncrease',
            'budgetLimit',
            'budgetPercentage',
            'progressBarColor',
            'budgetWarning',
            'recentTransactions',
            'statusMessage',
            'statusClass',
            'statusIcon'
        ));
    }

public function createIncome()
{
    // Fetch only categories where is_borrow is 0 (hiding special debt categories)
    $categories = IncomeCategory::where('is_borrow', 0)->get();
    
    return view('user.addincome', compact('categories'));
}

public function createExpense()
{
    // Fetch only categories where is_lent is 0 (hiding special lent categories)
    $categories = ExpenseCategory::where('is_lent', 0)->get();
    
    return view('user.addexpense', compact('categories'));
}

    public function store(Request $request)
    {
        // 1. Validate inputs
        $validated = $request->validate([
            'incomeamount'      => 'required|numeric|min:0.01',
            'incomecategory_id' => ['required', 'exists:income_categories,incomecategory_id'],
            'income_date'       => 'required|date',
            'Description'       => 'nullable|string',
        ]);

        // 2. Database transaction to save income & update user's total income summary
        DB::transaction(function () use ($validated) {
            $user = Auth::user();

            AddIncome::create([
                'userid'            => $user->userid,
                'incomeamount'      => $validated['incomeamount'],
                'incomecategory_id' => $validated['incomecategory_id'],
                'income_date'       => $validated['income_date'],
                'Description'       => $validated['Description'] ?? null,
            ]);

            $totalIncome = AddIncome::where('userid', $user->userid)->sum('incomeamount');
            $user->income = $totalIncome;
            $user->save();
        });

        // 3. Redirect back to userhome with success message flash
        return redirect()->route('userhome')->with('success', 'Income added successfully and total updated!');
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'expenseamount'      => 'required|numeric|min:0.01',
            'expensecategory_id' => ['required', 'exists:App\Models\ExpenseCategory,expensecategory_id'],
            'expense_date'       => 'required|date',
            'Description'        => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $user = Auth::user();

            AddExpense::create([
                'userid'             => $user->userid,
                'expenseamount'      => $validated['expenseamount'],
                'expensecategory_id' => $validated['expensecategory_id'],
                'expense_date'       => $validated['expense_date'],
                'Description'        => $validated['Description'] ?? null,
            ]);

            $totalIncome = AddIncome::where('userid', $user->userid)->sum('incomeamount');
            $totalExpense = AddExpense::where('userid', $user->userid)->sum('expenseamount');
            $netIncome = $totalIncome - $totalExpense;

            User::where('userid', $user->userid)->update([
                'income' => $netIncome
            ]);
        });

        return redirect()->route('addexpense')->with('success', 'Expense recorded and income deducted successfully!');
    }

    public function indexIncome(Request $request)
    {
        $user = Auth::user();

        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        $incomes = AddIncome::where('userid', $user->userid)
            ->whereMonth('income_date', $selectedMonth)
            ->whereYear('income_date', $selectedYear)
            ->with('category')
            ->orderBy('income_date', 'desc')
            ->get();

        $totalFilteredIncome = $incomes->sum('incomeamount');

        return view('user.viewincome', compact('incomes', 'selectedMonth', 'selectedYear', 'totalFilteredIncome'));
    }

    public function exportIncome(Request $request)
    {
        $user = Auth::user();
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        $incomes = AddIncome::where('userid', $user->userid)
            ->whereMonth('income_date', $selectedMonth)
            ->whereYear('income_date', $selectedYear)
            ->with('category')
            ->orderBy('income_date', 'desc')
            ->get();

        $totalFilteredIncome = $incomes->sum('incomeamount');
        $monthName = \DateTime::createFromFormat('!m', $selectedMonth)->format('F');
        $fileName = 'Income_Report_' . $monthName . '_' . $selectedYear . '.xls';

        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $html = '
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
                table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
                th { background-color: #198754; color: #ffffff; border: 1px solid #ddd; padding: 10px; text-align: left; font-weight: bold; }
                td { border: 1px solid #ddd; padding: 8px; text-align: left; color: #333; }
                .title { font-size: 16px; font-weight: bold; color: #198754; margin-bottom: 5px; }
                .meta { font-size: 12px; color: #666; margin-bottom: 3px; }
                .total-row { background-color: #f8f9fa; font-weight: bold; }
                .amount { text-align: right; }
            </style>
        </head>
        <body>
            <table>
                <tr>
                    <td colspan="4" style="border: none;">
                        <div class="title">EXPENSE TRACKER - INCOME REPORT</div>
                        <div class="meta"><strong>Period:</strong> ' . $monthName . ' ' . $selectedYear . '</div>
                        <div class="meta"><strong>Generated On:</strong> ' . date('Y-m-d H:i:s') . '</div>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th class="amount">Amount (INR)</th>
                </tr>';

        if ($incomes->isEmpty()) {
            $html .= '<tr><td colspan="4" style="text-align: center;">No income records found for this period.</td></tr>';
        } else {
            foreach ($incomes as $income) {
                $html .= '
                <tr>
                    <td>' . $income->income_date . '</td>
                    <td>' . ($income->category->incomecategory ?? 'N/A') . '</td>
                    <td>' . ($income->Description ?? '-') . '</td>
                    <td class="amount">₹' . number_format($income->incomeamount, 2) . '</td>
                </tr>';
            }
        }

        $html .= '
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>TOTAL INCOME:</strong></td>
                    <td class="amount"><strong>₹' . number_format($totalFilteredIncome, 2) . '</strong></td>
                </tr>
            </table>
        </body>
        </html>';

        return response()->stream(function () use ($html) {
            echo $html;
        }, 200, $headers);
    }

    public function indexExpense(Request $request)
    {
        $user = Auth::user();

        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        $expenses = AddExpense::where('userid', $user->userid)
            ->whereMonth('expense_date', $selectedMonth)
            ->whereYear('expense_date', $selectedYear)
            ->with('category')
            ->orderBy('expense_date', 'desc')
            ->get();

        $totalFilteredExpense = $expenses->sum('expenseamount');

        return view('user.viewexpense', compact('expenses', 'selectedMonth', 'selectedYear', 'totalFilteredExpense'));
    }

    public function exportExpense(Request $request)
    {
        $user = Auth::user();
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));

        $expenses = AddExpense::where('userid', $user->userid)
            ->whereMonth('expense_date', $selectedMonth)
            ->whereYear('expense_date', $selectedYear)
            ->with('category')
            ->orderBy('expense_date', 'desc')
            ->get();

        $totalFilteredExpense = $expenses->sum('expenseamount');
        $monthName = \DateTime::createFromFormat('!m', $selectedMonth)->format('F');
        $fileName = 'Expense_Report_' . $monthName . '_' . $selectedYear . '.xls';

        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $html = '
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
                table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
                th { background-color: #dc3545; color: #ffffff; border: 1px solid #ddd; padding: 10px; text-align: left; font-weight: bold; }
                td { border: 1px solid #ddd; padding: 8px; text-align: left; color: #333; }
                .title { font-size: 16px; font-weight: bold; color: #dc3545; margin-bottom: 5px; }
                .meta { font-size: 12px; color: #666; margin-bottom: 3px; }
                .total-row { background-color: #f8f9fa; font-weight: bold; }
                .amount { text-align: right; }
            </style>
        </head>
        <body>
            <table>
                <tr>
                    <td colspan="4" style="border: none;">
                        <div class="title">SmartSpendAI - EXPENSE REPORT</div>
                        <div class="meta"><strong>Period:</strong> ' . $monthName . ' ' . $selectedYear . '</div>
                        <div class="meta"><strong>Generated On:</strong> ' . date('Y-m-d H:i:s') . '</div>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th class="amount">Amount (INR)</th>
                </tr>';

        if ($expenses->isEmpty()) {
            $html .= '<tr><td colspan="4" style="text-align: center;">No expense records found for this period.</td></tr>';
        } else {
            foreach ($expenses as $expense) {
                $html .= '
                <tr>
                    <td>' . $expense->expense_date . '</td>
                    <td>' . ($expense->category->expensecategory ?? 'N/A') . '</td>
                    <td>' . ($expense->Description ?? '-') . '</td>
                    <td class="amount">₹' . number_format($expense->expenseamount, 2) . '</td>
                </tr>';
            }
        }

        $html .= '
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>TOTAL EXPENSES:</strong></td>
                    <td class="amount"><strong>₹' . number_format($totalFilteredExpense, 2) . '</strong></td>
                </tr>
            </table>
        </body>
        </html>';

        return response()->stream(function () use ($html) {
            echo $html;
        }, 200, $headers);
    }

    public function createBudget()
    {
        return view('user.add-budget');
    }

    public function storeBudget(Request $request)
    {
        $request->validate([
            'budget_amount' => 'required|numeric|min:0',
            'month'         => 'required|string|size:2',
            'year'          => 'required|digits:4|integer',
        ]);

        $user = Auth::user();

        Budget::updateOrCreate(
            [
                'userid' => $user->userid,
                'month'  => $request->month,
                'year'   => $request->year,
            ],
            [
                'budget_amount' => $request->budget_amount,
            ]
        );

        return redirect()->route('userhome')->with('success', 'Monthly budget successfully saved and updated!');
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'username' => 'required|string|max:255|unique:User,username,' . $user->userid . ',userid',
        ]);

        $user->update([
            'name' => $request->name,
            'contact' => $request->contact,
            'username' => $request->username,
        ]);

        return redirect()->route('user.profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function showForgotForm()
    {
        return view('user.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:User,email']);

        $token = Str::random(60);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        Mail::send('emails.forgot-password', ['resetUrl' => $resetUrl], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('SmartSpendAI - Password Reset Request');
        });

        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('user.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:User,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'This password reset token is invalid.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been successfully reset!');
    }

public function indexDebts()
{
    $user = Auth::user();
    
    // 1. Fetch debts for the logged-in user
    $debts = Dept::where('userid', $user->userid)->latest()->get();

    // 2. Fetch expense categories where money is lent (is_lent = 1)
    $lendCategories = ExpenseCategory::where('is_lent', 1)->get();

    // 3. Fetch income categories where money is borrowed (is_borrow = 1)
    $borrowCategories = IncomeCategory::where('is_borrow', 1)->get();

    // 4. Calculate current main balance (Total Income - Total Expense)
    $totalIncome = AddIncome::where('userid', $user->userid)->sum('incomeamount');
    $totalExpense = AddExpense::where('userid', $user->userid)->sum('expenseamount');
    $mainBalance = $totalIncome - $totalExpense;

    // 5. Pass everything to the view
    return view('user.debts', compact('debts', 'lendCategories', 'borrowCategories', 'mainBalance'));
}

public function storeDebt(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'purpose' => 'required|string|max:255',
        'debt_amount' => 'required|numeric|min:0.01',
        'category_id' => 'required|integer', 
        'fromdate' => 'required|date',
        'todate' => 'required|date|after_or_equal:fromdate',
        'debt_type' => 'required|in:borrow,lent',
    ]);

    DB::transaction(function () use ($request) {
        $userId = Auth::id();

        // 1. Save into your depts table including the selected category_id
        Dept::create([
            'userid' => $userId,
            'name' => $request->name,
            'purpose' => $request->purpose,
            'debt_amount' => $request->debt_amount,
            'category_id' => $request->category_id, // Dynamically saved here
            'fromdate' => $request->fromdate,
            'todate' => $request->todate,
            'debt_type' => $request->debt_type,
            'status' => 'pending',
        ]);

        // 2. Reflect in history tables (AddIncome or AddExpense)
        if ($request->debt_type === 'borrow') {
            AddIncome::create([
                'userid' => $userId,
                'incomecategory_id' => $request->category_id,
                'incomeamount' => $request->debt_amount,
                'income_date' => $request->fromdate,
                'Description' => "Borrowed from {$request->name}: {$request->purpose}",
            ]);
        } else {
            AddExpense::create([
                'userid' => $userId,
                'expensecategory_id' => $request->category_id,
                'expenseamount' => $request->debt_amount,
                'expense_date' => $request->fromdate,
                'Description' => "Lent to {$request->name}: {$request->purpose}",
            ]);
        }

        // 3. Recalculate main balance
        $user = User::where('userid', $userId)->first();
        $totalIncome = AddIncome::where('userid', $userId)->sum('incomeamount');
        $totalExpense = AddExpense::where('userid', $userId)->sum('expenseamount');
        
        $user->income = $totalIncome - $totalExpense;
        $user->save();
    });

    return redirect()->route('user.debts.index')->with('success', 'Debt recorded and main balance updated successfully!');
}

    public function updateDebtStatus($id)
{
    DB::transaction(function () use ($id) {
        $userId = Auth::id();
        $debt = Dept::where('dept_id', $id)->where('userid', $userId)->firstOrFail();

        // 1. Update debt status to repaid
        $debt->status = 'repaid';
        $debt->save();

        // 2. Handle Repayments using the exact matching database category names
        if ($debt->debt_type === 'borrow') {
            // Find the 'Debt Repayment Paid' category dynamically
            $expenseCategory = ExpenseCategory::where('expensecategory', 'Debt Repayment Paid')->first();
            $expenseCatId = $expenseCategory ? $expenseCategory->expensecategory_id : 1; // Fallback to 1 if not found

            AddExpense::create([
                'userid' => $userId,
                'expensecategory_id' => $expenseCatId, 
                'expenseamount' => $debt->debt_amount, 
                'expense_date' => Carbon::now(),
                'Description' => "Repaid borrowed amount to {$debt->name}",
            ]);
        } 
        else {
            // Find the 'Debt Repayment Received' category dynamically
            $incomeCategory = IncomeCategory::where('incomecategory', 'Debt Repayment Received')->first();
            $incomeCatId = $incomeCategory ? $incomeCategory->incomecategory_id : 1; // Fallback to 1 if not found

            AddIncome::create([
                'userid' => $userId,
                'incomecategory_id' => $incomeCatId, 
                'incomeamount' => $debt->debt_amount, 
                'income_date' => Carbon::now(),
                'Description' => "Received repayment from {$debt->name}",
            ]);
        }

        // 3. Recalculate and update the user's main balance
        $user = User::where('userid', $userId)->first();
        $totalIncome = AddIncome::where('userid', $userId)->sum('incomeamount');
        $totalExpense = AddExpense::where('userid', $userId)->sum('expenseamount');
        
        $user->income = $totalIncome - $totalExpense;
        $user->save();
    });

    return redirect()->route('user.debts.index')->with('success', 'Debt marked as repaid with correct category and balance updated!');
}

  

    public function indexSavings()
    {
        $user = Auth::user();
        $savings = Savings::where('userid', $user->userid)->latest()->get();

        // Eager load savingsGoal to display the purpose name in the history table
        $transactions = SavingsTransaction::with('savingsGoal')
            ->where('userid', $user->userid)
            ->latest()
            ->get();

        $mainBalance = $user->income;

        return view('user.savings', compact('savings', 'transactions', 'mainBalance'));
    }

    public function storeSavings(Request $request)
    {
        $request->validate([
            'saving_purpose' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'saved_amount' => 'required|numeric|min:0',
            'end_date' => 'required|date',
        ]);

        $user = Auth::user();
        $savedAmount = $request->saved_amount ?? 0.00;

        if ($savedAmount > 0) {
            if ($user->income < $savedAmount) {
                return back()->withErrors(['saved_amount' => 'Insufficient main balance available.'])->withInput();
            }

            $user->income -= $savedAmount;
            $user->save();
        }

        $goal = Savings::create([
            'userid' => $user->userid,
            'saving_purpose' => $request->saving_purpose,
            'target_amount' => $request->target_amount,
            'saved_amount' => $savedAmount,
            'end_date' => $request->end_date,
        ]);

        if ($savedAmount > 0) {
            SavingsTransaction::create([
                'savings_id' => $goal->savings_id,
                'userid' => $user->userid,
                'deposit_amount' => $savedAmount,
            ]);
        }

        return redirect()->route('user.savings.index')->with('success', 'Savings goal created and transaction logged!');
    }

public function depositSavings(Request $request, $id)
{
    $request->validate([
        'deposit_amount' => 'required|numeric|min:1'
    ]);

    $user = Auth::user();

    $goal = Savings::where('savings_id', $id)
                ->where('userid', $user->userid)
                ->firstOrFail();

    // Calculate remaining amount needed to achieve the goal
    $remainingNeeded = $goal->target_amount - $goal->saved_amount;

    if ($request->deposit_amount > $remainingNeeded) {
        return back()->withErrors(['deposit_amount' => 'Deposit exceeds remaining target. You only need ₹' . number_format($remainingNeeded, 2) . ' to achieve this goal.']);
    }

    if ($request->deposit_amount > $user->income) {
        return back()->withErrors(['deposit_amount' => 'Insufficient main balance available.']);
    }

    $user->income -= $request->deposit_amount;
    $user->save();

    $goal->saved_amount += $request->deposit_amount;
    $goal->save();

    SavingsTransaction::create([
        'savings_id' => $goal->savings_id,
        'userid' => $user->userid,
        'deposit_amount' => $request->deposit_amount,
    ]);

    return redirect()->route('user.savings.index')->with('success', 'Amount added and transaction recorded in history!');
}

    public function updateDeadline(Request $request, $id)
    {
        $request->validate([
            'end_date' => 'required|date'
        ]);

        $user = Auth::user();
        $goal = Savings::where('savings_id', $id)
            ->where('userid', $user->userid)
            ->firstOrFail();

        $goal->end_date = $request->end_date;
        $goal->save();

        return redirect()->route('user.savings.index')->with('success', 'Savings deadline updated successfully!');
    }

    public function destroySavings($id)
    {
        $user = Auth::user();

        $goal = Savings::where('savings_id', $id)
            ->where('userid', $user->userid)
            ->firstOrFail();

        DB::transaction(function () use ($user, $goal) {
            // Only refund to main balance if the goal was NOT achieved (abandoned early)
            if ($goal->saved_amount < $goal->target_amount && $goal->saved_amount > 0) {
                $user->income += $goal->saved_amount;
                $user->save();
            }

            // Delete the goal (associated transactions will safely set savings_id to null)
            $goal->delete();
        });

        return redirect()->route('user.savings.index')->with('success', 'Savings goal deleted successfully!');
    }
}