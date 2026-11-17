@extends('layouts.usermaster')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Manage Savings Goals</h3>
            <p class="text-muted small m-0">Monitor your financial targets, adjust target timelines, and review deposit histories.</p>
        </div>
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body px-4 py-2 text-end">
                <span class="d-block small text-uppercase fw-semibold opacity-75">Main Balance</span>
                <span class="fs-5 fw-bold">₹{{ number_format($mainBalance, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm py-2 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close py-3" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm py-2 mb-4" role="alert">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close py-3" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- TOP SECTION: Form and Active Goals Side-by-Side --}}
    <div class="row g-4 mb-4">
        {{-- LEFT: Add Savings Goal Form --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Add Savings Goal</h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('user.savings.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Goal Purpose</label>
                            <input type="text" name="saving_purpose" class="form-control" required placeholder="e.g., Emergency Fund" value="{{ old('saving_purpose') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Target Amount (₹)</label>
                            <input type="number" step="0.01" name="target_amount" class="form-control" required placeholder="0.00" value="{{ old('target_amount') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Initial Saved Amount (₹)</label>
                            <input type="number" step="0.01" name="saved_amount" class="form-control" required placeholder="0.00" value="{{ old('saved_amount', 0) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Target Deadline</label>
                            <input type="date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm">Create Goal</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT: Your Savings Goals Table --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-wallet2 me-2"></i>Your Savings Goals</h6>
                    <span class="badge bg-light text-secondary border px-2 py-1">{{ count($savings) }} Active Goals</span>
                </div>
                <div class="card-body p-0" style="max-height: 380px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-secondary sticky-top" style="top: 0; z-index: 1;">
                                <tr>
                                    <th class="ps-3 py-3">Purpose</th>
                                    <th>Target</th>
                                    <th>Saved</th>
                                    <th style="width: 18%;">Progress</th>
                                    <th>Deadline</th>
                                    <th class="text-center pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($savings as $goal)
                                    @php
                                        $percent = $goal->target_amount > 0 ? min(100, round(($goal->saved_amount / $goal->target_amount) * 100)) : 0;
                                        $daysLeft = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($goal->end_date), false);
                                        $isAchieved = $goal->saved_amount >= $goal->target_amount;
                                    @endphp
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark">{{ $goal->saving_purpose }}</td>
                                        <td class="text-muted">₹{{ number_format($goal->target_amount, 2) }}</td>
                                        <td class="text-success fw-bold">₹{{ number_format($goal->saved_amount, 2) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="small fw-semibold text-muted">{{ $percent }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column align-items-start">
                                                <form action="{{ route('user.savings.updatedeadline', $goal->savings_id) }}" method="POST" class="d-flex align-items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="date" name="end_date" value="{{ $goal->end_date }}" class="form-control form-control-sm px-1 py-0 text-muted small border-0 bg-transparent text-decoration-underline" style="width: 115px; cursor: pointer;" required onchange="this.form.submit()" title="Click to change date">
                                                </form>
                                                @if(!$isAchieved)
                                                    @if($daysLeft < 0)
                                                        <span class="badge bg-danger text-white mt-1 px-1 py-0" style="font-size: 0.65rem;">Passed</span>
                                                    @elseif($daysLeft <= 7)
                                                        <span class="badge bg-warning text-dark mt-1 px-1 py-0" style="font-size: 0.65rem;">Due in {{ $daysLeft }}d</span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="d-inline-flex gap-1 align-items-center justify-content-center">
                                                @if(!$isAchieved)
                                                    <form action="{{ route('user.savings.deposit', $goal->savings_id) }}" method="POST" class="d-inline-flex gap-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="number" step="0.01" name="deposit_amount" placeholder="₹" class="form-control form-control-sm px-2" style="width: 70px;" required>
                                                        <button type="submit" class="btn btn-outline-success btn-sm px-2 py-1 fw-semibold">Add</button>
                                                    </form>

                                                    <form action="{{ route('user.savings.destroy', $goal->savings_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this goal? The saved amount (₹{{ number_format($goal->saved_amount, 2) }}) will be refunded to your main balance.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm px-2 py-1" title="Delete Goal"><i class="bi bi-trash"></i></button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-success-subtle text-success px-2 py-1">Achieved</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No savings goals found. Create your first goal to get started!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BOTTOM SECTION: Full-Width Deposit History Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2"></i>Savings Deposit History</h6>
                </div>
                <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-secondary sticky-top" style="top: 0; z-index: 1;">
                                <tr>
                                    <th class="ps-3 py-3">Date & Time Added</th>
                                    <th>Goal Purpose</th>
                                    <th class="pe-3 text-end">Amount Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $txn)
                                    <tr>
                                        <td class="ps-3 text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            @if($txn->savingsGoal)
                                                <span class="fw-semibold text-dark">{{ $txn->savingsGoal->saving_purpose }}</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1">Deleted Goal</span>
                                            @endif
                                        </td>
                                        <td class="pe-3 text-end text-success fw-bold">+₹{{ number_format($txn->deposit_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No savings transactions recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection