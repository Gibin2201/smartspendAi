@extends('layouts.usermaster')

@section('title', 'Income Records')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-10">
    
    <!-- Page Header & Action Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">Income Management</h4>
        <p class="text-muted mb-0">View, filter, and export your recorded income inflows.</p>
      </div>
      <a href="{{ route('addincome') }}" class="btn btn-success shadow-sm fw-semibold">
        <i class="bx bx-plus fs-5 me-1 align-middle"></i> Add New Income
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
        <form action="{{ route('income.index') }}" method="GET" class="row g-3 align-items-end">
          
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
            <button type="submit" class="btn btn-success w-50 fw-semibold text-white">
              <i class="bx bx-filter-alt me-1"></i> Filter
            </button>
            <button type="submit" formaction="{{ route('income.export') }}" class="btn btn-outline-secondary w-50 fw-semibold">
              <i class="bx bx-download me-1"></i> Export
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- Total Income Summary Card (Changed to ₹) -->
    <div class="card bg-success text-white shadow-sm border-0 rounded-4 p-4 mb-4">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="text-white-50 mb-1">Total Income for Selected Period</h6>
          <h2 class="mb-0 fw-bold">₹{{ number_format($totalFilteredIncome, 2) }}</h2>
        </div>
        <div class="fs-1 text-white-50">
          <i class="bx bx-wallet"></i>
        </div>
      </div>
    </div>

    <!-- Incomes Table Card -->
    <div class="card shadow border-0 rounded-4">
      <div class="card-header bg-white py-3 px-4 border-bottom rounded-top-4">
        <h5 class="card-title m-0 text-dark fw-bold">Income List</h5>
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
              @forelse($incomes as $income)
                <tr>
                  <td class="px-4 text-muted">{{ $income->income_date }}</td>
                  <td>
  <span class="text-secondary fw-semibold">
    {{ $income->category->incomecategory ?? 'N/A' }}
  </span>
</td>
                  <td class="text-secondary">{{ $income->Description ?? '-' }}</td>
                  <!-- Changed to Indian format ₹ -->
                  <td class="text-end px-4 fw-bold text-success">+₹{{ number_format($income->incomeamount, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center py-5 text-muted">
                    <i class="bx bx-folder-open fs-1 mb-2 d-block text-black-50"></i>
                    No income records found for this month and year.
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