@extends('layouts.app')

@section('title')
    Forgot Password
@endsection

@section('content')
<div class="w-full sm:w-3/4 lg:w-1/2 px-2 sm:px-0 max-w-md md:mx-auto">
    <div class="rounded shadow">
        <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            {{ __('Reset Password') }}
        </div>
        <div class="bg-white p-3 rounded-b">
            @if (session('status'))
                <div class="bg-secondary-lightest border border-secondary-light text-secondary-dark text-sm px-4 py-3 rounded mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                <div class="field-group">
                    <label for="email">{{ __('E-Mail Address') }}</label>
                    <div class="field">
                        <input id="email" type="email" class="{{ $errors->has('email') ? 'border-error-dark' : 'border-secondary-light' }}" name="email" value="{{ old('email') }}" required autofocus>
                        {!! $errors->first('email', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                    </div>
                </div>

                <div class="field-group flex">
                    <div class="ml-auto mt-3">
                        <button type="submit" class="btn is-primary">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

