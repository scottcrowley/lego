@extends('layouts.app')

@section('content')
<div class="w-full sm:w-3/4 lg:w-1/2 px-2 sm:px-0 max-w-md md:mx-auto">
    <div class="rounded shadow">
        <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            {{ __('Login') }}
        </div>
        <div class="bg-white p-3 rounded-b">
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="field-group">
                    <label for="email" class="">{{ __('E-Mail Address') }}</label>
                    <div class="field">
                        <input id="email" type="email" class="{{ $errors->has('email') ? 'border-error-dark' : 'border-secondary-light' }}" name="email" value="{{ old('email') }}" required autofocus>
                        {!! $errors->first('email', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                    </div>
                </div>

                <div class="field-group">
                    <label for="password" class="">{{ __('Password') }}</label>
                    <div class="field">
                        <input id="password" type="password" class="{{ $errors->has('password') ? 'border-error-dark' : 'border-secondary-light' }}" name="password" required>
                        {!! $errors->first('password', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                    </div>
                </div>

                <div class="field-group">
                    <label class="flex items-center">
                        <span class="text-sm text-secondary-dark mr-2"> {{ __('Remember Me') }}</span>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    </label>
                </div>

                <div class="field-group flex">
                    <div class="mt-3">
                        <button type="submit" class="btn is-primary">
                            {{ __('Login') }}
                        </button>
                        <a class="block sm:inline mt-2 sm:mt-0 no-underline hover:underline text-primary-darker text-sm" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
