<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Financial Report - {{ $periodLabel }}</title>
  <style>
    @page {
      margin: 25px 30px;
    }
    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 11px;
      color: #1e293b;
      line-height: 1.4;
      margin: 0;
      padding: 0;
    }
    .header-table {
      width: 100%;
      border-bottom: 2px solid #2563eb;
      padding-bottom: 12px;
      margin-bottom: 16px;
    }
    .brand-title {
      font-size: 20px;
      font-weight: bold;
      color: #2563eb;
      margin: 0;
    }
    .brand-subtitle {
      font-size: 11px;
      color: #64748b;
      margin-top: 2px;
    }
    .meta-text {
      text-align: right;
      font-size: 10px;
      color: #64748b;
    }
    .meta-text strong {
      color: #0f172a;
    }

    /* Summary Metric Boxes */
    .summary-table {
      width: 100%;
      margin-bottom: 20px;
      border-collapse: separate;
      border-spacing: 8px 0;
    }
    .metric-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 10px 12px;
      text-align: center;
    }
    .metric-label {
      font-size: 9px;
      text-transform: uppercase;
      color: #64748b;
      font-weight: bold;
      margin-bottom: 4px;
    }
    .metric-val {
      font-size: 14px;
      font-weight: bold;
    }
    .text-success { color: #0f766e; }
    .text-danger { color: #dc2626; }
    .text-primary { color: #2563eb; }

    /* Tables */
    .section-title {
      font-size: 13px;
      font-weight: bold;
      color: #0f172a;
      margin-bottom: 8px;
      border-left: 3px solid #2563eb;
      padding-left: 6px;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    .data-table th {
      background-color: #f1f5f9;
      color: #475569;
      font-weight: bold;
      font-size: 9px;
      text-transform: uppercase;
      border: 1px solid #cbd5e1;
      padding: 6px 8px;
      text-align: left;
    }
    .data-table td {
      border: 1px solid #e2e8f0;
      padding: 6px 8px;
      font-size: 10px;
    }
    .data-table tr:nth-child(even) td {
      background-color: #f8fafc;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .total-row td {
      background: #e2e8f0 !important;
      font-weight: bold;
      font-size: 10px;
    }
    .badge {
      display: inline-block;
      padding: 2px 6px;
      font-size: 8px;
      font-weight: bold;
      border-radius: 4px;
      text-transform: uppercase;
    }
    .badge-income { background: #dcfce7; color: #166534; }
    .badge-expense { background: #fee2e2; color: #991b1b; }
    .badge-lent { background: #fef3c7; color: #92400e; }
    .badge-borrow { background: #e0f2fe; color: #075985; }

    .footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 20px;
      font-size: 9px;
      color: #94a3b8;
      border-top: 1px solid #e2e8f0;
      padding-top: 5px;
      text-align: center;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <table class="header-table">
    <tr>
      <td style="vertical-align: middle;">
        <h1 class="brand-title">Expense Tracker</h1>
        <div class="brand-subtitle">Admin Financial Control Center &bull; {{ ucfirst($reportType) }} Report</div>
      </td>
      <td class="meta-text" style="vertical-align: middle;">
        <div><strong>Period:</strong> {{ $periodLabel }}</div>
        <div><strong>Generated:</strong> {{ date('M d, Y h:i A') }}</div>
        <div><strong>Role:</strong> Administrator</div>
      </td>
    </tr>
  </table>

  <!-- Summary Metric Boxes -->
  <table class="summary-table">
    <tr>
      <td class="metric-box" style="width: 25%;">
        <div class="metric-label">Total Inflow</div>
        <div class="metric-val text-success">₹{{ number_format($totalIncome, 2) }}</div>
      </td>
      <td class="metric-box" style="width: 25%;">
        <div class="metric-label">Total Outflow</div>
        <div class="metric-val text-danger">₹{{ number_format($totalExpense, 2) }}</div>
      </td>
      <td class="metric-box" style="width: 25%;">
        <div class="metric-label">Net Balance</div>
        <div class="metric-val {{ $netBalance >= 0 ? 'text-primary' : 'text-danger' }}">
          {{ $netBalance >= 0 ? '+' : '' }}₹{{ number_format($netBalance, 2) }}
        </div>
      </td>
      <td class="metric-box" style="width: 25%;">
        <div class="metric-label">Total Records</div>
        <div class="metric-val" style="color: #0f172a;">{{ $transactions->count() }}</div>
      </td>
    </tr>
  </table>

  <!-- 1. OVERVIEW & TRANSACTIONS TABLE -->
  @if(in_array($reportType, ['overview', 'transactions']))
    <div class="section-title">Transactions Ledger</div>
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 25px;">#</th>
          <th style="width: 70px;">Date</th>
          <th style="width: 120px;">User</th>
          <th style="width: 65px;">Type</th>
          <th style="width: 110px;">Category</th>
          <th>Description</th>
          <th class="text-right" style="width: 90px;">Amount</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $key => $tx)
          <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $tx->date->format('M d, Y') }}</td>
            <td>{{ $tx->user_name }}</td>
            <td class="text-center">
              <span class="badge {{ $tx->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                {{ ucfirst($tx->type) }}
              </span>
            </td>
            <td>{{ $tx->category }}</td>
            <td>{{ $tx->description }}</td>
            <td class="text-right {{ $tx->type === 'income' ? 'text-success' : 'text-danger' }}" style="font-weight: bold;">
              {{ $tx->type === 'income' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center" style="padding: 20px; color: #94a3b8;">
              No transactions recorded for this period.
            </td>
          </tr>
        @endforelse
      </tbody>
      @if($transactions->isNotEmpty())
        <tfoot>
          <tr class="total-row">
            <td colspan="6" class="text-right">TOTAL INFLOWS:</td>
            <td class="text-right text-success">+₹{{ number_format($totalIncome, 2) }}</td>
          </tr>
          <tr class="total-row">
            <td colspan="6" class="text-right">TOTAL OUTFLOWS:</td>
            <td class="text-right text-danger">-₹{{ number_format($totalExpense, 2) }}</td>
          </tr>
          <tr class="total-row" style="background: #cbd5e1 !important;">
            <td colspan="6" class="text-right">NET FINANCIAL SURPLUS / DEFICIT:</td>
            <td class="text-right {{ $netBalance >= 0 ? 'text-primary' : 'text-danger' }}">
              {{ $netBalance >= 0 ? '+' : '' }}₹{{ number_format($netBalance, 2) }}
            </td>
          </tr>
        </tfoot>
      @endif
    </table>

  <!-- 2. EXPENSES REPORT -->
  @elseif($reportType === 'expenses')
    <div class="section-title">Expense Transactions Ledger</div>
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 25px;">#</th>
          <th style="width: 80px;">Date</th>
          <th style="width: 140px;">User</th>
          <th style="width: 120px;">Category</th>
          <th>Description</th>
          <th class="text-right" style="width: 90px;">Amount</th>
        </tr>
      </thead>
      <tbody>
        @forelse($expenses as $key => $exp)
          <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $exp->expense_date ? \Carbon\Carbon::parse($exp->expense_date)->format('M d, Y') : $exp->created_at->format('M d, Y') }}</td>
            <td>{{ $exp->user->name ?? 'User #' . $exp->userid }}</td>
            <td>{{ $exp->category->expensecategory ?? 'Expense' }}</td>
            <td>{{ $exp->Description ?? '—' }}</td>
            <td class="text-right text-danger" style="font-weight: bold;">₹{{ number_format($exp->expenseamount, 2) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center" style="padding: 20px; color: #94a3b8;">No expense records found.</td>
          </tr>
        @endforelse
      </tbody>
      @if($expenses->isNotEmpty())
        <tfoot>
          <tr class="total-row">
            <td colspan="5" class="text-right">TOTAL EXPENSES:</td>
            <td class="text-right text-danger">₹{{ number_format($totalExpense, 2) }}</td>
          </tr>
        </tfoot>
      @endif
    </table>

  <!-- 3. INCOMES REPORT -->
  @elseif($reportType === 'incomes')
    <div class="section-title">Income Transactions Ledger</div>
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 25px;">#</th>
          <th style="width: 80px;">Date</th>
          <th style="width: 140px;">User</th>
          <th style="width: 120px;">Category</th>
          <th>Description</th>
          <th class="text-right" style="width: 90px;">Amount</th>
        </tr>
      </thead>
      <tbody>
        @forelse($incomes as $key => $inc)
          <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $inc->income_date ? \Carbon\Carbon::parse($inc->income_date)->format('M d, Y') : $inc->created_at->format('M d, Y') }}</td>
            <td>{{ $inc->user->name ?? 'User #' . $inc->userid }}</td>
            <td>{{ $inc->category->incomecategory ?? 'Income' }}</td>
            <td>{{ $inc->Description ?? '—' }}</td>
            <td class="text-right text-success" style="font-weight: bold;">₹{{ number_format($inc->incomeamount, 2) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center" style="padding: 20px; color: #94a3b8;">No income records found.</td>
          </tr>
        @endforelse
      </tbody>
      @if($incomes->isNotEmpty())
        <tfoot>
          <tr class="total-row">
            <td colspan="5" class="text-right">TOTAL INCOMES:</td>
            <td class="text-right text-success">₹{{ number_format($totalIncome, 2) }}</td>
          </tr>
        </tfoot>
      @endif
    </table>

  <!-- 4. DEBTS & LOANS REPORT -->
  @elseif($reportType === 'debts')
    <div class="section-title">Debts & Loans Ledger</div>
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 25px;">#</th>
          <th style="width: 110px;">User</th>
          <th style="width: 110px;">Person / Party</th>
          <th style="width: 55px;">Type</th>
          <th>Purpose</th>
          <th style="width: 75px;">From Date</th>
          <th style="width: 75px;">Due Date</th>
          <th style="width: 60px;">Status</th>
          <th class="text-right" style="width: 80px;">Amount</th>
        </tr>
      </thead>
      <tbody>
        @forelse($debts as $key => $debt)
          <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $debt->user->name ?? 'User #' . $debt->userid }}</td>
            <td><strong>{{ $debt->name }}</strong></td>
            <td class="text-center">
              <span class="badge {{ $debt->debt_type === 'lent' ? 'badge-lent' : 'badge-borrow' }}">
                {{ ucfirst($debt->debt_type) }}
              </span>
            </td>
            <td>{{ $debt->purpose ?? '—' }}</td>
            <td>{{ $debt->fromdate ? \Carbon\Carbon::parse($debt->fromdate)->format('M d, Y') : '—' }}</td>
            <td>{{ $debt->todate ? \Carbon\Carbon::parse($debt->todate)->format('M d, Y') : '—' }}</td>
            <td class="text-center">{{ ucfirst($debt->status ?? 'pending') }}</td>
            <td class="text-right" style="font-weight: bold;">₹{{ number_format($debt->debt_amount, 2) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center" style="padding: 20px; color: #94a3b8;">No debts or loans found.</td>
          </tr>
        @endforelse
      </tbody>
      @if($debts->isNotEmpty())
        <tfoot>
          <tr class="total-row">
            <td colspan="8" class="text-right">TOTAL LENT:</td>
            <td class="text-right">₹{{ number_format($totalLent, 2) }}</td>
          </tr>
          <tr class="total-row">
            <td colspan="8" class="text-right">TOTAL BORROWED:</td>
            <td class="text-right">₹{{ number_format($totalBorrowed, 2) }}</td>
          </tr>
        </tfoot>
      @endif
    </table>

  <!-- 5. USERS SUMMARY -->
  @elseif($reportType === 'users')
    <div class="section-title">Registered Users Financial Summary</div>
    <table class="data-table">
      <thead>
        <tr>
          <th style="width: 25px;">#</th>
          <th>Name</th>
          <th>Email</th>
          <th style="width: 70px;">Role</th>
          <th style="width: 90px;">Joined</th>
          <th class="text-right" style="width: 100px;">Recorded Balance</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $key => $u)
          <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td><strong>{{ $u->name }}</strong></td>
            <td>{{ $u->email }}</td>
            <td class="text-center">{{ ucfirst($u->role) }}</td>
            <td>{{ $u->created_at->format('M d, Y') }}</td>
            <td class="text-right" style="font-weight: bold;">₹{{ number_format($u->income ?? 0, 2) }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center" style="padding: 20px; color: #94a3b8;">No users found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  @endif

  <div class="footer">
    Expense Tracker &bull; Confidential Administrative Report &bull; Page generated automatically
  </div>

</body>
</html>
