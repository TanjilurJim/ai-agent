@extends('dashboard.layout')

@section('content')

<style>
  /* Modern styling with better visual hierarchy */
  .personality-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }
  
  .card-header {
    background: var(--bs-body-bg);
    border-bottom: 1px solid var(--bs-border-color);
    padding: 1.25rem 1.5rem;
  }
  
  .card-body {
    padding: 1.5rem;
  }
  
  .form-label {
    font-weight: 600;
    color: var(--bs-body-color);
    margin-bottom: 0.5rem;
  }
  
  .form-control, .form-select {
    border-radius: 8px;
    border: 1px solid var(--bs-border-color);
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
  }
  
  .form-control:focus, .form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.1);
  }
  
  /* Improved notepad styling */
  .notepad-style {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    white-space: pre-wrap;
    line-height: 1.6;
    border-radius: 8px;
    padding: 1rem;
    min-height: 160px;
    resize: vertical;
    transition: all 0.2s ease;
  }
  
  .notepad-style:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.1);
  }
  
  /* Enhanced repeater items */
  .repeater-item {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
    position: relative;
  }
  
  .repeater-item:hover {
    border-color: var(--bs-primary);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }
  
  .repeater-item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--bs-border-color-translucent);
  }
  
  .item-number {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--bs-body-color);
  }
  
  .item-number-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--bs-primary);
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
  }
  
  .item-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .drag-handle {
    cursor: grab;
    color: var(--bs-secondary-color);
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.2s ease;
  }
  
  .drag-handle:hover {
    background: var(--bs-secondary-bg);
    color: var(--bs-body-color);
  }
  
  .drag-handle:active {
    cursor: grabbing;
  }
  
  .remove-item-btn {
    transition: all 0.2s ease;
  }
  
  .remove-item-btn:not(:disabled):hover {
    background: var(--bs-danger);
    color: white;
    border-color: var(--bs-danger);
  }
  
  /* Helper text styling */
  .form-text {
    font-size: 0.875rem;
    margin-top: 0.5rem;
    color: var(--bs-secondary-color) !important;
  }
  
  /* Button improvements */
  .btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.625rem 1.25rem;
    transition: all 0.2s ease;
  }
  
  .btn-sm {
    padding: 0.5rem 1rem;
    border-radius: 6px;
  }
  
  .btn-primary {
    box-shadow: 0 2px 4px rgba(var(--bs-primary-rgb), 0.2);
  }
  
  .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.3);
  }
  
  /* Empty state for items container */
  .empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--bs-secondary-color);
    border: 2px dashed var(--bs-border-color);
    border-radius: 12px;
    margin-bottom: 1rem;
  }
  
  .empty-state.hidden {
    display: none;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .card-body {
      padding: 1rem;
    }
    
    .repeater-item {
      padding: 1rem;
    }
    
    .repeater-item-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.75rem;
    }
    
    .item-actions {
      align-self: flex-end;
    }
  }
</style>

<div class="page-content">
  <div class="container-fluid">
    <!-- Page Header -->
    <div class="row align-items-center mb-4">
      <div class="col">
        <div class="page-title-box d-flex flex-wrap align-items-center justify-content-between">
          <div class="mb-2 mb-md-0">
            <h4 class="page-title mb-1">Create Personality</h4>
            <p class="text-muted mb-0">Define a new personality with FAQ-style content</p>
          </div>
          <a class="btn btn-outline-primary" href="/dashboard/train-bot/page-list">
            <i class="fa-solid fa-list me-1"></i> View Personalities
          </a>
        </div>
      </div>
    </div>

    <!-- Main Form Card -->
    <div class="card personality-card">
      <div class="card-header">
        <h5 class="card-title mb-0">Personality Details</h5>
      </div>
      <form method="POST" action="{{ route('dashboard.page.store') }}" class="card-body" id="personalityForm" novalidate>
        @csrf

        <!-- Personality Name -->
        <div class="mb-4">
          <label class="form-label" for="title">Personality Name</label>
          <input
            type="text"
            name="title"
            id="title"
            class="form-control"
            placeholder="e.g. IP Telephony FAQs, Customer Support Guide"
            value="{{ old('title') }}"
            required
            aria-required="true"
          >
          <div class="invalid-feedback">Please provide a name for this personality.</div>
          {{-- <div class="form-text">Give your personality a descriptive name that reflects its purpose.</div> --}}
        </div>

        <!-- Items Section -->
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <label class="form-label mb-1">Personality Items</label>
              {{-- <p class="text-muted mb-0">Add FAQ-style items to define the personality's knowledge</p> --}}
            </div>
            <button type="button" class="btn btn-primary" id="addItemBtn">
              <i class="fa-solid fa-plus me-1"></i> Add Item
            </button>
          </div>

          <div id="itemsContainer">
            <!-- Empty state (shown when no items) -->
            <div id="emptyState" class="empty-state hidden">
              <i class="fa-solid fa-inbox fa-2x mb-3"></i>
              <p class="mb-2">No items added yet</p>
              <p class="small">Click "Add Item" to create your first FAQ item</p>
            </div>

            <!-- Initial item (index 0) -->
            <div class="repeater-item" data-index="0">
              <div class="repeater-item-header">
                <div class="item-number">
                  <span class="item-number-badge">1</span>
                  <span>Item</span>
                </div>
                <div class="item-actions">
                  <span class="drag-handle" title="Drag to reorder">
                    <i class="fa-solid fa-grip-lines"></i>
                  </span>
                  <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" disabled aria-disabled="true">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </div>

              <input type="hidden" name="items[0][order]" value="1" class="order-input">

              <div class="mb-3">
                <label class="form-label" for="items_0_heading">Heading (optional)</label>
                <input 
                  type="text" 
                  id="items_0_heading" 
                  name="items[0][heading]" 
                  class="form-control"
                  placeholder="e.g. Pricing, Registration, Troubleshooting"
                >
                {{-- <div class="form-text">A short heading to categorize this item (optional)</div> --}}
              </div>

              <div class="mb-2">
                <label class="form-label" for="items_0_body">Content</label>
                <textarea 
                  id="items_0_body" 
                  name="items[0][body]" 
                  rows="5" 
                  class="form-control notepad-style"
                  placeholder="Enter the content for this item..."
                  required 
                  aria-required="true"
                >{{ old('items.0.body') }}</textarea>
                <div class="form-text">
                  <i class="fa-solid fa-lightbulb me-1"></i> 
                  Tip: Press Enter to start a new line with a dash automatically.
                </div>
                <div class="invalid-feedback">Please provide content for this item.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
          <button type="submit" class="btn btn-primary px-4" id="saveBtn">
            <i class="fa-solid fa-floppy-disk me-1"></i> Save Personality
          </button>
          <a href="/dashboard/train-bot/page-list" class="btn btn-outline-secondary">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function(){
    const form = document.getElementById('personalityForm');
    const itemsContainer = document.getElementById('itemsContainer');
    const addBtn = document.getElementById('addItemBtn');
    const emptyState = document.getElementById('emptyState');

    // Update empty state visibility
    function updateEmptyState() {
      const items = itemsContainer.querySelectorAll('.repeater-item');
      if (items.length === 0) {
        emptyState.classList.remove('hidden');
      } else {
        emptyState.classList.add('hidden');
      }
    }

    // Form validation
    form.addEventListener('submit', function (e) {
      let valid = true;
      
      // Validate title
      const title = document.getElementById('title');
      if (!title.value.trim()) {
        valid = false;
        title.classList.add('is-invalid');
      } else {
        title.classList.remove('is-invalid');
      }
      
      // Validate items
      const items = itemsContainer.querySelectorAll('.repeater-item');
      if (items.length === 0) {
        valid = false;
        // Show message about needing at least one item
        if (!document.getElementById('noItemsError')) {
          const errorDiv = document.createElement('div');
          errorDiv.id = 'noItemsError';
          errorDiv.className = 'alert alert-warning mt-3';
          errorDiv.innerHTML = '<i class="fa-solid fa-exclamation-triangle me-1"></i> Please add at least one item to create a personality.';
          itemsContainer.parentNode.insertBefore(errorDiv, itemsContainer);
        }
      } else {
        const noItemsError = document.getElementById('noItemsError');
        if (noItemsError) noItemsError.remove();
        
        // Validate each item's body
        items.forEach(item => {
          const body = item.querySelector('textarea[name$="[body]"]');
          if (!body.value.trim()) {
            valid = false;
            body.classList.add('is-invalid');
          } else {
            body.classList.remove('is-invalid');
          }
        });
      }
      
      if (!valid) {
        e.preventDefault();
        e.stopPropagation();
        
        // Scroll to first error
        const firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) {
          firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          firstInvalid.focus();
        }
      }
    });

    // Renumber items and update UI
    function renumber() {
      const items = itemsContainer.querySelectorAll('.repeater-item');
      
      items.forEach((item, index) => {
        item.dataset.index = index;
        
        // Update number badge
        const badge = item.querySelector('.item-number-badge');
        if (badge) badge.textContent = index + 1;
        
        // Update remove button state and aria
        const removeBtn = item.querySelector('.remove-item-btn');
        if (removeBtn) {
          removeBtn.disabled = items.length === 1;
          removeBtn.setAttribute('aria-label', `Remove item ${index + 1}`);
          removeBtn.setAttribute('aria-disabled', items.length === 1 ? 'true' : 'false');
        }
        
        // Update all input names and IDs
        const heading = item.querySelector('input[name$="[heading]"]');
        const body = item.querySelector('textarea[name$="[body]"]');
        const order = item.querySelector('.order-input');
        
        if (heading) {
          heading.name = `items[${index}][heading]`;
          heading.id = `items_${index}_heading`;
        }
        
        if (body) {
          body.name = `items[${index}][body]`;
          body.id = `items_${index}_body`;
        }
        
        if (order) {
          order.name = `items[${index}][order]`;
          order.value = index + 1;
        }
      });
      
      updateEmptyState();
    }

    // Create new item template
    function createItem(index) {
      const item = document.createElement('div');
      item.className = 'repeater-item';
      item.dataset.index = index;
      item.innerHTML = `
        <div class="repeater-item-header">
          <div class="item-number">
            <span class="item-number-badge">${index + 1}</span>
            <span>Item</span>
          </div>
          <div class="item-actions">
            <span class="drag-handle" title="Drag to reorder">
              <i class="fa-solid fa-grip-lines"></i>
            </span>
            <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" aria-label="Remove item ${index + 1}">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </div>
        
        <input type="hidden" name="items[${index}][order]" value="${index + 1}" class="order-input">
        
        <div class="mb-3">
          <label class="form-label" for="items_${index}_heading">Heading (optional)</label>
          <input 
            type="text" 
            id="items_${index}_heading" 
            name="items[${index}][heading]" 
            class="form-control"
            placeholder="e.g. Pricing, Registration, Troubleshooting"
          >
          <div class="form-text">A short heading to categorize this item (optional)</div>
        </div>
        
        <div class="mb-2">
          <label class="form-label" for="items_${index}_body">Content</label>
          <textarea 
            id="items_${index}_body" 
            name="items[${index}][body]" 
            rows="5" 
            class="form-control notepad-style"
            placeholder="Enter the content for this item..."
            required 
            aria-required="true"
          ></textarea>
          <div class="form-text">
            <i class="fa-solid fa-lightbulb me-1"></i> 
            Tip: Press Enter to start a new line with a dash automatically.
          </div>
          <div class="invalid-feedback">Please provide content for this item.</div>
        </div>
      `;
      
      return item;
    }

    // Add new item
    addBtn.addEventListener('click', () => {
      const items = itemsContainer.querySelectorAll('.repeater-item');
      const index = items.length;
      const newItem = createItem(index);
      
      itemsContainer.appendChild(newItem);
      renumber();
      
      // Scroll to and focus the new item
      newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
      const textarea = newItem.querySelector('textarea');
      setTimeout(() => textarea.focus(), 300);
    });

    // Remove item
    itemsContainer.addEventListener('click', (e) => {
      if (!e.target.closest('.remove-item-btn')) return;
      
      const btn = e.target.closest('.remove-item-btn');
      if (btn.disabled) return;
      
      const item = btn.closest('.repeater-item');
      item.style.opacity = '0';
      item.style.transform = 'translateX(-20px)';
      
      setTimeout(() => {
        item.remove();
        renumber();
      }, 300);
    });

    // Textarea helper: add dash on new line
    itemsContainer.addEventListener('keydown', (e) => {
      if (e.key !== 'Enter' || !e.target.matches('textarea')) return;
      
      const textarea = e.target;
      const start = textarea.selectionStart;
      const value = textarea.value;
      
      // Only add dash if we're at the beginning of a new line
      if (start === 0 || value.substring(start - 1, start) === '\n') {
        e.preventDefault();
        textarea.value = value.substring(0, start) + '- ' + value.substring(start);
        textarea.selectionStart = textarea.selectionEnd = start + 2;
      }
    });

    // Initialize
    updateEmptyState();
    
    // Remove no items error when first item is added
    const observer = new MutationObserver(() => {
      const noItemsError = document.getElementById('noItemsError');
      if (noItemsError && itemsContainer.querySelectorAll('.repeater-item').length > 0) {
        noItemsError.remove();
      }
    });
    
    observer.observe(itemsContainer, { childList: true });
  })();
</script>

<!-- Include SortableJS for drag and drop functionality -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
  // Initialize drag and drop when DOM is loaded
  document.addEventListener('DOMContentLoaded', function() {
    const itemsContainer = document.getElementById('itemsContainer');
    
    // Initialize SortableJS
    const sortable = new Sortable(itemsContainer, {
      handle: '.drag-handle',
      animation: 150,
      ghostClass: 'sortable-ghost',
      chosenClass: 'sortable-chosen',
      dragClass: 'sortable-drag',
      onEnd: function() {
        // Renumber items after reordering
        const renumberFunction = new Function('renumber')();
        if (typeof renumberFunction === 'function') {
          renumberFunction();
        }
      }
    });
  });
</script>

@endsection