@extends('layouts.adminmaster')

@section('content')
<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">
    
    <!-- Page Heading -->
    <div class="page-heading">
      <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
        <div>
          <p class="eyebrow mb-1">Overview</p>
          <h1 class="h3 mb-1">Admin Dashboard</h1>
          <p class="text-muted mb-0">Monitor global platform income, expenses, net balance, and user accounts.</p>
        </div>
      </div>
      {{-- Report Generation Actions (Disabled) --}}
      {{--
      <div class="heading-actions d-flex flex-wrap gap-2">
        <a href="{{ route('admin.reports.excel') }}" class="btn btn-sm btn-outline-success shadow-sm" title="Download Excel financial report for this month">
          <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </a>
        <a href="{{ route('admin.reports.pdf') }}" class="btn btn-sm btn-outline-danger shadow-sm" title="Download PDF financial report for this month">
          <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
        <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-primary shadow-sm" title="Custom Financial Reports & Filters">
          <i class="bi bi-file-earmark-bar-graph me-1"></i> Reports
        </a>
      </div>
      --}}
    </div>

    <!-- Metric Cards -->
    <section class="row g-3 mt-1" aria-label="Dashboard metrics">
      
      <!-- Total Income Card -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-success">
          <div class="metric-top">
            <span class="metric-label">Total Income</span>
            <span class="metric-icon"><i class="bi bi-wallet2" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">₹{{ number_format($totalIncome, 2) }}</div>
          <div class="metric-meta">
            <span class="text-success">All user inflows</span>
          </div>
        </article>
      </div>

      <!-- Total Expenses Card -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-danger">
          <div class="metric-top">
            <span class="metric-label">Total Expenses</span>
            <span class="metric-icon"><i class="bi bi-cash-stack" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">₹{{ number_format($totalExpense, 2) }}</div>
          <div class="metric-meta">
            <span class="text-danger">All user outflows</span>
          </div>
        </article>
      </div>

      <!-- Net Balance Card -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-primary">
          <div class="metric-top">
            <span class="metric-label">Net Balance</span>
            <span class="metric-icon"><i class="bi bi-piggy-bank" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">₹{{ number_format($netBalance, 2) }}</div>
          <div class="metric-meta">
            <span>Overall remaining</span>
          </div>
        </article>
      </div>

      <!-- Customers / Users Card -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-warning">
          <div class="metric-top">
            <span class="metric-label">Customers</span>
            <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $userCount }}</div>
          <div class="metric-meta">
            <span class="{{ $formattedChange >= 0 ? 'text-success' : 'text-danger' }}">
              {{ $formattedChange >= 0 ? '+' . $formattedChange : $formattedChange }}%
            </span>
            <span>active users</span>
          </div>
        </article>
      </div>

    </section>

    <!-- Monthly Expense Tracker Pulse -->
    <section class="row g-3 mt-1" aria-label="Monthly tracker pulse">
      
      <!-- This Month Income -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-success">
          <div class="metric-top">
            <span class="metric-label">This Month Income</span>
            <span class="metric-icon"><i class="bi bi-calendar-check" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">₹{{ number_format($thisMonthIncome, 2) }}</div>
          <div class="metric-meta">
            <span class="text-muted">{{ date('F Y') }} inflow</span>
          </div>
        </article>
      </div>

      <!-- This Month Expenses -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-danger">
          <div class="metric-top">
            <span class="metric-label">This Month Expenses</span>
            <span class="metric-icon"><i class="bi bi-calendar-x" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">₹{{ number_format($thisMonthExpense, 2) }}</div>
          <div class="metric-meta">
            <span class="text-muted">{{ date('F Y') }} outflow</span>
          </div>
        </article>
      </div>

      <!-- This Month Net Balance -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-primary">
          <div class="metric-top">
            <span class="metric-label">This Month Net</span>
            <span class="metric-icon"><i class="bi bi-graph-up" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value {{ $thisMonthNet >= 0 ? 'text-success' : 'text-danger' }}">
            {{ $thisMonthNet >= 0 ? '+' : '' }}₹{{ number_format($thisMonthNet, 2) }}
          </div>
          <div class="metric-meta">
            <span>{{ $thisMonthNet >= 0 ? 'Net surplus' : 'Net deficit' }}</span>
          </div>
        </article>
      </div>

      <!-- Platform Transactions Count -->
      <div class="col-12 col-sm-6 col-xl-3">
        <article class="metric-card metric-warning">
          <div class="metric-top">
            <span class="metric-label">Transactions</span>
            <span class="metric-icon"><i class="bi bi-arrow-left-right" aria-hidden="true"></i></span>
          </div>
          <div class="metric-value">{{ $totalTransactionsCount }}</div>
          <div class="metric-meta">
            <span>Logged financial records</span>
          </div>
        </article>
      </div>

    </section>

    <!-- Charts & Activity -->
<section class="row g-3 mt-1">
    <!-- Financial Overview Chart Area / Dynamic Summary -->
    <div class="col-12 col-xl-6">
        <div class="panel h-100">
            <div class="panel-header pb-2">
                <div>
                    <h2 class="h6 mb-1 section-title"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i><span>Platform Financial Overview</span></h2>
                    <p class="text-muted small mb-0">Live summary of total inflows and outflows.</p>
                </div>
            </div>
            <div class="py-2">
                <div class="row text-center py-2">
                    <div class="col-6 border-end">
                        <span class="text-muted small d-block mb-1">Total Inflows</span>
                        <h4 class="text-success fw-bold mb-0">₹{{ number_format($totalIncome, 2) }}</h4>
                    </div>
                    <div class="col-6">
                        <span class="text-muted small d-block mb-1">Total Outflows</span>
                        <h4 class="text-danger fw-bold mb-0">₹{{ number_format($totalExpense, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic System Activity Feed -->
    <div class="col-12 col-xl-6">
        <div class="panel h-100">
            <div class="panel-header pb-2">
                <div>
                    <h2 class="h6 mb-1 section-title"><i class="bi bi-activity" aria-hidden="true"></i><span>System Activity</span></h2>
                    <p class="text-muted small mb-0">Latest real-time user actions.</p>
                </div>
            </div>

            <div class="activity-list px-3 pb-2">
                @forelse($recentIncomes as $income)
                    <div class="activity-item d-flex align-items-start mb-2 pb-2 border-bottom">
                        <span class="activity-dot bg-success mt-1 me-2"></span>
                        <div>
                            <p class="mb-0 fw-semibold small">New Income Added</p>
                            <p class="text-muted text-xs mb-0" style="font-size: 0.8rem;">
                                User logged ₹{{ number_format($income->incomeamount, 2) }} on {{ \Carbon\Carbon::parse($income->income_date)->format('M d') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-muted small">No recent income activities.</div>
                @endforelse

                @forelse($recentExpenses as $expense)
                    <div class="activity-item d-flex align-items-start mb-2 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <span class="activity-dot bg-danger mt-1 me-2"></span>
                        <div>
                            <p class="mb-0 fw-semibold small">New Expense Logged</p>
                            <p class="text-muted text-xs mb-0" style="font-size: 0.8rem;">
                                Expense of ₹{{ number_format($expense->expenseamount, 2) }} recorded.
                            </p>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</section>

    <!-- Category Breakdown Analytics (Charts) -->
    <section class="row g-3 mt-1" aria-label="Category breakdown charts">
        <!-- Expense Category Breakdown Chart -->
        <div class="col-12 col-xl-6">
            <div class="panel h-100 d-flex flex-column">
                <div class="panel-header pb-2 d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h2 class="h6 mb-1 section-title">
                            <i class="bi bi-pie-chart text-danger" aria-hidden="true"></i>
                            <span>Expense Category Breakdown</span>
                        </h2>
                        <p class="text-muted small mb-0">Platform expenditure distribution across categories.</p>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-semibold">
                        ₹{{ number_format($totalExpense, 2) }}
                    </span>
                </div>
                
                <div class="flex-grow-1 d-flex flex-column justify-content-center py-2">
                    @if(count($expenseChartData) > 0 && $totalExpense > 0)
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="expenseCategoryBreakdownChart"></canvas>
                        </div>
                        <div class="mt-3 pt-2 border-top">
                            <div class="row g-2">
                                @foreach($expenseCategoriesBreakdown->take(4) as $cat)
                                    @php
                                        $percent = $totalExpense > 0 ? round(($cat->total / $totalExpense) * 100, 1) : 0;
                                    @endphp
                                    <div class="col-6">
                                        <div class="p-2 rounded bg-light bg-opacity-50 border">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="small fw-semibold text-truncate text-dark" style="max-width: 120px;" title="{{ $cat->category->expensecategory ?? 'Uncategorized' }}">
                                                    {{ $cat->category->expensecategory ?? 'Uncategorized' }}
                                                </span>
                                                <span class="badge text-bg-danger rounded-pill">{{ $percent }}%</span>
                                            </div>
                                            <span class="small text-danger fw-bold d-block mt-1">₹{{ number_format($cat->total, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-pie-chart fs-1 d-block mb-2 text-black-50"></i>
                            <p class="mb-0 small">No expense category data recorded yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Income Category Breakdown Chart -->
        <div class="col-12 col-xl-6">
            <div class="panel h-100 d-flex flex-column">
                <div class="panel-header pb-2 d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h2 class="h6 mb-1 section-title">
                            <i class="bi bi-pie-chart-fill text-success" aria-hidden="true"></i>
                            <span>Income Category Breakdown</span>
                        </h2>
                        <p class="text-muted small mb-0">Platform inflow distribution across income categories.</p>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold">
                        ₹{{ number_format($totalIncome, 2) }}
                    </span>
                </div>
                
                <div class="flex-grow-1 d-flex flex-column justify-content-center py-2">
                    @if(count($incomeChartData) > 0 && $totalIncome > 0)
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="incomeCategoryBreakdownChart"></canvas>
                        </div>
                        <div class="mt-3 pt-2 border-top">
                            <div class="row g-2">
                                @foreach($incomeCategoriesBreakdown->take(4) as $cat)
                                    @php
                                        $percent = $totalIncome > 0 ? round(($cat->total / $totalIncome) * 100, 1) : 0;
                                    @endphp
                                    <div class="col-6">
                                        <div class="p-2 rounded bg-light bg-opacity-50 border">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="small fw-semibold text-truncate text-dark" style="max-width: 120px;" title="{{ $cat->category->incomecategory ?? 'Uncategorized' }}">
                                                    {{ $cat->category->incomecategory ?? 'Uncategorized' }}
                                                </span>
                                                <span class="badge text-bg-success rounded-pill">{{ $percent }}%</span>
                                            </div>
                                            <span class="small text-success fw-bold d-block mt-1">₹{{ number_format($cat->total, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-wallet2 fs-1 d-block mb-2 text-black-50"></i>
                            <p class="mb-0 small">No income category data recorded yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Expense Categories & Debts Analytics -->
    <section class="row g-3 mt-1">
        <!-- Top Expense Categories Breakdown -->
        <div class="col-12 col-xl-6">
            <div class="panel h-100">
                <div class="panel-header pb-2">
                    <div>
                        <h2 class="h6 mb-1 section-title"><i class="bi bi-pie-chart" aria-hidden="true"></i><span>Top Expense Categories</span></h2>
                        <p class="text-muted small mb-0">Highest spending categories across all users.</p>
                    </div>
                </div>
                <div class="py-2">
                    @forelse($topExpenseCategories as $item)
                        @php
                            $catName = $item->category->expensecategory ?? 'Uncategorized';
                            $percent = $totalExpense > 0 ? round(($item->total / $totalExpense) * 100, 1) : 0;
                        @endphp
                        <div class="mb-3 {{ !$loop->last ? 'pb-2 border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-semibold small text-dark">{{ $catName }}</span>
                                <div class="text-end">
                                    <span class="fw-bold small text-danger">₹{{ number_format($item->total, 2) }}</span>
                                    <span class="text-muted small ms-1">({{ $percent }}%)</span>
                                </div>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" 
                                     role="progressbar" 
                                     style="width: {{ $percent }}%;" 
                                     aria-valuenow="{{ $percent }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-tag fs-2 d-block mb-1 text-black-50"></i>
                            No expense categories recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Debts & Categories Overview -->
        <div class="col-12 col-xl-6">
            <div class="panel h-100">
                <div class="panel-header pb-2">
                    <div>
                        <h2 class="h6 mb-1 section-title"><i class="bi bi-shield-check" aria-hidden="true"></i><span>Debts & Categories Overview</span></h2>
                        <p class="text-muted small mb-0">Platform lending, borrowing, and active category counters.</p>
                    </div>
                </div>
                <div class="py-2">
                    <div class="row g-2 text-center mb-3">
                        <div class="col-6">
                            <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                                <span class="text-muted small d-block mb-1"><i class="bi bi-box-arrow-up-right text-danger me-1"></i>Total Lent</span>
                                <h5 class="fw-bold text-dark mb-0">₹{{ number_format($totalLent, 2) }}</h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                                <span class="text-muted small d-block mb-1"><i class="bi bi-box-arrow-in-down-left text-success me-1"></i>Total Borrowed</span>
                                <h5 class="fw-bold text-dark mb-0">₹{{ number_format($totalBorrowed, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 text-center">
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Pending Debts</span>
                            <span class="badge text-bg-warning rounded-pill mt-1 px-3 py-1.5">{{ $pendingDebtsCount }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Repaid Debts</span>
                            <span class="badge text-bg-success rounded-pill mt-1 px-3 py-1.5">{{ $repaidDebtsCount }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Expense Cats</span>
                            <span class="badge text-bg-danger rounded-pill mt-1 px-3 py-1.5">{{ $expenseCategoriesCount }}</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Income Cats</span>
                            <span class="badge text-bg-primary rounded-pill mt-1 px-3 py-1.5">{{ $incomeCategoriesCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Platform Transactions -->
    <section class="panel mt-3">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-receipt" aria-hidden="true"></i><span>Recent Transactions</span></h2>
          <p class="text-muted mb-0">Real-time financial transactions logged across all users.</p>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th scope="col">User</th>
              <th scope="col">Type</th>
              <th scope="col">Category</th>
              <th scope="col">Description</th>
              <th scope="col">Date</th>
              <th scope="col" class="text-end pe-3">Amount</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentTransactions as $tx)
              <tr>
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
                <td>
                  <span class="fw-medium text-dark">{{ $tx->category }}</span>
                </td>
                <td class="text-muted small">
                  {{ !empty($tx->description) ? \Illuminate\Support\Str::limit($tx->description, 35) : '—' }}
                </td>
                <td class="text-muted">
                  {{ $tx->date->format('M d, Y') }}
                </td>
                <td class="text-end pe-3">
                  <span class="fw-bold {{ $tx->type === 'income' ? 'text-success' : 'text-danger' }}">
                    {{ $tx->type === 'income' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                  </span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">No recent transactions recorded.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

    <!-- Users Table -->
    <section class="panel mt-3">
      <div class="panel-header">
        <div>
          <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Recent Users</span></h2>
          <p class="text-muted mb-0">Latest account activity across the workspace.</p>
        </div>
      </div>
      
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th scope="col">User</th>
              <th scope="col">Role</th>
              <th scope="col">Status</th>
              <th scope="col">Joined</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentUsers as $user)
              <tr>
                <td>
                  <div>
                    <p class="fw-semibold mb-0">{{ $user->name }}</p>
                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                  </div>
                </td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                  <span class="badge text-bg-success">Active</span>
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">No recent users found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>

  </div>
</main>

<script>
    // Replaces the current history entry so the 'back' button 
    // skips the login/guest page entirely
    if (window.history && window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const sliceBorder = isDark ? '#1e293b' : '#ffffff';
    const legendText = isDark ? '#cbd5e1' : '#475569';

    const formatINR = function(val) {
        return '₹' + Number(val).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    // 1. Expense Category Breakdown Chart
    const expenseCanvas = document.getElementById('expenseCategoryBreakdownChart');
    if (expenseCanvas) {
        const expLabels = {!! json_encode($expenseChartLabels) !!};
        const expData = {!! json_encode($expenseChartData) !!};

        if (expData && expData.length > 0) {
            new Chart(expenseCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: expLabels,
                    datasets: [{
                        data: expData,
                        backgroundColor: [
                            '#ef4444', '#f97316', '#f59e0b', '#ec4899', 
                            '#8b5cf6', '#6366f1', '#14b8a6', '#0ea5e9', '#64748b'
                        ],
                        borderWidth: 2,
                        borderColor: sliceBorder,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 12,
                                color: legendText,
                                font: { size: 12, family: "'Segoe UI', sans-serif" }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + Number(b), 0);
                                    const val = Number(context.parsed);
                                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                    return ' ' + context.label + ': ' + formatINR(val) + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // 2. Income Category Breakdown Chart
    const incomeCanvas = document.getElementById('incomeCategoryBreakdownChart');
    if (incomeCanvas) {
        const incLabels = {!! json_encode($incomeChartLabels) !!};
        const incData = {!! json_encode($incomeChartData) !!};

        if (incData && incData.length > 0) {
            new Chart(incomeCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: incLabels,
                    datasets: [{
                        data: incData,
                        backgroundColor: [
                            '#10b981', '#06b6d4', '#3b82f6', '#8b5cf6', 
                            '#14b8a6', '#84cc16', '#eab308', '#0284c7', '#64748b'
                        ],
                        borderWidth: 2,
                        borderColor: sliceBorder,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 12,
                                color: legendText,
                                font: { size: 12, family: "'Segoe UI', sans-serif" }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + Number(b), 0);
                                    const val = Number(context.parsed);
                                    const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                    return ' ' + context.label + ': ' + formatINR(val) + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endpush
@endsection