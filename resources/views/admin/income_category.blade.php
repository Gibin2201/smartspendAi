@extends('layouts.adminmaster')

@section('title', 'Manage Income Categories')

@section('content')
<div class="container-fluid flex-grow-1 container-p-y px-4 py-4">
  
  <!-- Success Alert -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
      <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row mt-4">
    <!-- ============================================== -->
    <!-- LEFT COLUMN: ADD CATEGORY FORM                 -->
    <!-- ============================================== -->
    <div class="col-12 col-lg-4 mb-4">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-bottom py-3 px-4">
          <h5 class="mb-0 fw-semibold"><i class="bi bi-tags me-2 text-success"></i>Add Income Category</h5>
        </div>
        <div class="card-body p-4">
          
          <form action="{{ route('storeIncome') }}" method="POST">
            @csrf
            
            <div class="mb-4">
              <label for="incomecategory" class="form-label fw-medium text-dark">Category Name <span class="text-danger">*</span></label>
              
              <div class="input-group shadow-none border-1">
                <span class="input-group-text border-end-0 bg-transparent text-muted"><i class="bi bi-wallet2"></i></span>
                <input type="text" 
                       class="form-control border-start-0 ps-0 @error('incomecategory') is-invalid border-danger @enderror" 
                       id="incomecategory" 
                       name="incomecategory" 
                       placeholder="e.g., Salary, Freelance, Bonus" 
                       value="{{ old('incomecategory') }}"
                       required>
              </div>
              
              @error('incomecategory')
                <div class="text-danger small mt-1 d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
              @enderror
            </div>
            
            <div class="pt-2">
              <button type="submit" class="btn btn-success w-100 py-2 fw-semibold shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Income
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>

    <!-- ============================================== -->
    <!-- RIGHT COLUMN: INCOME CATEGORY TABLE            -->
    <!-- ============================================== -->
    <div class="col-12 col-lg-8">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-transparent border-bottom py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
          <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
              <i class="bi bi-list-ul me-2 text-primary"></i>Manage Categories
            </h5>
            <span class="badge bg-primary bg-opacity-10 text-primary fw-medium rounded-pill px-2.5 py-1" id="categoryCountBadge" style="font-size: 0.8rem;">
              <span id="visibleCount">{{ count($incomes) }}</span> {{ count($incomes) === 1 ? 'category' : 'categories' }}
            </span>
          </div>

          <!-- Search Bar matching current code style -->
          <div class="table-search-box">
            <form action="{{ route('createIncome') }}" method="GET" class="m-0" id="categorySearchForm">
              <div class="input-group table-search-group">
                <span class="input-group-text border-0 bg-transparent text-muted ps-3 pe-2">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" 
                       name="search" 
                       id="categorySearchInput" 
                       class="form-control border-0 bg-transparent table-search-input ps-1 pe-2" 
                       placeholder="Search categories..." 
                       value="{{ request('search') }}"
                       aria-label="Search categories"
                       autocomplete="off">
                @if(request('search'))
                  <a href="{{ route('createIncome') }}" class="btn btn-sm btn-link text-muted p-0 pe-3 d-flex align-items-center" title="Clear filter">
                    <i class="bi bi-x-circle-fill"></i>
                  </a>
                @else
                  <button type="button" id="clearSearchBtn" class="btn btn-sm btn-link text-muted p-0 pe-3 d-none align-items-center" title="Clear search">
                    <i class="bi bi-x-circle-fill"></i>
                  </button>
                @endif
              </div>
            </form>
          </div>
        </div>
        
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="categoriesTable">
              <thead class="table-light">
                <tr>
                  <th class="ps-4" style="width: 70px;">#</th>
                  <th>Category Name</th>
                  <th class="text-end pe-4" style="width: 180px;">Actions</th>
                </tr>
              </thead>
              <tbody id="categoryTableBody">
                @forelse($incomes as $key => $income)
                  <tr class="category-row" data-category-name="{{ strtolower($income->incomecategory) }}">
                    <td class="ps-4 row-index">{{ $key + 1 }}</td>
                    <td class="fw-medium category-name-cell">{{ $income->incomecategory }}</td>
                    <td class="text-end pe-4">
                      
                      <!-- Action Buttons Wrapper -->
                      <div class="d-flex justify-content-end align-items-center gap-2">
                        
                        <!-- Update/Edit Button -->
                        <a href="{{ route('editIncome', $income->incomecategory_id) }}" 
                           class="btn btn-sm btn-outline-primary d-flex align-items-center rounded-pill px-3 shadow-none" 
                           title="Edit Category">
                          <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>

                        <!-- Delete Form -->
                        <form action="{{ route('deleteIncome', $income->incomecategory_id) }}" 
                              method="POST" 
                              class="m-0" 
                              onsubmit="return confirm('Are you sure you want to delete this category?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" 
                                  class="btn btn-sm btn-outline-danger d-flex align-items-center rounded-pill px-3 shadow-none" 
                                  title="Delete Category">
                            <i class="bi bi-trash me-1"></i> Delete
                          </button>
                        </form>

                      </div>

                    </td>
                  </tr>
                @empty
                  <tr id="emptyDbRow">
                    <td colspan="3" class="text-center py-5 text-muted">
                      <i class="bi bi-folder-x fs-1 d-block mb-2 text-black-50"></i>
                      @if(request('search'))
                        No income categories match "{{ request('search') }}". <a href="{{ route('createIncome') }}" class="text-primary ms-1">View all</a>
                      @else
                        No income categories found.
                      @endif
                    </td>
                  </tr>
                @endforelse
                <tr id="noMatchRow" style="display: none;">
                  <td colspan="3" class="text-center py-5 text-muted">
                    <i class="bi bi-search fs-2 d-block mb-2 text-black-50"></i>
                    <div>No categories found matching "<span id="searchQueryText" class="fw-semibold text-dark"></span>"</div>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill mt-3 px-3" id="resetSearchBtn">
                      <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Search
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('categorySearchInput');
  const clearBtn = document.getElementById('clearSearchBtn');
  const resetBtn = document.getElementById('resetSearchBtn');
  const tableBody = document.getElementById('categoryTableBody');
  const rows = tableBody ? tableBody.querySelectorAll('.category-row') : [];
  const noMatchRow = document.getElementById('noMatchRow');
  const searchQueryText = document.getElementById('searchQueryText');
  const visibleCountEl = document.getElementById('visibleCount');

  if (!searchInput) return;

  function filterCategories() {
    const query = searchInput.value.trim().toLowerCase();
    let visibleCount = 0;

    if (clearBtn) {
      if (query.length > 0) {
        clearBtn.classList.remove('d-none');
        clearBtn.classList.add('d-flex');
      } else {
        clearBtn.classList.add('d-none');
        clearBtn.classList.remove('d-flex');
      }
    }

    rows.forEach((row) => {
      const categoryName = (row.getAttribute('data-category-name') || '').trim();
      if (query === '' || categoryName.includes(query)) {
        row.style.display = '';
        visibleCount++;
        const indexCell = row.querySelector('.row-index');
        if (indexCell) {
          indexCell.textContent = visibleCount;
        }
      } else {
        row.style.display = 'none';
      }
    });

    if (visibleCountEl) {
      visibleCountEl.textContent = visibleCount;
    }

    if (noMatchRow) {
      if (rows.length > 0 && visibleCount === 0 && query !== '') {
        noMatchRow.style.display = '';
        if (searchQueryText) {
          searchQueryText.textContent = searchInput.value.trim();
        }
      } else {
        noMatchRow.style.display = 'none';
      }
    }
  }

  searchInput.addEventListener('input', filterCategories);

  if (clearBtn) {
    clearBtn.addEventListener('click', function () {
      searchInput.value = '';
      filterCategories();
      searchInput.focus();
    });
  }

  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      searchInput.value = '';
      filterCategories();
      searchInput.focus();
    });
  }

  searchInput.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      searchInput.value = '';
      filterCategories();
    }
  });

  if (searchInput.value.trim().length > 0) {
    filterCategories();
  }
});
</script>
@endpush