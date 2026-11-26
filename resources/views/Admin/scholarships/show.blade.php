@extends('layouts.master')

@section('title', $scholarship->name)
@section('page-title', 'Scholarship Details')

@section('content')
    <div class="box custom-box">
        <div class="box-header flex justify-between">
            <div class="box-title">{{ $scholarship->name }}</div>
            <div class="flex gap-2 items-center">
                <form method="POST" action="{{ route('admin.scholarships.status', $scholarship) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-control !py-1 !text-[.75rem]" onchange="this.form.submit()">
                        @foreach(\App\Enums\ScholarshipStatus::cases() as $st)
                            <option value="{{ $st->value }}" @selected($scholarship->status === $st)>{{ ucfirst($st->value) }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('admin.scholarships.edit', $scholarship) }}"
                    class="ti-btn ti-btn-info !py-1 !px-2 !text-[0.75rem]">Edit</a>
                <a href="{{ route('admin.scholarships.index') }}"
                    class="ti-btn ti-btn-light !py-1 !px-2 !text-[0.75rem]">Back</a>
            </div>
        </div>
        <div class="box-body">
            <p class="mb-3">{{ $scholarship->description }}</p>
            <div class="grid grid-cols-12 gap-4">
                <div class="md:col-span-3 col-span-6"><small>Type</small>
                    <div class="font-semibold">{{ $scholarship->type }}</div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Slots</small>
                    <div class="font-semibold">{{ $scholarship->slots }}</div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Start</small>
                    <div class="font-semibold">{{ $scholarship->start_date->format('M d, Y h:i A') }}</div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Deadline</small>
                    <div class="font-semibold">{{ $scholarship->deadline->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="box custom-box">
        <div class="box-header">
            <div class="box-title">Eligibility Requirements</div>
        </div>
        <div class="box-body">
            <div class="table-responsive mb-4">
                <table class="table whitespace-nowrap table-bordered min-w-full">
                    <thead>
                        <tr class="border-b border-defaultborder">
                            <th class="text-start">Requirement</th>
                            <th class="text-start">Condition</th>
                            <th class="text-start">Value</th>
                            <th class="text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scholarship->requirements as $req)
                            <tr class="border-b border-defaultborder">
                                <th scope="row">
                                    {{ $req->requirement_type->label() }}
                                </th>
                                <td>{{ $req->requirement_condition->value }}
                                </td>
                                <td>{{ $req->displayValue() }}</td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('admin.scholarships.requirements.destroy', [$scholarship, $req]) }}"
                                        data-confirm="Are you sure you want to remove this requirement?"
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger text-[.875rem] leading-none"><i
                                                class="ri-delete-bin-5-line"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No requirements set yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <form method="POST" action="{{ route('admin.scholarships.requirements.store', $scholarship) }}">
                @csrf
                <div class="grid grid-cols-12 gap-4 items-start">
                    <div class="md:col-span-4 col-span-12">
                        <label class="form-label">Requirement</label>
                        <select name="requirement_type" class="form-control">
                            @foreach(\App\Enums\RequirementType::cases() as $case)
                                <option value="{{ $case->value }}" @selected(old('requirement_type') === $case->value)>
                                    {{ $case->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('requirement_type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="md:col-span-3 col-span-12">
                        <label class="form-label">Condition</label>
                        <select name="requirement_condition" class="form-control">
                            @foreach(\App\Enums\RequirementCondition::cases() as $case)
                                <option value="{{ $case->value }}" @selected(old('requirement_condition') === $case->value)>
                                    {{ $case->value }}
                                </option>
                            @endforeach
                        </select>
                        @error('requirement_condition') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="md:col-span-3 col-span-12">
                        <label class="form-label">Value</label>
                        <input type="number" step="0.01" min="0" name="required_value" class="form-control"
                            value="{{ old('required_value') }}">
                        @error('required_value') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="md:col-span-2 col-span-12">
                        <label class="form-label invisible">Save</label>
                        <button type="submit" class="ti-btn ti-btn-primary w-full">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="box custom-box">
        <div class="box-header">
            <div class="box-title">Required Documents</div>
        </div>
        <div class="box-body">
            <div class="table-responsive mb-4">
                <table class="table whitespace-nowrap table-bordered min-w-full">
                    <thead>
                        <tr class="border-b border-defaultborder">
                            <th class="text-start">Document</th>
                            <th class="text-start">Type</th>
                            <th class="text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scholarship->requiredDocuments as $doc)
                            <tr class="border-b border-defaultborder">
                                <th scope="row">{{ $doc->name }}</th>
                                <td>
                                    @if($doc->is_required)
                                        <span class="badge bg-danger/10 text-danger">Required</span>
                                    @else
                                        <span class="badge bg-secondary/10 text-secondary">Optional</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST"
                                        action="{{ route('admin.scholarships.documents.destroy', [$scholarship, $doc]) }}"
                                        data-confirm="Are you sure you want to remove this document?"
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger text-[.875rem] leading-none"><i
                                                class="ri-delete-bin-5-line"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No documents set yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <form method="POST" action="{{ route('admin.scholarships.documents.store', $scholarship) }}">
                @csrf
                <div class="grid grid-cols-12 gap-4 items-start">
                    <div class="md:col-span-6 col-span-12">
                        <label class="form-label">Document name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="e.g. Certificate of Registration">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="md:col-span-3 col-span-12">
                        <label class="form-label">Type</label>
                        <select name="is_required" class="form-control">
                            <option value="1">Required</option>
                            <option value="0">Optional</option>
                        </select>
                    </div>
                    <div class="md:col-span-3 col-span-12">
                        <label class="form-label invisible">Add</label>
                        <button type="submit" class="ti-btn ti-btn-primary w-full">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection