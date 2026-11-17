<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AddIncome;
use App\Models\AddExpense;
use App\Models\Dept;
use App\Models\Budget;
use App\Models\Savings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function adminhome()
    {
        $admin = Auth::user();
        
        // 1. Total count of users with role 'user'
        $userCount = User::where('role', 'user')->count();
        $recentUsers = User::latest()->take(5)->get();
        
        // 2. Users with role 'user' registered this month
        $usersThisMonth = User::where('role', 'user')
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->count();
                            
        // 3. Users with role 'user' registered last month
        $usersLastMonth = User::where('role', 'user')
                            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                            ->whereYear('created_at', Carbon::now()->subMonth()->year)
                            ->count();
                            
        // 4. Calculate percentage growth
        $percentageChange = 0;
        if ($usersLastMonth > 0) {
            $percentageChange = (($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100;
        } else {
            $percentageChange = $usersThisMonth > 0 ? 100 : 0;
        }

        $formattedChange = round($percentageChange, 1);

        // 5. Overall Financial Metrics (Total Income, Expenses, and Net Balance across all users)
        $totalIncome = AddIncome::sum('incomeamount');
        $totalExpense = AddExpense::sum('expenseamount');
        $netBalance = $totalIncome - $totalExpense;

        $recentIncomes = AddIncome::with('user')->latest()->take(2)->get();
        $recentExpenses = AddExpense::with('user')->latest()->take(2)->get();

        // 6. Current Month Metrics for Expense Tracker
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $thisMonthIncome = AddIncome::whereMonth('income_date', $currentMonth)
                            ->whereYear('income_date', $currentYear)
                            ->sum('incomeamount');

        $thisMonthExpense = AddExpense::whereMonth('expense_date', $currentMonth)
                            ->whereYear('expense_date', $currentYear)
                            ->sum('expenseamount');

        $thisMonthNet = $thisMonthIncome - $thisMonthExpense;
        $savingsRate = $thisMonthIncome > 0 ? round((($thisMonthIncome - $thisMonthExpense) / $thisMonthIncome) * 100, 1) : 0;
        $totalTransactionsCount = AddIncome::count() + AddExpense::count();

        // 7. Top Spending Categories Breakdown
        $topExpenseCategories = AddExpense::select('expensecategory_id', DB::raw('SUM(expenseamount) as total'))
            ->with('category')
            ->groupBy('expensecategory_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 7a. Expense Categories Breakdown for Chart & Analytics
        $expenseCategoriesBreakdown = AddExpense::select('expensecategory_id', DB::raw('SUM(expenseamount) as total'))
            ->with('category')
            ->groupBy('expensecategory_id')
            ->orderByDesc('total')
            ->get();

        $expenseChartLabels = $expenseCategoriesBreakdown->map(function ($item) {
            return $item->category->expensecategory ?? 'Uncategorized';
        })->values();

        $expenseChartData = $expenseCategoriesBreakdown->map(function ($item) {
            return (float)$item->total;
        })->values();

        // 7b. Income Categories Breakdown for Chart & Analytics
        $incomeCategoriesBreakdown = AddIncome::select('incomecategory_id', DB::raw('SUM(incomeamount) as total'))
            ->with('category')
            ->groupBy('incomecategory_id')
            ->orderByDesc('total')
            ->get();

        $incomeChartLabels = $incomeCategoriesBreakdown->map(function ($item) {
            return $item->category->incomecategory ?? 'Uncategorized';
        })->values();

        $incomeChartData = $incomeCategoriesBreakdown->map(function ($item) {
            return (float)$item->total;
        })->values();

        // 8. Platform Debts & Categories Summary
        $totalLent = Dept::where('debt_type', 'lent')->sum('debt_amount');
        $totalBorrowed = Dept::where('debt_type', 'borrow')->sum('debt_amount');
        $pendingDebtsCount = Dept::where('status', 'pending')->count();
        $repaidDebtsCount = Dept::where('status', 'repaid')->count();
        $expenseCategoriesCount = ExpenseCategory::count();
        $incomeCategoriesCount = IncomeCategory::count();

        // 9. Recent Platform Transactions (Combined Incomes & Expenses)
        $latestIncomes = AddIncome::with(['user', 'category'])
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'type' => 'income',
                    'user_name' => $item->user->name ?? 'User',
                    'user_email' => $item->user->email ?? '',
                    'category' => $item->category->incomecategory ?? 'Income',
                    'amount' => $item->incomeamount,
                    'date' => $item->income_date ? Carbon::parse($item->income_date) : $item->created_at,
                    'description' => $item->Description,
                ];
            });

        $latestExpenses = AddExpense::with(['user', 'category'])
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($item) {
                return (object)[
                    'type' => 'expense',
                    'user_name' => $item->user->name ?? 'User',
                    'user_email' => $item->user->email ?? '',
                    'category' => $item->category->expensecategory ?? 'Expense',
                    'amount' => $item->expenseamount,
                    'date' => $item->expense_date ? Carbon::parse($item->expense_date) : $item->created_at,
                    'description' => $item->Description,
                ];
            });

        $recentTransactions = $latestIncomes->concat($latestExpenses)->sortByDesc('date')->take(6);

        return view('admin.adminhome', compact(
            'admin',
            'userCount', 
            'formattedChange', 
            'recentUsers', 
            'totalIncome', 
            'totalExpense', 
            'netBalance',
            'recentExpenses',
            'recentIncomes',
            'thisMonthIncome',
            'thisMonthExpense',
            'thisMonthNet',
            'savingsRate',
            'totalTransactionsCount',
            'topExpenseCategories',
            'expenseCategoriesBreakdown',
            'expenseChartLabels',
            'expenseChartData',
            'incomeCategoriesBreakdown',
            'incomeChartLabels',
            'incomeChartData',
            'totalLent',
            'totalBorrowed',
            'pendingDebtsCount',
            'repaidDebtsCount',
            'expenseCategoriesCount',
            'incomeCategoriesCount',
            'recentTransactions'
        ));
    }

    public function storeIncome(Request $request)
    {
        $request->validate([
            'incomecategory' => 'required|string|max:255|unique:income_categories,incomecategory',
        ], [
            'incomecategory.unique' => 'This income category already exists.'
        ]);

        IncomeCategory::create([
            'incomecategory' => $request->incomecategory,
        ]);

        return back()->with('success', 'Income category added successfully!');
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'expensecategory' => 'required|string|max:255|unique:expense_categories,expensecategory',
        ], [
            'expensecategory.unique' => 'This expense category already exists.'
        ]);

        ExpenseCategory::create([
            'expensecategory' => $request->expensecategory,
        ]);

        return back()->with('success', 'Expense category added successfully!');
    }

    // Display
    public function createExpense(Request $request)
    {
        $search = $request->query('search');
        $query = ExpenseCategory::orderBy('expensecategory_id', 'desc');
        if (!empty($search)) {
            $query->where('expensecategory', 'like', '%' . $search . '%');
        }
        $expenses = $query->get();
        return view('admin.expense_category', compact('expenses', 'search'));
    }

    public function deleteExpense($id)
    {
        $expense = ExpenseCategory::findOrFail($id);
        $expense->delete();
        return back()->with('success', 'Expense category deleted successfully!');
    }

    public function editExpense($id)
    {
        $expense = ExpenseCategory::findOrFail($id);
        return view('admin.edit_expense_category', compact('expense'));
    }

    // Update
public function updateExpense(Request $request, $id)
{
    $request->validate([
        'expensecategory' => 'required|string|max:255',
        'is_lent' => 'required|integer|in:0,1,2',
    ]);

    $expense = ExpenseCategory::findOrFail($id);
    $expense->expensecategory = $request->expensecategory;
    $expense->is_lent = $request->is_lent;
    $expense->save();

    return redirect()->route('createExpense')->with('success', 'Expense category updated successfully!');
}

    // 1. Show Add Page & List
    public function createIncome(Request $request)
    {
        $search = $request->query('search');
        $query = IncomeCategory::orderBy('incomecategory_id', 'desc');
        if (!empty($search)) {
            $query->where('incomecategory', 'like', '%' . $search . '%');
        }
        $incomes = $query->get();
        return view('admin.income_category', compact('incomes', 'search'));
    }

    // 2. Show Edit Page
    public function editIncome($id)
    {
        $income = IncomeCategory::findOrFail($id);
        return view('admin.edit_income_category', compact('income'));
    }

    // 3. Process the Update
public function updateIncome(Request $request, $id)
{
    $request->validate([
        'incomecategory' => 'required|string|max:255',
        'is_borrow' => 'required|integer|in:0,1,2',
    ]);

    $income = IncomeCategory::findOrFail($id);
    $income->incomecategory = $request->incomecategory;
    $income->is_borrow = $request->is_borrow;
    $income->save();

    return redirect()->route('createIncome')->with('success', 'Income category updated successfully!');
}

    // Delete
    public function deleteIncome($id)
    {
        $income = IncomeCategory::findOrFail($id);
        $income->delete();
        return back()->with('success', 'Income category deleted successfully!');
    }

    public function indexUsers()
    {
        $users = User::latest()->get();
        return view('admin.viewusers', compact('users'));
    }

    /*
    // ==========================================
    // ADMIN REPORTS & EXPORTS (Disabled)
    // ==========================================
    public function indexReports(Request $request)
    {
        $data = $this->getReportData($request);
        return view('admin.reports', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $fileName = 'Financial_Report_' . ucfirst($data['reportType']) . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['periodLabel']) . '.pdf';
        
        $pdf = Pdf::loadView('admin.reports_pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download($fileName);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);
        $fileName = 'Financial_Report_' . ucfirst($data['reportType']) . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['periodLabel']) . '.xls';

        $headers = [
            "Content-type"        => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->view('admin.reports_excel', $data, 200, $headers);
    }

    protected function getReportData(Request $request)
    {
        $reportType = $request->input('report_type', 'overview');
        $range = $request->input('range', 'this_month');
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        $now = Carbon::now();
        $startDate = null;
        $endDate = null;
        $periodLabel = '';

        switch ($range) {
            case 'last_month':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                $periodLabel = $startDate->format('F Y');
                break;
            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $periodLabel = 'Year ' . $now->year;
                break;
            case 'all_time':
                $startDate = Carbon::create(2000, 1, 1);
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'All Time';
                break;
            case 'custom':
                if ($startDateInput && $endDateInput) {
                    $startDate = Carbon::parse($startDateInput)->startOfDay();
                    $endDate = Carbon::parse($endDateInput)->endOfDay();
                    $periodLabel = $startDate->format('M d, Y') . ' to ' . $endDate->format('M d, Y');
                } else {
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();
                    $periodLabel = $now->format('F Y');
                }
                break;
            case 'this_month':
            default:
                $range = 'this_month';
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $periodLabel = $now->format('F Y');
                break;
        }

        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        // Query Incomes in range
        $incomes = AddIncome::with(['user', 'category'])
            ->whereBetween('income_date', [$startDateStr, $endDateStr])
            ->orderBy('income_date', 'desc')
            ->get();
        $totalIncome = $incomes->sum('incomeamount');

        // Query Expenses in range
        $expenses = AddExpense::with(['user', 'category'])
            ->whereBetween('expense_date', [$startDateStr, $endDateStr])
            ->orderBy('expense_date', 'desc')
            ->get();
        $totalExpense = $expenses->sum('expenseamount');

        $netBalance = $totalIncome - $totalExpense;

        // Category Breakdown in range
        $categoryBreakdown = AddExpense::select('expensecategory_id', DB::raw('SUM(expenseamount) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('expense_date', [$startDateStr, $endDateStr])
            ->with('category')
            ->groupBy('expensecategory_id')
            ->orderByDesc('total')
            ->get();

        // Combined Transactions
        $mappedIncomes = $incomes->map(function ($item) {
            return (object)[
                'type' => 'income',
                'user_name' => $item->user->name ?? 'User #' . $item->userid,
                'user_email' => $item->user->email ?? '',
                'category' => $item->category->incomecategory ?? 'Income',
                'amount' => (float)$item->incomeamount,
                'date' => $item->income_date ? Carbon::parse($item->income_date) : $item->created_at,
                'description' => $item->Description ?? '—',
            ];
        });

        $mappedExpenses = $expenses->map(function ($item) {
            return (object)[
                'type' => 'expense',
                'user_name' => $item->user->name ?? 'User #' . $item->userid,
                'user_email' => $item->user->email ?? '',
                'category' => $item->category->expensecategory ?? 'Expense',
                'amount' => (float)$item->expenseamount,
                'date' => $item->expense_date ? Carbon::parse($item->expense_date) : $item->created_at,
                'description' => $item->Description ?? '—',
            ];
        });

        $transactions = $mappedIncomes->concat($mappedExpenses)->sortByDesc('date');

        // Debts
        $debts = Dept::with('user')->latest()->get();
        $totalLent = Dept::where('debt_type', 'lent')->sum('debt_amount');
        $totalBorrowed = Dept::where('debt_type', 'borrow')->sum('debt_amount');

        // Users
        $users = User::latest()->get();

        return [
            'reportType' => $reportType,
            'range' => $range,
            'startDateInput' => $startDateInput,
            'endDateInput' => $endDateInput,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => $periodLabel,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'incomes' => $incomes,
            'expenses' => $expenses,
            'transactions' => $transactions,
            'categoryBreakdown' => $categoryBreakdown,
            'debts' => $debts,
            'totalLent' => $totalLent,
            'totalBorrowed' => $totalBorrowed,
            'users' => $users,
        ];
    }
    */
}