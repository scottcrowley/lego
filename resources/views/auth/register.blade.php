@extends('layouts.app')

@section('content')
<div class="flex items-center px-6 md:px-0">
    <div class="w-full max-w-md md:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                Register
            </div>
            <div class="bg-white p-3 rounded-b">
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    <div class="field-group">
                        <label for="username" class="">UserName</label>
                        <div class="field">
                            <input id="username" type="text" class="{{ $errors->has('username') ? 'border-error-dark' : 'border-secondary-light' }}" name="username" value="{{ old('username') }}" required autofocus>
                            {!! $errors->first('username', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="name" class="">Name</label>
                        <div class="field">
                            <input id="name" type="text" class="{{ $errors->has('name') ? 'border-error-dark' : 'border-secondary-light' }}" name="name" value="{{ old('name') }}" required>
                            {!! $errors->first('name', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="email" class="">E-Mail Address</label>
                        <div class="field">
                            <input id="email" type="email" class="{{ $errors->has('email') ? 'border-error-dark' : 'border-secondary-light' }}" name="email" value="{{ old('email') }}" required>
                            {!! $errors->first('email', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="password" class="">Password</label>
                        <div class="field">
                            <input id="password" type="password" class="{{ $errors->has('password') ? 'border-error-dark' : 'border-secondary-light' }}" name="password" required>
                            {!! $errors->first('password', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="password_confirmation" class="">Confirm Password</label>
                        <div class="field">
                            <input id="password_confirmation" type="password" class="{{ $errors->has('password_confirmation') ? 'border-error-dark' : 'border-secondary-light' }}" name="password_confirmation" required>
                            {!! $errors->first('password_confirmation', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group flex">
                        <div class="ml-auto mt-3">
                            <button type="submit" class="btn is-primary">
                                Register
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
