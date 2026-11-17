@extends('layouts.usermaster')

@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    
    <!-- Page Header Card -->
    <div class="card bg-primary text-white mb-4 shadow-sm border-0">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <h4 class="text-white mb-1">Account Settings ⚙️</h4>
          <p class="mb-0 text-white-50 small">Update your personal credentials and profile information.</p>
        </div>
        <a href="{{ route('userhome') }}" class="btn btn-sm btn-dark bg-white text-primary fw-bold border-0 shadow-sm">
          <i class="bx bx-arrow-back me-1"></i> Back to Dashboard
        </a>
      </div>
    </div>

    <!-- Main Profile Form Card -->
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light py-3 px-4">
        <h5 class="card-title m-0 fw-bold text-dark">Edit Profile Information</h5>
      </div>
      
      <div class="card-body p-4">
        
        <!-- Display Validation Errors -->
        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <!-- Profile Update Form -->
        <form action="{{ route('user.profile.update') }}" method="POST">
          @csrf

          <!-- Full Name -->
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Full Name</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-user"></i></span>
              <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
          </div>

          <!-- Username -->
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Username</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-at"></i></span>
              <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
            </div>
          </div>

          <!-- Contact Number -->
          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Contact Number</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-phone"></i></span>
              <input type="text" name="contact" class="form-control" value="{{ old('contact', $user->contact) }}" required>
            </div>
          </div>

          <!-- Email (Verified & Locked) -->
          <div class="mb-4">
            <label class="form-label fw-semibold text-dark">Email Address (Verified & Locked)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-envelope"></i></span>
              <input type="email" class="form-control bg-light text-muted" value="{{ $user->email }}" disabled>
            </div>
            <small class="text-muted fst-italic mt-1 d-block">Your email address is verified and cannot be edited here.</small>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('userhome') }}" class="btn btn-outline-secondary px-4">Cancel</a>
            <button type="submit" class="btn btn-primary px-4 fw-semibold">
              <i class="bx bx-check me-1"></i> Save Changes
            </button>
          </div>

        </form>

      </div>
    </div>

  </div>
</div>
@endsection