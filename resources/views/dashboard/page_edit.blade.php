@extends('dashboard.layout')

@section('content')

<style>
  .notepad-style {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    white-space: pre-wrap;
    line-height: 1.5;
    border: 1px solid var(--bs-border-color);
    padding: .75rem;
    outline: none;
    resize: vertical;
    width: 100%;
    background: var(--bs-body-bg);
    color: var(--bs-body-color);
    border-radius: .5rem;
  }
  .repeater-item {
    border: 1px solid var(--bs-border-color);
    border-radius: .75rem;
    padding: .75rem;
    background: var(--bs-body-bg);
    color: var(--bs-body-color);
    box-shadow: 0 1px 2px rgba(0,0,0,.03);
  }
  .repeater-item + .repeater-item { margin-top: .75rem; }

  .drag-handle { cursor: grab; user-select: none; color: var(--bs-secondary-color); }

  .repeater-header {
    display: flex; align-items: center; justify-content: space-between; gap: .75rem; flex-wrap: wrap;
  }
  .repeater-header > * { flex: 0 0 auto; }
  @media (max-width: 575.98px){ .repeater-header .btn { width: 100%; } .card-body{ padding:1rem; } }

  .item-number-badge{
    display:inline-flex; align-items:center; justify-content:center;
    min-width: 26px; height: 26px; border-radius: 999px;
    background: var(--bs-secondary-bg);
    color: var(--bs-secondary-color);
    font-weight: 600; font-size: .875rem;
  }
  .form-text { color: var(--bs-secondary-color) !important; }
</style>

<div class="page-content">
  <div class="container-fluid">
    <div class="row align-items-center g-2">
      <div class="col-12">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
          <h4 class="page-title mb-0">Edit Personality</h4>
          <div class="mt-2 mt-md-0">
            <a class="btn btn-sm btn-primary text-white" href="{{ route('dashboard.page.list') }}">
              <i class="fa-solid fa-list me-1"></i> Personality List
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <form method="POST" action="{{ route('dashboard.page.edit.store', $page->id) }}" class="card-body" id="personalityEditForm" novalidate>
        @csrf

        {{-- Personality name --}}
        <div class="mb-3">
          <label class="form-label" for="title">Personality Name</label>
          <input
            type="text"
            name="title"
            id="title"
            class="form-control"
            value="{{ old('title', $page->title) }}"
            required
            aria-required="true"
          >
          <div class="invalid-feedback">Please provide a name for this personality.</div>
        </div>

        {{-- Items header --}}
        <div class="repeater-header mt-3">
          <label class="mb-0">Personality Items (FAQ-style)</label>
          <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
            <i class="fa-solid fa-plus me-1"></i> Add Item
          </button>
        </div>

        {{-- Items container --}}
        <div id="itemsContainer" class="mt-2">
          @php
            // $p is the Personality with items ordered by 'order' (from controller)
            $items = $personality->items ?? collect();
          @endphp

          @forelse($items as $i => $it)
            <div class="repeater-item" data-index="{{ $i }}">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                  <span class="item-number-badge"><span class="item-number">{{ $i+1 }}</span></span>
                  <strong class="mb-0">Item</strong>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="drag-handle" title="Drag (optional)" aria-hidden="true">
                    <i class="fa-solid fa-grip-lines"></i>
                  </span>
                  <button type="button" class="btn btn-sm btn-outline-danger removeItemBtn" aria-label="Remove item {{ $i+1 }}">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </div>

              {{-- Keep id for updates --}}
              <input type="hidden" name="items[{{ $i }}][id]" value="{{ $it->id }}">
              <input type="hidden" name="items[{{ $i }}][order]" value="{{ $i+1 }}" class="order-input">

              <div class="mb-2">
                <label class="form-label my-1" for="items_{{ $i }}_heading">Heading (optional)</label>
                <input type="text" id="items_{{ $i }}_heading" name="items[{{ $i }}][heading]"
                       class="form-control" value="{{ old("items.$i.heading", $it->heading) }}"
                       placeholder="e.g. Pricing / Recharge">
              </div>

              <div class="mb-1">
                <label class="form-label my-1" for="items_{{ $i }}_body">Body</label>
                <textarea id="items_{{ $i }}_body" name="items[{{ $i }}][body]" rows="6" class="form-control notepad-style"
                          placeholder="Enter details for this section..." required aria-required="true">{{ old("items.$i.body", $it->body) }}</textarea>
                <div class="invalid-feedback">This item needs some content.</div>
              </div>
            </div>
          @empty
            {{-- Fallback: show one item if none exist (map old single content) --}}
            <div class="repeater-item" data-index="0">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                  <span class="item-number-badge"><span class="item-number">1</span></span>
                  <strong class="mb-0">Item</strong>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span class="drag-handle" title="Drag (optional)" aria-hidden="true">
                    <i class="fa-solid fa-grip-lines"></i>
                  </span>
                  <button type="button" class="btn btn-sm btn-outline-danger removeItemBtn" disabled aria-disabled="true" aria-label="Remove item 1">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </div>

              <input type="hidden" name="items[0][order]" value="1" class="order-input">

              <div class="mb-2">
                <label class="form-label my-1" for="items_0_heading">Heading (optional)</label>
                <input type="text" id="items_0_heading" name="items[0][heading]" class="form-control" placeholder="e.g. Registration">
              </div>

              <div class="mb-1">
                <label class="form-label my-1" for="items_0_body">Body</label>
                <textarea id="items_0_body" name="items[0][body]" rows="6" class="form-control notepad-style"
                          placeholder="Enter details for this section..." required aria-required="true">{{ old('items.0.body', $page->content) }}</textarea>
                <div class="invalid-feedback">This item needs some content.</div>
              </div>
            </div>
          @endforelse
        </div>

        {{-- collect ids to delete --}}
        <div id="deleteBucket"></div>

        <div class="d-grid d-sm-inline-block">
          <button class="btn btn-primary my-3 px-3" id="saveBtn">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function(){
    const form = document.getElementById('personalityEditForm');
    const itemsContainer = document.getElementById('itemsContainer');
    const addBtn = document.getElementById('addItemBtn');
    const deleteBucket = document.getElementById('deleteBucket');

    // client validation
    form.addEventListener('submit', function (e) {
      const title = document.getElementById('title');
      const bodies = itemsContainer.querySelectorAll('textarea[name^="items"][name$="[body]"]');

      let valid = true;
      if (!title.value.trim()) { valid = false; title.classList.add('is-invalid'); }
      else title.classList.remove('is-invalid');

      bodies.forEach(ta => {
        if (!ta.value.trim()) { valid = false; ta.classList.add('is-invalid'); }
        else ta.classList.remove('is-invalid');
      });

      if (!valid) {
        e.preventDefault();
        e.stopPropagation();
        const firstInvalid = form.querySelector('.is-invalid');
        firstInvalid && firstInvalid.focus();
      }
    });

    function renumber() {
      const blocks = itemsContainer.querySelectorAll('.repeater-item');
      blocks.forEach((block, i) => {
        block.dataset.index = i;

        const numSpan = block.querySelector('.item-number');
        if (numSpan) numSpan.textContent = i + 1;

        const removeBtn = block.querySelector('.removeItemBtn');
        if (removeBtn) {
          const disabled = (blocks.length === 1);
          removeBtn.disabled = disabled;
          removeBtn.setAttribute('aria-disabled', disabled.toString());
          removeBtn.setAttribute('aria-label', `Remove item ${i + 1}`);
        }

        const idInput   = block.querySelector('input[name^="items"][name$="[id]"]');
        const heading   = block.querySelector('input[name^="items"][name$="[heading]"]');
        const body      = block.querySelector('textarea[name^="items"][name$="[body]"]');
        const order     = block.querySelector('input.order-input');

        if (idInput)  idInput.name = `items[${i}][id]`;
        if (heading) { heading.name = `items[${i}][heading]`; heading.id = `items_${i}_heading`; }
        if (body)    { body.name    = `items[${i}][body]`;    body.id    = `items_${i}_body`; }
        if (order)   { order.name   = `items[${i}][order]`;   order.value = i + 1; }
      });
    }

    function makeItem(index) {
      const wrapper = document.createElement('div');
      wrapper.className = 'repeater-item';
      wrapper.dataset.index = index;
      wrapper.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="d-flex align-items-center gap-2">
            <span class="item-number-badge"><span class="item-number">${index + 1}</span></span>
            <strong class="mb-0">Item</strong>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="drag-handle" title="Drag (optional)" aria-hidden="true">
              <i class="fa-solid fa-grip-lines"></i>
            </span>
            <button type="button" class="btn btn-sm btn-outline-danger removeItemBtn" aria-label="Remove item ${index + 1}">
              <i class="fa-solid fa-trash"></i>
            </button>
          </div>
        </div>

        <input type="hidden" name="items[${index}][order]" value="${index + 1}" class="order-input">

        <div class="mb-2">
          <label class="form-label my-1" for="items_${index}_heading">Heading (optional)</label>
          <input type="text" id="items_${index}_heading" name="items[${index}][heading]" class="form-control"
                 placeholder="e.g. Registration, Incoming Calls, Internet">
        </div>

        <div class="mb-1">
          <label class="form-label my-1" for="items_${index}_body">Body</label>
          <textarea id="items_${index}_body" name="items[${index}][body]" rows="6" class="form-control notepad-style"
                    placeholder="Details for this section..." required aria-required="true"></textarea>
          <div class="invalid-feedback">This item needs some content.</div>
        </div>
      `;
      return wrapper;
    }

    addBtn.addEventListener('click', () => {
      const index = itemsContainer.querySelectorAll('.repeater-item').length;
      const block = makeItem(index);
      itemsContainer.appendChild(block);
      renumber();
      block.scrollIntoView({ behavior: 'smooth', block: 'center' });
      const body = block.querySelector('textarea');
      body && body.focus();
    });

    itemsContainer.addEventListener('click', (e) => {
      const btn = e.target.closest('.removeItemBtn');
      if (!btn) return;

      const block = btn.closest('.repeater-item');
      if (!block) return;

      // if existing item, push its id to delete bucket
      const idInput = block.querySelector('input[name^="items"][name$="[id]"]');
      if (idInput && idInput.value) {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'delete_item_ids[]';
        hidden.value = idInput.value;
        deleteBucket.appendChild(hidden);
      }

      block.remove();
      renumber();
    });

    // dash on Enter helper
    itemsContainer.addEventListener('keydown', (e) => {
      const ta = e.target.closest('textarea');
      if (!ta) return;
      if (e.key === 'Enter') {
        const start = ta.selectionStart;
        const value = ta.value;
        ta.value = value.substring(0, start) + "\n- " + value.substring(start);
        ta.selectionStart = ta.selectionEnd = start + 3;
        e.preventDefault();
      }
    });

    // initial
    renumber();
  })();
</script>
@endsection
