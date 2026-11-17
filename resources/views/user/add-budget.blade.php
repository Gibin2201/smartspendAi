@extends('layouts.usermaster')

@section('title', 'Set Monthly Budget')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="card-title m-0 text-white"><i class="bx bx-wallet me-2"></i> Set Monthly Budget</h5>
    <a href="{{ route('userhome') }}" class="btn btn-sm btn-light">Back to Dashboard</a>      </div>
      
      <div class="card-body mt-3">
        <!-- Success or Error Alerts -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <!-- Budget Form -->
        <form action="{{ url('user/budgets/store') }}" method="POST">
          @csrf

          <!-- Budget Amount -->
          <div class="mb-3">
            <label for="budget_amount" class="form-label fw-semibold">Budget Amount (₹)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">₹</span>
              <input 
                type="number" 
                step="0.01" 
                class="form-control" 
                id="budget_amount" 
                name="budget_amount" 
                placeholder="e.g. 15000" 
                value="{{ old('budget_amount') }}" 
                required 
              />
            </div>
          </div>

          <!-- Month Selection -->
          <div class="mb-3">
            <label for="month" class="form-label fw-semibold">Month</label>
            <select class="form-select" id="month" name="month" required>
              <option value="" disabled selected>Select Month</option>
              <option value="01" {{ date('m') == '01' ? 'selected' : '' }}>January</option>
              <option value="02" {{ date('m') == '02' ? 'selected' : '' }}>February</option>
              <option value="03" {{ date('m') == '03' ? 'selected' : '' }}>March</option>
              <option value="04" {{ date('m') == '04' ? 'selected' : '' }}>April</option>
              <option value="05" {{ date('m') == '05' ? 'selected' : '' }}>May</option>
              <option value="06" {{ date('m') == '06' ? 'selected' : '' }}>June</option>
              <option value="07" {{ date('m') == '07' ? 'selected' : '' }}>July</option>
              <option value="08" {{ date('m') == '08' ? 'selected' : '' }}>August</option>
              <option value="09" {{ date('m') == '09' ? 'selected' : '' }}>September</option>
              <option value="10" {{ date('m') == '10' ? 'selected' : '' }}>October</option>
              <option value="11" {{ date('m') == '11' ? 'selected' : '' }}>November</option>
              <option value="12" {{ date('m') == '12' ? 'selected' : '' }}>December</option>
            </select>
          </div>

          <!-- Year Selection -->
          <div class="mb-4">
            <label for="year" class="form-label fw-semibold">Year</label>
            <input 
              type="number" 
              class="form-control" 
              id="year" 
              name="year" 
              value="{{ date('Y') }}" 
              required 
            />
          </div>

          <!-- Submit Button -->
          <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="bx bx-save me-1"></i> Save Budget Limit
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection