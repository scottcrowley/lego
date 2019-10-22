@extends('layouts.app')

@section('title')
    Edit Your Profile
@endsection

@section('content')
<div class="flex items-center flex-col">
    <div class="w-3/4 lg:w-1/2 mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                Edit {{ $user->username }}'s Profile
            </div>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')
                <div class="bg-white p-3 pb-6 rounded-b">
                    <div class="field-group">
                        <label for="username">Username:</label>
                        <div class="field">
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="{{ $errors->has('username') ? 'danger' : '' }}" required>
                            {!! $errors->first('username', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="name">Name:</label>
                        <div class="field">
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="{{ $errors->has('name') ? 'danger' : '' }}" required>
                            {!! $errors->first('name', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="email">Email:</label>
                        <div class="field">
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="{{ $errors->has('email') ? 'danger' : '' }}" required>
                            {!! $errors->first('email', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="current_password">Current Password:</label>
                        <div class="field">
                            <input type="password" name="current_password" id="current_password" value="" class="{{ $errors->has('current_password') ? 'danger' : '' }}">
                            {!! $errors->first('current_password', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="password">New Password:</label>
                        <div class="field">
                            <input type="password" name="password" id="password" value="" class="{{ $errors->has('password') ? 'danger' : '' }}">
                            {!! $errors->first('password', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="password_confirmation">Confirm New Password:</label>
                        <div class="field">
                            <input type="password" name="password_confirmation" id="password_confirmation" value="" class="{{ $errors->has('password_confirmation') ? 'danger' : '' }}">
                            {!! $errors->first('password_confirmation', '<span class="danger">:message</span>') !!}
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
                    
                    <div class="field-group mt-6">
                        <div class="ml-auto items-center">
                            <a href="{{ route('profile') }}" class="mr-3">Cancel</a>
                            <button type="submit" class="btn is-primary">Update Profile</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
