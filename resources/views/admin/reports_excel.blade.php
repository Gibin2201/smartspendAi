<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <style>
    table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
    th { background-color: #2563eb; color: #ffffff; border: 1px solid #cbd5e1; padding: 10px; text-align: left; font-weight: bold; }
    td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; color: #1e293b; }
    .title { font-size: 16px; font-weight: bold; color: #2563eb; }
    .meta { font-size: 12px; color: #64748b; margin-bottom: 2px; }
    .total-row { background-color: #f1f5f9; font-weight: bold; }
    .amount { text-align: right; }
    .income-text { color: #0f766e; font-weight: bold; }
    .expense-text { color: #dc2626; font-weight: bold; }
  </style>
</head>
<body>
  <table>
    <tr>
      <td colspan="7" style="border: none;">
        <div class="title">EXPENSE TRACKER - ADMIN FINANCIAL REPORT ({{ strtoupper($reportType) }})</div>
        <div class="meta"><strong>Period:</strong> {{ $periodLabel }}</div>
        <div class="meta"><strong>Generated On:</strong> {{ date('Y-m-d H:i:s') }}</div>
        <div class="meta"><strong>Total Inflow:</strong> ₹{{ number_format($totalIncome, 2) }} | <strong>Total Outflow:</strong> ₹{{ number_format($totalExpense, 2) }} | <strong>Net Balance:</strong> ₹{{ number_format($netBalance, 2) }}</div>
        <br>
      </td>
    </tr>

    @if(in_array($reportType, ['overview', 'transactions']))
      <tr>
        <th style="width: 50px;">#</th>
        <th style="width: 110px;">Date</th>
        <th style="width: 150px;">User Name</th>
        <th style="width: 200px;">User Email</th>
        <th style="width: 90px;">Type</th>
        <th style="width: 160px;">Category</th>
        <th>Description</th>
        <th class="amount" style="width: 120px;">Amount (INR)</th>
      </tr>
      @forelse($transactions as $key => $tx)
        <tr>
          <td>{{ $key + 1 }}</td>
          <td>{{ $tx->date->format('Y-m-d') }}</td>
          <td>{{ $tx->user_name }}</td>
          <td>{{ $tx->user_email }}</td>
          <td>{{ ucfirst($tx->type) }}</td>
          <td>{{ $tx->category }}</td>
          <td>{{ $tx->description }}</td>
          <td class="amount {{ $tx->type === 'income' ? 'income-text' : 'expense-text' }}">
            {{ $tx->type === 'income' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
          </td>
        </tr>
      @empty
        <tr><td colspan="8" style="text-align: center;">No transactions found.</td></tr>
      @endforelse
      <tr class="total-row">
        <td colspan="7" style="text-align: right;"><strong>TOTAL INFLOWS:</strong></td>
        <td class="amount income-text"><strong>+₹{{ number_format($totalIncome, 2) }}</strong></td>
      </tr>
      <tr class="total-row">
        <td colspan="7" style="text-align: right;"><strong>TOTAL OUTFLOWS:</strong></td>
        <td class="amount expense-text"><strong>-₹{{ number_format($totalExpense, 2) }}</strong></td>
      </tr>
      <tr class="total-row" style="background-color: #cbd5e1;">
        <td colspan="7" style="text-align: right;"><strong>NET BALANCE:</strong></td>
        <td class="amount"><strong>₹{{ number_format($netBalance, 2) }}</strong></td>
      </tr>

    @elseif($reportType === 'expenses')
      <tr>
        <th style="width: 50px;">#</th>
        <th style="width: 110px;">Date</th>
        <th style="width: 150px;">User</th>
        <th style="width: 160px;">Category</th>
        <th>Description</th>
        <th class="amount" style="width: 120px;">Amount (INR)</th>
      </tr>
      @forelse($expenses as $key => $exp)
        <tr>
          <td>{{ $key + 1 }}</td>
          <td>{{ $exp->expense_date ?? $exp->created_at->format('Y-m-d') }}</td>
          <td>{{ $exp->user->name ?? 'User #' . $exp->userid }}</td>
          <td>{{ $exp->category->expensecategory ?? 'Expense' }}</td>
          <td>{{ $exp->Description ?? '-' }}</td>
          <td class="amount expense-text">₹{{ number_format($exp->expenseamount, 2) }}</td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align: center;">No expense records found.</td></tr>
      @endforelse
      <tr class="total-row">
        <td colspan="5" style="text-align: right;"><strong>TOTAL EXPENSES:</strong></td>
        <td class="amount expense-text"><strong>₹{{ number_format($totalExpense, 2) }}</strong></td>
      </tr>

    @elseif($reportType === 'incomes')
      <tr>
        <th style="width: 50px;">#</th>
        <th style="width: 110px;">Date</th>
        <th style="width: 150px;">User</th>
        <th style="width: 160px;">Category</th>
        <th>Description</th>
        <th class="amount" style="width: 120px;">Amount (INR)</th>
      </tr>
      @forelse($incomes as $key => $inc)
        <tr>
          <td>{{ $key + 1 }}</td>
          <td>{{ $inc->income_date ?? $inc->created_at->format('Y-m-d') }}</td>
          <td>{{ $inc->user->name ?? 'User #' . $inc->userid }}</td>
          <td>{{ $inc->category->incomecategory ?? 'Income' }}</td>
          <td>{{ $inc->Description ?? '-' }}</td>
          <td class="amount income-text">₹{{ number_format($inc->incomeamount, 2) }}</td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align: center;">No income records found.</td></tr>
      @endforelse
      <tr class="total-row">
        <td colspan="5" style="text-align: right;"><strong>TOTAL INCOMES:</strong></td>
        <td class="amount income-text"><strong>₹{{ number_format($totalIncome, 2) }}</strong></td>
      </tr>

    @elseif($reportType === 'debts')
      <tr>
        <th style="width: 50px;">#</th>
        <th style="width: 150px;">User</th>
        <th style="width: 150px;">Person / Party</th>
        <th style="width: 80px;">Type</th>
        <th>Purpose</th>
        <th style="width: 110px;">From Date</th>
        <th style="width: 110px;">Due Date</th>
        <th style="width: 90px;">Status</th>
        <th class="amount" style="width: 120px;">Amount (INR)</th>
      </tr>
      @forelse($debts as $key => $debt)
        <tr>
          <td>{{ $key + 1 }}</td>
          <td>{{ $debt->user->name ?? 'User #' . $debt->userid }}</td>
          <td>{{ $debt->name }}</td>
          <td>{{ ucfirst($debt->debt_type) }}</td>
          <td>{{ $debt->purpose ?? '-' }}</td>
          <td>{{ $debt->fromdate ?? '-' }}</td>
          <td>{{ $debt->todate ?? '-' }}</td>
          <td>{{ ucfirst($debt->status ?? 'pending') }}</td>
          <td class="amount">₹{{ number_format($debt->debt_amount, 2) }}</td>
        </tr>
      @empty
        <tr><td colspan="9" style="text-align: center;">No debt records found.</td></tr>
      @endforelse
      <tr class="total-row">
        <td colspan="8" style="text-align: right;"><strong>TOTAL LENT:</strong></td>
        <td class="amount"><strong>₹{{ number_format($totalLent, 2) }}</strong></td>
      </tr>
      <tr class="total-row">
        <td colspan="8" style="text-align: right;"><strong>TOTAL BORROWED:</strong></td>
        <td class="amount"><strong>₹{{ number_format($totalBorrowed, 2) }}</strong></td>
      </tr>

    @elseif($reportType === 'users')
      <tr>
        <th style="width: 50px;">#</th>
        <th style="width: 180px;">Name</th>
        <th style="width: 220px;">Email</th>
        <th style="width: 100px;">Role</th>
        <th style="width: 130px;">Joined Date</th>
        <th class="amount" style="width: 140px;">Recorded Balance (INR)</th>
      </tr>
      @forelse($users as $key => $u)
        <tr>
          <td>{{ $key + 1 }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ ucfirst($u->role) }}</td>
          <td>{{ $u->created_at->format('Y-m-d') }}</td>
          <td class="amount">₹{{ number_format($u->income ?? 0, 2) }}</td>
        </tr>
      @empty
        <tr><td colspan="6" style="text-align: center;">No users found.</td></tr>
      @endforelse
    @endif
  </table>
</body>
</html>
