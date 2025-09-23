<form method="POST" action="{{ $widget->exists ? route('widgets.update', $widget) : route('widgets.store') }}"
    class="card-body" enctype="multipart/form-data">
    @csrf
    @if ($widget->exists)
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="avatar" class="d-block">
            <p class="mb-2">Logo <small class="text-body-secondary">62×62 px</small></p>
            <div class="d-flex justify-content-center">
                {{-- FIX: remove stray "i" and ensure fallback image works --}}
                <img id="avatar_preview" src="{{ $widget->avatar_url }}" alt="Avatar" class="border rounded "
                    width="82" height="82" style="cursor:pointer;">
            </div>
        </label>
        <input type="file" name="avatar" id="avatar" accept="image/*" style="display:none;">
        @error('avatar')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mt-2">
        <label for="widgetName">Widget Name</label>
        <input type="text" name="widgetName" id="widgetName" class="form-control mt-1"
            value="{{ old('widgetName', $widget->widgetName ?? '') }}" placeholder="e.g. Support Widget">
        @error('widgetName')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mt-2">
        <label for="name">Bot Name</label>
        <input type="text" name="name" id="name" class="form-control mt-1"
            value="{{ old('name', $widget->name ?? '') }}" placeholder="e.g. Alexa" required>
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    @php
        $personalities = \App\Models\Personality::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
    @endphp

    <div class="mb-3">
        <label class="form-label">Personality</label>
        <select name="personality_id" class="form-select">
            <option value="">— None —</option>
            @foreach ($personalities as $p)
                <option value="{{ $p->id }}" @selected($widget->personality_id === $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group mt-2">
        <label for="welcomeMessage">Welcome Message</label>
        <input type="text" name="welcomeMessage" id="welcomeMessage" class="form-control mt-1"
            value="{{ old('welcomeMessage', $widget->welcomeMessage ?? '') }}" placeholder="How can I assist you?"
            required>
        @error('welcomeMessage')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mt-2">
        <label for="color">Color</label>
        <input type="text" class="form-control text-white mt-1" name="color" id="color"
            value="{{ old('color', $widget->color ?? '#0A5') }}" data-huebee />
        @error('color')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-2 mt-3">
        <button class="btn btn-primary">Save</button>

        {{-- optional helper badge to show API key on edit --}}
        @if ($widget->exists && !empty($widget->api_key))
            <span class="badge bg-secondary-subtle text-body-secondary">
                API: <code>{{ \Illuminate\Support\Str::limit($widget->api_key, 18) }}</code>
            </span>
        @endif
    </div>
</form>
