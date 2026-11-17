@extends('layouts.usermaster')

@section('content')
<div class="container-fluid px-3 py-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-dark m-0">Manage Debts & Loans</h3>
        
        {{-- UX: Live Main Balance Display Badge --}}
        <div class="card shadow-sm border-0 bg-light px-3 py-2">
            <span class="small text-muted d-block text-end fw-semibold">Main Balance</span>
            <h5 class="mb-0 fw-bold {{ ($mainBalance ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                ₹{{ number_format($mainBalance ?? 0, 2) }}
            </h5>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors Alert --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Top Notification Banner for Overdue Debts --}}
    @php
        $overdueCount = $debts->where('status', 'pending')->filter(function($debt) {
            return \Carbon\Carbon::parse($debt->todate)->isPast();
        })->count();
    @endphp

    @if($overdueCount > 0)
        <div class="alert alert-danger py-2 mb-3 d-flex align-items-center" role="alert">
            <div>
                <strong>Notice:</strong> You have <strong>{{ $overdueCount }}</strong> pending debt(s) past their repayment deadline!
            </div>
        </div>
    @endif

    <div class="row g-3">
        {{-- LEFT SIDE: Form to Add Debt --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0 fw-semibold">Add New Debt / Lend</h6>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('user.debts.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">Person Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" required value="{{ old('name') }}">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">Purpose</label>
                            <input type="text" name="purpose" class="form-control form-control-sm" required value="{{ old('purpose') }}">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">Amount</label>
                            <input type="number" step="0.01" name="debt_amount" class="form-control form-control-sm" required value="{{ old('debt_amount') }}">
                        </div>

                        {{-- Dynamic Category Dropdown --}}
                        <div class="mb-2">
                            <label class="form-label small fw-bold mb-1">Select Category (Type)</label>
                            <select name="category_id" id="category_id" class="form-select form-select-sm" required>
                                <option value="">-- Choose Category --</option>
                                
                                {{-- Lend Categories --}}
                                @if(isset($lendCategories) && $lendCategories->count() > 0)
                                    @foreach($lendCategories as $category)
                                        <option value="{{ $category->expensecategory_id ?? $category->id }}" 
                                                data-islent="1" 
                                                data-isborrow="0"
                                                {{ old('category_id') == ($category->expensecategory_id ?? $category->id) ? 'selected' : '' }}>
                                            {{ $category->expensecategory ?? $category->name }} (Lend)
                                        </option>
                                    @endforeach
                                @endif

                                {{-- Borrow Categories --}}
                                @if(isset($borrowCategories) && $borrowCategories->count() > 0)
                                    @foreach($borrowCategories as $category)
                                        <option value="{{ $category->incomecategory_id ?? $category->id }}" 
                                                data-islent="0" 
                                                data-isborrow="1"
                                                {{ old('category_id') == ($category->incomecategory_id ?? $category->id) ? 'selected' : '' }}>
                                            {{ $category->incomecategory ?? $category->name }} (Borrow)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Hidden input for debt_type --}}
                        <input type="hidden" name="debt_type" id="debt_type" value="{{ old('debt_type') }}">

                        {{-- Dates Row --}}
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label small fw-bold mb-1">From Date</label>
                                <input type="date" name="fromdate" class="form-control form-control-sm" required value="{{ old('fromdate', date('Y-m-d')) }}">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label small fw-bold mb-1">Deadline</label>
                                <input type="date" name="todate" class="form-control form-control-sm" required value="{{ old('todate') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 fw-semibold">Save Debt Record</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: Display List of Debts --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white py-2">
                    <h6 class="mb-0 fw-semibold">Your Debt Records</h6>
                </div>
                <div class="card-body p-2">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0 small">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Purpose</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debts as $debt)
                                @php
                                    $isOverdue = ($debt->status == 'pending' && \Carbon\Carbon::parse($debt->todate)->isPast());
                                    $isDueToday = ($debt->status == 'pending' && \Carbon\Carbon::parse($debt->todate)->isToday());
                                @endphp

                                <tr class="{{ $isOverdue ? 'table-danger' : ($isDueToday ? 'table-warning' : '') }}">
                                    <td class="fw-semibold">
                                        {{ $debt->name }}
                                        @if($isOverdue)
                                            <span class="badge bg-danger ms-1" style="font-size: 0.60rem;">Overdue</span>
                                        @elseif($isDueToday)
                                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.60rem;">Due Today</span>
                                        @endif
                                    </td>
                                    <td>{{ $debt->purpose }}</td>
                                    <td>₹{{ number_format($debt->debt_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $debt->debt_type == 'lent' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ucfirst($debt->debt_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $debt->todate }}</td>
                                    <td>
                                        <span class="badge {{ $debt->status == 'repaid' ? 'bg-info text-dark' : 'bg-danger' }}">
                                            {{ ucfirst($debt->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($debt->status == 'pending')
                                            <form action="{{ route('user.debts.updateStatus', $debt->dept_id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-success btn-sm py-0 px-2" style="font-size: 0.75rem;">Mark Repaid</button>
                                            </form>
                                        @else
                                            <span class="text-muted fw-bold" style="font-size: 0.75rem;">Settled</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">No debt records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript to automatically populate hidden debt_type field --}}
<script>
    document.getElementById('category_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var isLent = selectedOption.getAttribute('data-islent') == '1';
        var isBorrow = selectedOption.getAttribute('data-isborrow') == '1';
        
        var debtTypeInput = document.getElementById('debt_type');

        if (isLent) {
            debtTypeInput.value = 'lent';
        } else if (isBorrow) {
            debtTypeInput.value = 'borrow';
        } else {
            debtTypeInput.value = '';
        }
    });
</script>
@endsection