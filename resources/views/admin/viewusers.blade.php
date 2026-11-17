@extends('layouts.adminmaster')

@section('title', 'User Management')

@section('content')
<main class="dashboard-content">
  <div class="container-fluid px-3 px-lg-4 py-4">
    
    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h4 class="mb-1 fw-bold text-dark">User Management</h4>
        <p class="text-muted mb-0">View all registered platform users and their account details.</p>
      </div>
    </div>

    <!-- Users Table Card -->
    <div class="card shadow border-0 rounded-4">
      <div class="card-header bg-white py-3 px-4 border-bottom rounded-top-4">
        <h5 class="card-title m-0 text-dark fw-bold">All Registered Users</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-secondary">
              <tr>
                <th class="py-3 px-4">Name</th>
                <th class="py-3">Email</th>
                <th class="py-3">Role</th>
                <th class="py-3">Joined Date</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr>
                  <td class="px-4 fw-semibold text-dark">{{ $user->name }}</td>
                  <td class="text-muted">{{ $user->email }}</td>
                  <td>
                    <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-success' }} bg-opacity-10 text-{{ $user->role == 'admin' ? 'danger' : 'success' }} px-3 py-2 rounded-pill fw-semibold">
                      {{ ucfirst($user->role) }}
                    </span>
                  </td>
                  <td class="text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center py-5 text-muted">
                    <i class="bi bi-folder-open fs-1 mb-2 d-block text-black-50"></i>
                    No users found.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>
@endsection