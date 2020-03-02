@extends('layouts.app')

@section('title')
    Login
@endsection

@section('content')
<div class="w-full sm:w-3/4 lg:w-1/2 px-2 sm:px-0 max-w-md md:mx-auto">
    <div class="rounded shadow">
        <div class="font-medium text-lg text-primary-700 bg-primary-500 p-3 rounded-t">
            {{ __('Login') }}
        </div>
        <div class="bg-white p-3 rounded-b">
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="field-group">
                    <label for="email" class="">{{ __('E-Mail Address') }}</label>
                    <div class="field">
                        <input id="email" type="email" class="{{ $errors->has('email') ? 'border-error-600' : 'border-secondary-400' }}" name="email" value="{{ old('email') }}" required autofocus>
                        {!! $errors->first('email', '<span class="text-error-600 text-sm mt-2">:message</span>') !!}
                    </div>
                </div>

                <div class="field-group">
                    <label for="password" class="">{{ __('Password') }}</label>
                    <div class="field">
                        <input id="password" type="password" class="{{ $errors->has('password') ? 'border-error-600' : 'border-secondary-400' }}" name="password" required>
                        {!! $errors->first('password', '<span class="text-error-600 text-sm mt-2">:message</span>') !!}
                    </div>
                </div>

                <div class="field-group">
                    <label class="flex items-center">
                        <span class="text-sm text-secondary-600 mr-2"> {{ __('Remember Me') }}</span>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    </label>
                </div>

                <div class="field-group flex">
                    <div class="mt-3">
                        <button type="submit" class="btn is-primary">
                            {{ __('Login') }}
                        </button>
                        <a class="block sm:inline mt-2 sm:mt-0 no-underline hover:underline text-primary-700 text-sm" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
