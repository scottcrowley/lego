@csrf
<div class="field-group">
    <label for="name">Name:</label>
    <div class="field">
        <input type="text" name="name" id="name" value="{{ old('name', $location->name) }}" class="{{ $errors->has('name') ? 'danger' : '' }}" required>
        {!! $errors->first('name', '<span class="danger">:message</span>') !!}
    </div>
</div>
<div class="field-group">
    <label for="description">Description:</label>
    <div class="field">
        <input type="text" name="description" id="description" value="{{ old('description', $location->description) }}" class="{{ $errors->has('description') ? 'danger' : '' }}" required>
        {!! $errors->first('description', '<span class="danger">:message</span>') !!}
    </div>
</div>

<div class="field-group">
    <label for="description">Storage Type:</label>
    <div class="field">
        <div class="relative">
            @if (count($types))
                <select name="storage_type_id" id="storage_type_id" required>
                    <option value="">Choose a Container Type</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" 
                            {{ (($formType == 'create' && (old('storage_type_id') == $type->id || $location->storage_type_id == $type->id)) || 
                                ($formType == 'edit' && $location->storage_type_id == $type->id)) ? 'selected' : '' 
                            }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current h-4 w-4">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                    </svg>
                </div>
            @else
                <p class="text-sm">There are currently no storage container types. Click <a href="{{ route('storage.types.create') }}" class="text-primary-dark">here</a> to add one.</p>
            @endif
        </div>
        {!! $errors->first('storage_type_id', '<span class="danger">:message</span>') !!}
    </div>
</div>

@if (count($errors))
    <div class="field-group">
        <ul class="danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="field-group flex mt-6 align-center">
    <div class="ml-auto items-center">
        <a href="{{ route('storage.locations.index') }}" class="mr-3">Cancel</a>
        <button type="submit" class="btn is-primary">{{ ($formType == 'edit') ? 'Update' : 'Add'}} Location</button>
    </div>
</div>