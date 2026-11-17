@extends('layouts.usermaster')

@section('title', 'Add Income')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    
    <!-- Page Header / Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">Add New Income</h4>
        <p class="text-muted mb-0">Record a new cash inflow to keep your balance updated.</p>
      </div>
    </div>

    <!-- Display Validation Errors if any -->
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Main Card Form -->
    <div class="card shadow border-0 rounded-4">
      <div class="card-header bg-white py-3 px-4 border-bottom rounded-top-4">
        <h5 class="card-title m-0 text-success fw-bold d-flex align-items-center">
          <i class="bx bx-trending-up fs-4 me-2"></i>Income Details Form
        </h5>
      </div>
      
      <div class="card-body p-4 p-md-5">
        
        <!-- Replace '#' with your actual store route or action URL -->
        <form action="{{ route('incomes.store') }}" method="POST">
          @csrf

          <!-- Amount Field -->
          <div class="mb-3">
            <label for="incomeamount" class="form-label fw-semibold text-secondary">
              <i class="bx bx-dollar-circle me-1 text-success"></i> Amount
            </label>
            <div class="input-group input-group-merge">
              <span class="input-group-text bg-light text-muted fw-bold">$</span>
              <input type="number" step="0.01" class="form-control form-control-lg fs-6" id="incomeamount" name="incomeamount" value="{{ old('incomeamount') }}" placeholder="0.00" required>
            </div>
          </div>

          <!-- Income Category Selection (Fixed name to include underscore) -->
          <div class="mb-3">
            <label for="incomecategory_id" class="form-label fw-semibold text-secondary">
              <i class="bx bx-category me-1 text-success"></i> Income Category
            </label>
            <select class="form-select form-select-lg fs-6" id="incomecategory_id" name="incomecategory_id" required>
              <option value="">Select Income Category</option>
              @foreach($categories as $category)
                  <option value="{{ $category->incomecategory_id }}" {{ old('incomecategory_id') == $category->incomecategory_id ? 'selected' : '' }}>
                      {{ $category->incomecategory }}
                  </option>
              @endforeach
            </select>
          </div>

          <!-- Date Field -->
          <div class="mb-3">
            <label for="income_date" class="form-label fw-semibold text-secondary">
              <i class="bx bx-calendar me-1 text-success"></i> Date
            </label>
            <input type="date" class="form-control form-control-lg fs-6" id="income_date" name="income_date" value="{{ old('income_date', date('Y-m-d')) }}" required>
          </div>

          <!-- Description Field -->
          <div class="mb-4">
            <label for="Description" class="form-label fw-semibold text-secondary">
              <i class="bx bx-file me-1 text-success"></i> Description 
            </label>
            <textarea class="form-control" id="Description" name="Description" rows="3" placeholder="e.g., Monthly salary, freelance design project...">{{ old('Description') }}</textarea>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex justify-content-end gap-2 pt-2 border-top">
            <a href="{{ url('user/dashboard') }}" class="btn btn-light px-4 border">Cancel</a>
            <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm">
              <i class="bx bx-check fs-5 me-1 align-middle"></i> Save Income
            </button>
          </div>

        </form>

      </div>
    </div>

  </div>
</div>
@endsection