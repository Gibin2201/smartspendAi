<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AddIncome;
use App\Models\AddExpense;
use App\Models\Savings;
use App\Models\SavingsTransaction;
use Gemini;

class AIAdvisorController extends Controller
{
    // Show the AI Chatbot View
    public function index()
    {
        return view('user.ai-advisor');
    }

    // Handle the Chat Request
public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $userMessage = $request->input('message');

        // 1. Gather all financial models as context
        $incomes = AddIncome::where('userid', $user->userid)->with('category')->get();
        $expenses = AddExpense::where('userid', $user->userid)->with('category')->get();
        $savingsGoals = Savings::where('userid', $user->userid)->get();
        $savingsTransactions = SavingsTransaction::where('userid', $user->userid)->with('savingsGoal')->get();

        // 2. Format records into a readable text summary for the AI
        $financialContext = "Here is the financial data for the user named {$user->name}:\n";
        $financialContext .= "Main Wallet Balance: ₹{$user->income}\n\n";
        
        $financialContext .= "INCOMES:\n";
        foreach ($incomes as $inc) {
            $cat = $inc->category->incomecategory ?? 'General';
            $financialContext .= "- Date: {$inc->income_date}, Amount: ₹{$inc->incomeamount}, Category: {$cat}, Description: {$inc->Description}\n";
        }

        $financialContext .= "\nEXPENSES:\n";
        foreach ($expenses as $exp) {
            $cat = $exp->category->expensecategory ?? 'General';
            $financialContext .= "- Date: {$exp->expense_date}, Amount: ₹{$exp->expenseamount}, Category: {$cat}, Description: {$exp->Description}\n";
        }

        $financialContext .= "\nSAVINGS GOALS:\n";
        foreach ($savingsGoals as $goal) {
            $status = ($goal->saved_amount >= $goal->target_amount) ? 'Achieved' : 'Active';
            $financialContext .= "- Purpose: {$goal->saving_purpose}, Target: ₹{$goal->target_amount}, Saved: ₹{$goal->saved_amount}, Status: {$status}, Deadline: {$goal->end_date}\n";
        }

        $financialContext .= "\nSAVINGS DEPOSIT HISTORY:\n";
        foreach ($savingsTransactions as $txn) {
            $purpose = $txn->savingsGoal ? $txn->savingsGoal->saving_purpose : 'Deleted Goal';
            $financialContext .= "- Date: {$txn->created_at}, Goal: {$purpose}, Amount Added: ₹{$txn->deposit_amount}\n";
        }

        // 3. Construct the prompt
        $prompt = "You are a professional, friendly AI financial advisor for an expense tracking application. " .
                  "Analyze the user's financial data provided below and answer their question accurately, politely, and concisely in Indian Rupees (₹). " .
                  "If you give advice, base it strictly on their spending, income, and savings patterns.\n\n" .
                  $financialContext . "\n\n" .
                  "User Question: " . $userMessage;

        try {
            // 4. Client instantiation with local SSL verification disabled
            $client = Gemini::factory()
                ->withApiKey(env('GEMINI_API_KEY'))
                ->withHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->make();

            // Utilizing the requested gemini-3.6-flash model
            $result = $client->generativeModel(model: 'gemini-3.6-flash')->generateContent($prompt);
            $aiReply = $result->text();

            return response()->json(['reply' => $aiReply]);
        } catch (\Exception $e) {
            return response()->json([
                'reply' => 'AI Service Error: ' . $e->getMessage()
            ], 500);
        }
    }
}