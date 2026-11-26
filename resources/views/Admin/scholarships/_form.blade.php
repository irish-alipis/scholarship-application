@csrf
<div class="grid grid-cols-12 gap-4">
    <div class="md:col-span-6 col-span-12">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $scholarship->name ?? '') }}">
        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="md:col-span-6 col-span-12">
    <label class="form-label">Type</label>
    <select name="type" class="form-control">
        <option value="">Select type</option>
        @foreach(\App\Enums\ScholarshipType::cases() as $t)
            <option value="{{ $t->value }}"
                @selected(old('type', isset($scholarship) ? $scholarship->type->value : '') === $t->value)>
                {{ ucwords(str_replace('_', ' ', $t->value)) }}
            </option>
        @endforeach
    </select>
    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
</div>

    <div class="col-span-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $scholarship->description ?? '') }}</textarea>
        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="md:col-span-4 col-span-12">
        <label class="form-label">Slots</label>
        <input type="number" min="1" name="slots" class="form-control" value="{{ old('slots', $scholarship->slots ?? '') }}">
        @error('slots') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="md:col-span-4 col-span-12">
        <label class="form-label">Start Date</label>
        <input type="datetime-local" name="start_date" class="form-control"
            value="{{ old('start_date', isset($scholarship) ? $scholarship->start_date->format('Y-m-d\TH:i') : '') }}">
        @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    <div class="md:col-span-4 col-span-12">
        <label class="form-label">Deadline</label>
        <input type="datetime-local" name="deadline" class="form-control"
            value="{{ old('deadline', isset($scholarship) ? $scholarship->deadline->format('Y-m-d\TH:i') : '') }}">
        @error('deadline') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
</div>