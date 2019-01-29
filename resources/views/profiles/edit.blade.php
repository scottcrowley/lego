@extends('layouts.app')

@section('content')
<div class="flex items-center">
    <div class="md:w-1/2 md:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                Edit {{ $user->username }}'s Profile
            </div>
            <form method="POST" action="{{ route('profiles.update') }}">
                @csrf
                @method('PATCH')
                <div class="bg-white p-3 pb-6 rounded-b">
                    <div class="field-group">
                        <label for="username">Username:</label>
                        <div class="field">
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required>
                            {!! $errors->first('username', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="name">Name:</label>
                        <div class="field">
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                            {!! $errors->first('name', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="email">Email:</label>
                        <div class="field">
                            <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                            {!! $errors->first('email', '<span class="danger">:message</span>') !!}
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
                    
                    <div class="field-group">
                        <div class="ml-auto">
                            <a href="/profiles" class="mr-3">Cancel</a>
                            <button type="submit" class="btn is-primary">Update Profile</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection