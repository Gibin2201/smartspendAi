@extends('layouts.adminmaster')

@section('title', 'Edit Expense Category')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y px-4 py-4">
  
  <div class="row justify-content-center mt-4">
    <div class="col-12 col-md-8 col-lg-5">
      
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-semibold"><i class="bx bx-edit-alt me-2 text-primary"></i>Edit Expense Category</h5>
          <!-- Back Button -->
          <a href="{{ route('createExpense') }}" class="btn btn-sm btn-outline-secondary rounded-pill shadow-none">
            <i class="bx bx-arrow-back me-1"></i> Back
          </a>
        </div>
        <div class="card-body p-4">
          
          <form action="{{ route('updateExpense', $expense->expensecategory_id) }}" method="POST">
            @csrf
            <!-- Use the PUT method to securely update data in Laravel -->
            @method('PUT')
            
            <div class="mb-4">
              <label for="expensecategory" class="form-label fw-medium text-dark">Category Name <span class="text-danger">*</span></label>
              
              <div class="input-group input-group-merge shadow-none border-1">
                <span class="input-group-text border-end-0 bg-transparent"><i class="bx bx-receipt text-muted"></i></span>
                <input type="text" 
                       class="form-control border-start-0 ps-0 @error('expensecategory') is-invalid border-danger @enderror" 
                       id="expensecategory" 
                       name="expensecategory" 
                       value="{{ old('expensecategory', $expense->expensecategory) }}"
                       required>
              </div>
              
              @error('expensecategory')
                <div class="text-danger small mt-1 d-block"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
              @enderror
            </div>

            <!-- Added is_lent field selection -->
            <div class="mb-4">
              <label for="is_lent" class="form-label fw-medium text-dark">Category Type Flag (`is_lent`)</label>
              <select name="is_lent" id="is_lent" class="form-select @error('is_lent') is-invalid border-danger @enderror" required>
                <option value="0" {{ old('is_lent', $expense->is_lent) == 0 ? 'selected' : '' }}>0 - Regular Expense Category</option>
                <option value="1" {{ old('is_lent', $expense->is_lent) == 1 ? 'selected' : '' }}>1 - Lent Category</option>
                <option value="2" {{ old('is_lent', $expense->is_lent) == 2 ? 'selected' : '' }}>2 - Debt Repayment Paid Category</option>
              </select>
              <div class="form-text text-muted small mt-1">Use 0 for regular categories, 1 for Lent, and 2 for Repayments to keep them hidden from standard forms.</div>
              
              @error('is_lent')
                <div class="text-danger small mt-1 d-block"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
              @enderror
            </div>
            
            <div class="pt-2">
              <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                <i class="bx bx-save me-1"></i> Save Changes
              </button>
            </div>
          </form>

        </div>
      </div>
      
    </div>
  </div>

</div>
@endsection