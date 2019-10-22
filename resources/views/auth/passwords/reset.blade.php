@extends('layouts.app')

@section('title')
    Reset Password
@endsection

@section('content')
<div class="w-full sm:w-3/4 lg:w-1/2 px-2 sm:px-0 max-w-md md:mx-auto">
    <div class="rounded shadow">
        <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            {{ __('Reset Password') }}
        </div>
        <div class="bg-white p-3 rounded-b">
            <form class="form-horizontal" method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field-group">
                    <label for="email">{{ __('E-Mail Address') }}</label>
                    <div class="field">
                        <input id="email" type="email" class="{{ $errors->has('email') ? 'border-error-dark' : 'border-seondary-light' }}" name="email" value="{{ $email or old('email') }}" required autofocus>
                        {!! $errors->first('email', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                    </div>
                </div>

                <div class="field-group">
                    <label for="password">{{ __('Password') }}</label>
                    <div class="field">
                        <input id="password" type="password" class="{{ $errors->has('password') ? 'border-error-dark' : 'border-seondary-light' }}" name="password" required>
                        {!! $errors->first('password', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                    </div>
                </div>

                <div class="field-group">
                    <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                    <div class="field">
                        <input id="password_confirmation" type="password" class="{{ $errors->has('password_confirmation') ? 'border-error-dark' : 'border-seondary-light' }}" name="password_confirmation" required>
                        {!! $errors->first('password_confirmation', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                    </div>
                </div>

                <div class="field-group flex">
                    <div class="ml-auto mt-3">
                        <button type="submit" class="btn is-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
