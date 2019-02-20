@extends('layouts.app')

@section('content')
<div class="flex items-center">
    <div class="w-3/4 lg:w-1/2 mx-auto">
        <div class="rounded shadow">
            <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                <div>Edit {{ $type->name }}</div>
            </div>
            <div class="bg-white p-3 pb-6 rounded-b">
                <form method="POST" action="{{ route('storage.types.update', $type->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="field-group">
                        <label for="name">Name:</label>
                        <div class="field">
                            <input type="text" name="name" id="name" value="{{ old('name', $type->name) }}" class="{{ $errors->has('name') ? 'danger' : '' }}" required>
                            {!! $errors->first('name', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="field-group">
                        <label for="description">Description:</label>
                        <div class="field">
                            <input type="text" name="description" id="description" value="{{ old('description', $type->description) }}" class="{{ $errors->has('description') ? 'danger' : '' }}" required>
                            {!! $errors->first('description', '<span class="danger">:message</span>') !!}
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
                        <delete-confirm-button label="Delete" :data-set="{{ $type }}" classes="btn" path="/storage/types" class="">
                            <div slot="title">Are You Sure?</div>  
                            Are you sure you want to delete this storage type? This action is not undoable.
                        </delete-confirm-button>
                        <div class="ml-auto flex items-center">
                            <a href="{{ route('storage.types.index') }}" class="mr-3">Cancel</a>
                            <button type="submit" class="btn is-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
