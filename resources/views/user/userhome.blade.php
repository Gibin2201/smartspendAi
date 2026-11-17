@extends('layouts.usermaster')

@section('title', 'My Financial Dashboard')

@section('content')

<!-- SUCCESS POPUP / FLASH MESSAGE ALERT -->
@if(session('success'))
<div class="row mb-4">
  <div class="col-12">
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="background-color: #d4edda; color: #155724; border-left: 4px solid #28a745 !important;">
      <i class="bx bx-check-circle fs-4 me-2 align-middle"></i>
      <strong class="fw-bold">Success!</strong> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
</div>
@endif

<!-- Welcome & Quick Actions -->
<div class="row mb-4">
  <div class="col-12">
    <div class="card bg-primary text-white">
      <div class="card-body d-flex flex-sm-row flex-column justify-content-between align-items-center">
        <div>
          <h4 class="text-white mb-2">Welcome back, {{ Auth::user()->name ?? 'Tracker' }}! 🚀</h4>
          <p class="mb-0 text-white-50">Here is what is happening with your money this month. Stay on top of your budgets!</p>
        </div>
        <div class="mt-3 mt-sm-0 d-flex gap-2">
          <a href="{{ route('addincome') }}" class="btn btn-dark text-success fw-bold bg-white border-0 shadow-sm">
            <i class="bx bx-trending-up me-1"></i> Add Income
          </a>
          <a href="{{ route('addexpense') }}" class="btn btn-dark text-danger fw-bold bg-white border-0 shadow-sm">
            <i class="bx bx-trending-down me-1"></i> Add Expense
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- The Big Three: Snapshot Metrics -->
<div class="row mb-4">
  <!-- Total Balance -->
  <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
    <div class="card shadow-sm h-100 border-0 border-start border-primary border-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted fw-semibold">Current Balance</span>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-wallet fs-4"></i></span>
          </div>
        </div>
        <h2 class="mb-1 text-primary">₹{{ number_format($user->income, 2) }}</h2>
        
        <small class="{{ $statusClass ?? 'text-muted' }} fw-semibold">
            <i class="bx {{ $statusIcon ?? '' }}"></i> {{ $statusMessage ?? '' }}
        </small>
      </div>
    </div>
  </div>

  <!-- Total Income -->
  <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
    <div class="card shadow-sm h-100 border-0 border-start border-success border-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted fw-semibold">Monthly Income</span>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-down-arrow-circle fs-4"></i></span>
          </div>
        </div>
        <h2 class="mb-1 text-success">₹{{ number_format($monthlyIncome, 2) }}</h2>
        <small class="text-muted">Recorded this month</small>
      </div>
    </div>
  </div>

  <!-- Total Expenses -->
  <div class="col-lg-4 col-md-6">
    <div class="card shadow-sm h-100 border-0 border-start border-danger border-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted fw-semibold">Monthly Expenses</span>
          <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-up-arrow-circle fs-4"></i></span>
          </div>
        </div>
        <h2 class="mb-1 text-danger">₹{{ number_format($monthlyExpense, 2) }}</h2>
        <small class="text-muted">Recorded this month</small>
      </div>
    </div>
  </div>
</div>

<!-- Row 2: Monthly Expense Comparison (Left) & Income vs Expense Overview Chart (Right) -->
<div class="row mb-4">
  <!-- Monthly Expense Comparison Card -->
  <div class="col-12 col-lg-4 mb-4 mb-lg-0">
    <div class="card h-100 shadow-sm border-0 d-flex flex-column justify-content-between">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="avatar avatar-sm bg-label-primary rounded p-2">
            <i class="bx bx-bar-chart-alt-2 fs-4"></i>
          </div>
          <span class="badge {{ $isIncrease ? 'bg-label-danger' : 'bg-label-success' }} fw-semibold">
            <i class="bx {{ $isIncrease ? 'bx-up-arrow-alt' : 'bx-down-arrow-alt' }} me-1"></i>
            {{ $formattedPercentage }}% vs Last Month
          </span>
        </div>
        <h5 class="card-title fw-bold text-dark mb-1">Monthly Expense Comparison</h5>
        <p class="text-muted small mb-3">Compared to ₹{{ number_format($prevMonthExpense, 2) }} spent last month.</p>
        
        <div class="d-flex justify-content-between align-items-center bg-light p-2.5 rounded mb-3">
          <div>
            <span class="text-muted small d-block">Current Month</span>
            <h6 class="fw-bold mb-0 text-dark">₹{{ number_format($monthlyExpense, 2) }}</h6>
          </div>
          <div class="text-end">
            <span class="text-muted small d-block">Difference</span>
            <h6 class="fw-bold mb-0 {{ $isIncrease ? 'text-danger' : 'text-success' }}">
              {{ $isIncrease ? '+' : '-' }}₹{{ number_format(abs($monthlyExpense - $prevMonthExpense), 2) }}
            </h6>
          </div>
        </div>

        <div class="alert alert-secondary bg-label-secondary border-0 p-2 mb-0 text-center small">
          <i class="bx bx-info-circle me-1"></i> 
          @if($isIncrease)
            You have spent more than last month.
          @else
            Great job! You saved more compared to last month.
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Income vs Expense Overview Chart -->
  <div class="col-12 col-lg-8">
    <div class="card h-100 shadow-sm border-0">
      <div class="card-header d-flex align-items-center justify-content-between pb-0 pt-3 px-3">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2 fw-bold text-dark">Income vs. Expense Overview</h5>
          <small class="text-muted">Current Month Tracking</small>
        </div>
      </div>
      <div class="card-body px-2 mt-2">
        <div id="expenseOverviewChart" style="min-height: 225px;"></div>
      </div>
    </div>
  </div>
</div>

<!-- Row 3: Analytics & Budget Tracking -->
<div class="row mb-4">
  <div class="col-12 col-lg-6 mb-4 mb-lg-0">
    <div class="card shadow-sm h-100 border-0">
      <div class="card-header d-flex align-items-center justify-content-between pb-2 pt-3 px-3">
        <div class="d-flex align-items-center">
          <span class="avatar-initial rounded bg-label-warning p-2 me-2"><i class="bx bx-pie-chart fs-5"></i></span>
          <h5 class="card-title m-0 fw-bold text-dark">Expense Category Breakdown</h5>
        </div>
      </div>
      <div class="card-body px-3 pb-3 pt-0 d-flex align-items-center justify-content-center">
        <div style="position: relative; height: 240px; width: 100%;">
          <canvas id="expenseCategoryChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-2 pt-3 px-3">
        <div class="d-flex align-items-center">
          <span class="avatar-initial rounded bg-label-primary p-2 me-2"><i class="bx bx-pie-chart-alt-2 fs-5"></i></span>
          <h5 class="card-title m-0 fw-bold text-dark">Monthly Budget</h5>
        </div>
        <a href="{{ url('user/budgets/create') }}" class="btn btn-sm btn-outline-primary px-2 py-1"><i class="bx bx-edit-alt me-1"></i>Set/Edit</a>
      </div>
      
      <div class="card-body px-3 pb-3 pt-0">
        @if($budgetLimit > 0)
          <div class="row align-items-center mb-3">
            <div class="col-7">
              <span class="text-muted small d-block mb-1">Total Spent</span>
              <h3 class="fw-bold mb-0 text-dark">₹{{ number_format($monthlyExpense, 2) }}</h3>
            </div>
            <div class="col-5 text-end border-start">
              <span class="text-muted small d-block mb-1">Budget Limit</span>
              <h5 class="fw-semibold text-secondary mb-0">₹{{ number_format($budgetLimit, 2) }}</h5>
            </div>
          </div>
          
          <div class="progress mb-2 rounded-pill" style="height: 8px; background-color: #f1f1f2;">
            <div class="progress-bar {{ $progressBarColor }} rounded-pill" 
                 role="progressbar" 
                 style="width: {{ $budgetPercentage }}%" 
                 aria-valuenow="{{ $budgetPercentage }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
            </div>
          </div>
          
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge {{ $budgetPercentage >= 90 ? 'bg-danger' : ($budgetPercentage >= 75 ? 'bg-warning' : 'bg-primary') }} text-white px-2 py-1 fw-semibold">
              {{ $budgetPercentage }}% USED
            </span>
            <small class="text-muted fst-italic">{{ $budgetWarning }}</small>
          </div>

          <div class="p-2.5 rounded bg-light border-0 d-flex align-items-center justify-content-between mb-0">
            <div class="d-flex align-items-center">
              <div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded bg-white text-dark shadow-sm"><i class="bx bx-wallet"></i></span>
              </div>
              <span class="small text-muted fw-semibold">Remaining Balance</span>
            </div>
            <span class="fw-bold {{ ($budgetLimit - $monthlyExpense) < 0 ? 'text-danger' : 'text-success' }}">
              ₹{{ number_format($budgetLimit - $monthlyExpense, 2) }}
            </span>
          </div>
        @else
          <div class="text-center py-3">
            <div class="avatar avatar-lg bg-label-warning mx-auto mb-2">
              <i class="bx bx-error fs-2"></i>
            </div>
            <h6 class="fw-semibold text-dark mb-1">No Budget Set</h6>
            <p class="text-muted small mb-2">You haven't configured a spending target for this month.</p>
            <a href="{{ url('user/budgets/create') }}" class="btn btn-sm btn-primary">
              <i class="bx bx-plus me-1"></i> Set Budget Now
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Recent Transactions -->
<div class="row">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Recent Transactions</h5>
        
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Date</th>
              <th>Description</th>
              <th>Category</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @forelse($recentTransactions as $tx)
              <tr>
                <td><small class="text-muted">{{ \Carbon\Carbon::parse($tx['date'])->format('M d, Y') }}</small></td>
                <td><strong>{{ $tx['description'] }}</strong></td>
                <td>
                  @if($tx['type'] == 'income')
                    <span class="badge bg-label-success"><i class="bx bx-briefcase me-1"></i> {{ $tx['category'] }}</span>
                  @else
                    <span class="badge bg-label-warning"><i class="bx bx-tag me-1"></i> {{ $tx['category'] }}</span>
                  @endif
                </td>
                <td class="{{ $tx['type'] == 'income' ? 'text-success' : 'text-danger' }} fw-bold">
                  {{ $tx['type'] == 'income' ? '+' : '-' }}₹{{ number_format($tx['amount'], 2) }}
                </td>
                <td><span class="badge bg-label-success">Completed</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">No recent transactions found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Load Chart.js and ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // 1. Render Category Doughnut Chart
    const categoryCtx = document.getElementById('expenseCategoryChart');
    if (categoryCtx) {
      new Chart(categoryCtx.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: {!! json_encode($categories) !!},
          datasets: [{
            data: {!! json_encode($totals) !!},
            backgroundColor: [
              '#696cff', '#28a745', '#ffab00', '#ff3e1d', '#03c3ec', '#71dd37'
            ],
            borderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: { boxWidth: 12 }
            }
          }
        }
      });
    }

    // 2. Render Apex Bar Chart
    const options = {
      series: [{
        name: 'Income',
        data: [{{ floatval($monthlyIncome ?? 0) }}]
      }, {
        name: 'Expense',
        data: [{{ floatval($monthlyExpense ?? 0) }}]
      }],
      chart: {
        type: 'bar',
        height: 220,
        toolbar: { show: false }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '40%',
          borderRadius: 4
        },
      },
      dataLabels: { enabled: false },
      stroke: { show: true, width: 2, colors: ['transparent'] },
      colors: ['#28a745', '#dc3545'],
      xaxis: {
        categories: ['This Month'],
      },
      yaxis: {
        title: { text: '₹ (INR)' }
      },
      fill: { opacity: 1 },
      tooltip: {
        y: {
          formatter: function (val) {
            return "₹ " + val.toLocaleString();
          }
        }
      }
    };

    const chartElement = document.querySelector("#expenseOverviewChart");
    if (chartElement) {
      const chart = new ApexCharts(chartElement, options);
      chart.render();
    }
  });
</script>
@endpush