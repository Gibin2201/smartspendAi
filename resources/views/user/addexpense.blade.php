@extends('layouts.usermaster')

@section('title', 'Add Expense')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    
    <!-- SUCCESS POPUP / FLASH MESSAGE ALERT -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="background-color: #d4edda; color: #155724; border-left: 4px solid #28a745 !important;">
        <i class="bx bx-check-circle fs-4 me-2 align-middle"></i>
        <strong class="fw-bold">Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Page Header / Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">Add New Expense</h4>
        <p class="text-muted mb-0">Record a new cash outflow to keep your budget on track.</p>
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
        <h5 class="card-title m-0 text-danger fw-bold d-flex align-items-center">
          <i class="bx bx-trending-down fs-4 me-2"></i>Expense Details Form
        </h5>
      </div>
      
      <div class="card-body p-4 p-md-5">
        
        <form action="{{ route('expenses.store') }}" method="POST">
          @csrf

          <!-- Amount Field -->
          <div class="mb-3">
            <label for="expenseamount" class="form-label fw-semibold text-secondary">
              <i class="bx bx-rupee me-1 text-danger"></i> Amount
            </label>
            <div class="input-group input-group-merge">
              <span class="input-group-text bg-light text-muted fw-bold">₹</span>
              <input type="number" step="0.01" class="form-control form-control-lg fs-6" id="expenseamount" name="expenseamount" value="{{ old('expenseamount') }}" placeholder="0.00" required>
            </div>
          </div>

          <!-- Expense Category Selection -->
          <div class="mb-3">
            <label for="expensecategory_id" class="form-label fw-semibold text-secondary">
              <i class="bx bx-category me-1 text-danger"></i> Expense Category
            </label>
            <select class="form-select form-select-lg fs-6" id="expensecategory_id" name="expensecategory_id" required>
              <option value="">Select Expense Category</option>
              @foreach($categories as $category)
                  <option value="{{ $category->expensecategory_id }}" {{ old('expensecategory_id') == $category->expensecategory_id ? 'selected' : '' }}>
                      {{ $category->expensecategory }}
                  </option>
              @endforeach
            </select>
          </div>

          <!-- Date Field -->
          <div class="mb-3">
            <label for="expense_date" class="form-label fw-semibold text-secondary">
              <i class="bx bx-calendar me-1 text-danger"></i> Date
            </label>
            <input type="date" class="form-control form-control-lg fs-6" id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required>
          </div>

          <!-- Description Field -->
          <div class="mb-4">
            <label for="Description" class="form-label fw-semibold text-secondary">
              <i class="bx bx-file me-1 text-danger"></i> Description (Optional)
            </label>
            <textarea class="form-control" id="Description" name="Description" rows="3" placeholder="e.g., Grocery shopping, utility bill, fuel...">{{ old('Description') }}</textarea>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex justify-content-end gap-2 pt-2 border-top">
            <a href="{{ route('userhome') }}" class="btn btn-light px-4 border">Cancel</a>
            <button type="submit" class="btn btn-danger px-4 fw-semibold shadow-sm">
              <i class="bx bx-check fs-5 me-1 align-middle"></i> Save Expense
            </button>
          </div>

        </form>

      </div>
    </div>

  </div>
</div>
@endsection