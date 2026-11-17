@extends('layouts.adminmaster')

@section('title', 'Financial Reports & Exports')

@section('content')
<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">
    
    <!-- Page Heading -->
    <div class="page-heading">
      <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i></span>
        <div>
          <p class="eyebrow mb-1">Analytics & Exports</p>
          <h1 class="h3 mb-1">Financial Reports</h1>
          <p class="text-muted mb-0">Generate, customize, and export platform financial statements in PDF or Excel.</p>
        </div>
      </div>
      <div class="heading-actions d-flex flex-wrap gap-2">
        <a href="{{ route('admin.reports.excel', request()->all()) }}" class="btn btn-sm btn-success shadow-sm" title="Download Excel Spreadsheet">
          <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </a>
        <a href="{{ route('admin.reports.pdf', request()->all()) }}" class="btn btn-sm btn-danger shadow-sm" title="Download PDF Document">
          <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
        <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary shadow-sm" title="Print this report">
          <i class="bi bi-printer me-1"></i> Print
        </button>
      </div>
    </div>

    <!-- Filter Panel -->
    <section class="panel mb-4">
      <div class="panel-header pb-2">
        <div>
          <h2 class="h6 mb-1 section-title"><i class="bi bi-funnel" aria-hidden="true"></i><span>Customize Report</span></h2>
          <p class="text-muted small mb-0">Select criteria, date range, and report category.</p>
        </div>
      </div>
      
      <form action="{{ route('admin.reports') }}" method="GET" class="row g-3 align-items-end pt-1">
        
        <!-- Report Type -->
        <div class="col-12 col-md-4 col-lg-3">
          <label for="report_type" class="form-label fw-medium small text-dark">Report Category</label>
          <select name="report_type" id="report_type" class="form-select form-select-sm">
            <option value="overview" {{ $reportType == 'overview' ? 'selected' : '' }}>Overview & Summary</option>
            <option value="transactions" {{ $reportType == 'transactions' ? 'selected' : '' }}>All Transactions</option>
            <option value="expenses" {{ $reportType == 'expenses' ? 'selected' : '' }}>Expenses Only</option>
            <option value="incomes" {{ $reportType == 'incomes' ? 'selected' : '' }}>Incomes Only</option>
            <option value="debts" {{ $reportType == 'debts' ? 'selected' : '' }}>Debts & Loans Ledger</option>
            <option value="users" {{ $reportType == 'users' ? 'selected' : '' }}>User Financial Roster</option>
          </select>
        </div>

        <!-- Date Range Preset -->
        <div class="col-12 col-md-4 col-lg-3">
          <label for="range" class="form-label fw-medium small text-dark">Timeframe</label>
          <select name="range" id="range" class="form-select form-select-sm" onchange="toggleCustomDates(this.value)">
            <option value="this_month" {{ $range == 'this_month' ? 'selected' : '' }}>This Month ({{ date('F') }})</option>
            <option value="last_month" {{ $range == 'last_month' ? 'selected' : '' }}>Last Month</option>
            <option value="this_year" {{ $range == 'this_year' ? 'selected' : '' }}>This Year ({{ date('Y') }})</option>
            <option value="all_time" {{ $range == 'all_time' ? 'selected' : '' }}>All Time</option>
            <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Custom Date Range</option>
          </select>
        </div>

        <!-- Custom Start Date -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-2 custom-date-col {{ $range == 'custom' ? '' : 'd-none' }}">
          <label for="start_date" class="form-label fw-medium small text-dark">From Date</label>
          <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ $startDateInput ?? $startDate->format('Y-m-d') }}">
        </div>

        <!-- Custom End Date -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-2 custom-date-col {{ $range == 'custom' ? '' : 'd-none' }}">
          <label for="end_date" class="form-label fw-medium small text-dark">To Date</label>
          <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" value="{{ $endDateInput ?? $endDate->format('Y-m-d') }}">
        </div>

        <!-- Submit & Actions -->
        <div class="col-12 col-md-4 col-lg-2 d-flex gap-2">
          <button type="submit" class="btn btn-sm btn-primary w-100 py-1.5 fw-medium">
            <i class="bi bi-filter me-1"></i> Apply Filter
          </button>
        </div>

      </form>
    </section>

    <!-- Period Summary Metric Cards -->
    <section class="row g-3 mb-4" aria-label="Report summary metrics">
      
      <!-- Total Income -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-success">
          <div class="metric-top">
            <span class="metric-label">Period Inflows</span>
            <span class="metric-icon"><i class="bi bi-wallet2" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">₹{{ number_format($totalIncome, 2) }}</div>
          <div class="metric-meta">
            <span class="text-success">{{ $periodLabel }}</span>
          </div>
        </article>
      </div>

      <!-- Total Expense -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-danger">
          <div class="metric-top">
            <span class="metric-label">Period Outflows</span>
            <span class="metric-icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">₹{{ number_format($totalExpense, 2) }}</div>
          <div class="metric-meta">
            <span class="text-danger">{{ $periodLabel }}</span>
          </div>
        </article>
      </div>

      <!-- Net Balance -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-primary">
          <div class="metric-top">
            <span class="metric-label">Net Balance</span>
            <span class="metric-icon"><i class="bi bi-piggy-bank" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
            {{ $netBalance >= 0 ? '+' : '' }}₹{{ number_format($netBalance, 2) }}
          </div>
          <div class="metric-meta">
            <span>{{ $netBalance >= 0 ? 'Surplus' : 'Deficit' }}</span>
          </div>
        </article>
      </div>

      <!-- Total Records -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-warning">
          <div class="metric-top">
            <span class="metric-label">Transactions</span>
            <span class="metric-icon"><i class="bi bi-list-check" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $transactions->count() }}</div>
          <div class="metric-meta">
            <span>Inflows & outflows</span>
          </div>
        </article>
      </div>

    </section>

    <!-- Report Data Table Preview -->
    <section class="panel">
      <div class="panel-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
          <h2 class="h5 mb-1 section-title">
            <i class="bi bi-table" aria-hidden="true"></i>
            <span>{{ ucfirst($reportType) }} Report Preview</span>
          </h2>
          <p class="text-muted small mb-0">Period: <strong>{{ $periodLabel }}</strong></p>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a href="{{ route('admin.reports.excel', request()->all()) }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Download .XLS
          </a>
          <a href="{{ route('admin.reports.pdf', request()->all()) }}" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-file-earmark-pdf me-1"></i> Download .PDF
          </a>
        </div>
      </div>

      <!-- 1. OVERVIEW & TRANSACTIONS TABLE -->
      @if(in_array($reportType, ['overview', 'transactions']))
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th scope="col" style="width: 60px;">#</th>
                <th scope="col">Date</th>
                <th scope="col">User</th>
                <th scope="col">Type</th>
                <th scope="col">Category</th>
                <th scope="col">Description</th>
                <th scope="col" class="text-end pe-3">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($transactions as $key => $tx)
                <tr>
                  <td class="text-muted small">{{ $key + 1 }}</td>
                  <td>{{ $tx->date->format('M d, Y') }}</td>
                  <td>
                    <div>
                      <p class="fw-semibold mb-0 text-dark">{{ $tx->user_name }}</p>
                      <p class="text-muted small mb-0">{{ $tx->user_email }}</p>
                    </div>
                  </td>
                  <td>
                    <span class="badge {{ $tx->type === 'income' ? 'text-bg-success' : 'text-bg-danger' }}">
                      {{ ucfirst($tx->type) }}
                    </span>
                  </td>
                  <td><span class="fw-medium text-dark">{{ $tx->category }}</span></td>
                  <td class="text-muted small">{{ $tx->description }}</td>
                  <td class="text-end pe-3 fw-bold {{ $tx->type === 'income' ? 'text-success' : 'text-danger' }}">
                    {{ $tx->type === 'income' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">
                    <i class="bi bi-folder-x fs-2 d-block mb-2 text-black-50"></i>
                    No financial records found for the period ({{ $periodLabel }}).
                  </td>
                </tr>
              @endforelse
            </tbody>
            @if($transactions->isNotEmpty())
              <tfoot class="table-light fw-bold">
                <tr>
                  <td colspan="6" class="text-end pe-3">Total Inflow:</td>
                  <td class="text-end pe-3 text-success">+₹{{ number_format($totalIncome, 2) }}</td>
                </tr>
                <tr>
                  <td colspan="6" class="text-end pe-3">Total Outflow:</td>
                  <td class="text-end pe-3 text-danger">-₹{{ number_format($totalExpense, 2) }}</td>
                </tr>
                <tr class="border-top">
                  <td colspan="6" class="text-end pe-3">Net Balance:</td>
                  <td class="text-end pe-3 {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $netBalance >= 0 ? '+' : '' }}₹{{ number_format($netBalance, 2) }}
                  </td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>
      
      <!-- 2. EXPENSES ONLY -->
      @elseif($reportType === 'expenses')
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th scope="col" style="width: 60px;">#</th>
                <th scope="col">Date</th>
                <th scope="col">User</th>
                <th scope="col">Category</th>
                <th scope="col">Description</th>
                <th scope="col" class="text-end pe-3">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($expenses as $key => $exp)
                <tr>
                  <td class="text-muted small">{{ $key + 1 }}</td>
                  <td>{{ $exp->expense_date ? \Carbon\Carbon::parse($exp->expense_date)->format('M d, Y') : $exp->created_at->format('M d, Y') }}</td>
                  <td>{{ $exp->user->name ?? 'User #' . $exp->userid }}</td>
                  <td><span class="fw-medium text-dark">{{ $exp->category->expensecategory ?? 'Expense' }}</span></td>
                  <td class="text-muted small">{{ $exp->Description ?? '—' }}</td>
                  <td class="text-end pe-3 fw-bold text-danger">₹{{ number_format($exp->expenseamount, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-5 text-muted">No expense records found.</td>
                </tr>
              @endforelse
            </tbody>
            @if($expenses->isNotEmpty())
              <tfoot class="table-light fw-bold">
                <tr>
                  <td colspan="5" class="text-end pe-3">Total Expenses:</td>
                  <td class="text-end pe-3 text-danger">₹{{ number_format($totalExpense, 2) }}</td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>

      <!-- 3. INCOMES ONLY -->
      @elseif($reportType === 'incomes')
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th scope="col" style="width: 60px;">#</th>
                <th scope="col">Date</th>
                <th scope="col">User</th>
                <th scope="col">Category</th>
                <th scope="col">Description</th>
                <th scope="col" class="text-end pe-3">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($incomes as $key => $inc)
                <tr>
                  <td class="text-muted small">{{ $key + 1 }}</td>
                  <td>{{ $inc->income_date ? \Carbon\Carbon::parse($inc->income_date)->format('M d, Y') : $inc->created_at->format('M d, Y') }}</td>
                  <td>{{ $inc->user->name ?? 'User #' . $inc->userid }}</td>
                  <td><span class="fw-medium text-dark">{{ $inc->category->incomecategory ?? 'Income' }}</span></td>
                  <td class="text-muted small">{{ $inc->Description ?? '—' }}</td>
                  <td class="text-end pe-3 fw-bold text-success">₹{{ number_format($inc->incomeamount, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-5 text-muted">No income records found.</td>
                </tr>
              @endforelse
            </tbody>
            @if($incomes->isNotEmpty())
              <tfoot class="table-light fw-bold">
                <tr>
                  <td colspan="5" class="text-end pe-3">Total Inflows:</td>
                  <td class="text-end pe-3 text-success">₹{{ number_format($totalIncome, 2) }}</td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>

      <!-- 4. DEBTS & LOANS REPORT -->
      @elseif($reportType === 'debts')
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th scope="col" style="width: 60px;">#</th>
                <th scope="col">User</th>
                <th scope="col">Person / Party</th>
                <th scope="col">Type</th>
                <th scope="col">Purpose</th>
                <th scope="col">From Date</th>
                <th scope="col">Due Date</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-end pe-3">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($debts as $key => $debt)
                <tr>
                  <td class="text-muted small">{{ $key + 1 }}</td>
                  <td>{{ $debt->user->name ?? 'User #' . $debt->userid }}</td>
                  <td class="fw-semibold text-dark">{{ $debt->name }}</td>
                  <td>
                    <span class="badge {{ $debt->debt_type === 'lent' ? 'text-bg-warning' : 'text-bg-info' }}">
                      {{ ucfirst($debt->debt_type) }}
                    </span>
                  </td>
                  <td class="text-muted small">{{ $debt->purpose ?? '—' }}</td>
                  <td>{{ $debt->fromdate ? \Carbon\Carbon::parse($debt->fromdate)->format('M d, Y') : '—' }}</td>
                  <td>{{ $debt->todate ? \Carbon\Carbon::parse($debt->todate)->format('M d, Y') : '—' }}</td>
                  <td>
                    <span class="badge {{ $debt->status === 'repaid' ? 'text-bg-success' : 'text-bg-secondary' }}">
                      {{ ucfirst($debt->status ?? 'pending') }}
                    </span>
                  </td>
                  <td class="text-end pe-3 fw-bold text-dark">₹{{ number_format($debt->debt_amount, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center py-5 text-muted">No debt or loan records found.</td>
                </tr>
              @endforelse
            </tbody>
            @if($debts->isNotEmpty())
              <tfoot class="table-light fw-bold">
                <tr>
                  <td colspan="8" class="text-end pe-3">Total Lent:</td>
                  <td class="text-end pe-3">₹{{ number_format($totalLent, 2) }}</td>
                </tr>
                <tr>
                  <td colspan="8" class="text-end pe-3">Total Borrowed:</td>
                  <td class="text-end pe-3">₹{{ number_format($totalBorrowed, 2) }}</td>
                </tr>
              </tfoot>
            @endif
          </table>
        </div>

      <!-- 5. USERS SUMMARY -->
      @elseif($reportType === 'users')
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th scope="col" style="width: 60px;">#</th>
                <th scope="col">User</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">Joined Date</th>
                <th scope="col" class="text-end pe-3">Recorded Balance</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $key => $u)
                <tr>
                  <td class="text-muted small">{{ $key + 1 }}</td>
                  <td class="fw-semibold text-dark">{{ $u->name }}</td>
                  <td class="text-muted">{{ $u->email }}</td>
                  <td>
                    <span class="badge {{ $u->role === 'admin' ? 'text-bg-danger' : 'text-bg-primary' }}">
                      {{ ucfirst($u->role) }}
                    </span>
                  </td>
                  <td>{{ $u->created_at->format('M d, Y') }}</td>
                  <td class="text-end pe-3 fw-bold text-dark">₹{{ number_format($u->income ?? 0, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-5 text-muted">No users found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      @endif

    </section>

  </div>
</main>

<script>
function toggleCustomDates(range) {
  const customCols = document.querySelectorAll('.custom-date-col');
  if (range === 'custom') {
    customCols.forEach(col => col.classList.remove('d-none'));
  } else {
    customCols.forEach(col => col.classList.add('d-none'));
  }
}
</script>
@endsection
