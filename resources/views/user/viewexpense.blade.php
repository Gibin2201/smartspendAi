@extends('layouts.usermaster')

@section('title', 'Expense Records')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-10">
    
    <!-- Page Header & Action Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">Expense Management</h4>
        <p class="text-muted mb-0">View, filter, and export your recorded cash outflows.</p>
      </div>
      <a href="{{ route('addexpense') }}" class="btn btn-danger shadow-sm fw-semibold">
        <i class="bx bx-plus fs-5 me-1 align-middle"></i> Add New Expense
      </a>
    </div>

    <!-- Success Message Alert -->
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Filter & Export Form Card -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
      <div class="card-body p-3">
        <form action="{{ route('expense.index') }}" method="GET" class="row g-3 align-items-end">
          
          <!-- Month Dropdown -->
          <div class="col-md-4">
            <label for="month" class="form-label fw-semibold text-secondary">Filter by Month</label>
            <select name="month" id="month" class="form-select">
              @for ($m = 1; $m <= 12; $m++)
                <option value="{{ sprintf('%02d', $m) }}" {{ $selectedMonth == sprintf('%02d', $m) ? 'selected' : '' }}>
                  {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                </option>
              @endfor
            </select>
          </div>

          <!-- Year Dropdown -->
          <div class="col-md-4">
            <label for="year" class="form-label fw-semibold text-secondary">Filter by Year</label>
            <select name="year" id="year" class="form-select">
              @php $currentYear = date('Y'); @endphp
              @for ($y = $currentYear; $y >= $currentYear - 5; $y--)
                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                  {{ $y }}
                </option>
              @endfor
            </select>
          </div>

          <!-- Buttons: Filter & Download Report -->
          <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-danger w-50 fw-semibold text-white">
              <i class="bx bx-filter-alt me-1"></i> Filter
            </button>
            <button type="submit" formaction="{{ route('expense.export') }}" class="btn btn-outline-secondary w-50 fw-semibold">
              <i class="bx bx-download me-1"></i> Export
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- Total Expense Summary Card -->
    <div class="card bg-danger text-white shadow-sm border-0 rounded-4 p-4 mb-4">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-white-50 mb-1">Total Expenses for Selected Period</h6>
          <h2 class="mb-0 fw-bold">₹{{ number_format($totalFilteredExpense, 2) }}</h2>
        </div>
        <div class="fs-1 text-white-50">
          <i class="bx bx-wallet"></i>
        </div>
      </div>
    </div>

    <!-- Expenses Table Card -->
    <div class="card shadow border-0 rounded-4">
      <div class="card-header bg-white py-3 px-4 border-bottom rounded-top-4">
        <h5 class="card-title m-0 text-dark fw-bold">Expense List</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-secondary">
              <tr>
                <th class="py-3 px-4">Date</th>
                <th class="py-3">Category</th>
                <th class="py-3">Description</th>
                <th class="py-3 text-end px-4">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($expenses as $expense)
                <tr>
                  <td class="px-4 text-muted">{{ $expense->expense_date }}</td>
                  <td>
                    <span class="text-secondary fw-semibold">
                      {{ $expense->category->expensecategory ?? 'N/A' }}
                    </span>
                  </td>
                  <td class="text-secondary">{{ $expense->Description ?? '-' }}</td>
                  <td class="text-end px-4 fw-bold text-danger">-₹{{ number_format($expense->expenseamount, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center py-5 text-muted">
                    <i class="bx bx-folder-open fs-1 mb-2 d-block text-black-50"></i>
                    No expense records found for this month and year.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection