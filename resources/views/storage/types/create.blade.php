@extends('layouts.app')

@section('content')
<div class="flex items-center">
    <div class="md:w-1/2 md:mx-auto">
        <div class="rounded shadow">
            <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                <div>Add A New Storage Type</div>
            </div>
            <div class="bg-white p-3 pb-6 rounded-b">
                <form method="POST" action="{{ route('storage.types.store') }}">
                    @csrf
                    <div class="bg-white p-3 pb-6 rounded-b">
                        <div class="field-group">
                            <label for="name">Name:</label>
                            <div class="field">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                                {!! $errors->first('name', '<span class="danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="field-group">
                            <label for="description">Description:</label>
                            <div class="field">
                                <input type="text" name="description" id="description" value="{{ old('description') }}" required>
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
                        
                        <div class="field-group flex">
                            <div class="ml-auto mt-3">
                                <a href="{{ route('storage.types.index') }}" class="mr-3">Cancel</a>
                                <button type="submit" class="btn is-primary">Add Type</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
