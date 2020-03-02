@extends('layouts.app')

@section('title')
    Verify Email
@endsection

@section('content')
<div class="flex items-center">
    <div class="md:w-1/2 md:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-primary-800 bg-primary-500 p-3 rounded-t">
                {{ __('Verify your Email Address') }}
            </div>
            <div class="bg-white p-3 rounded-b">
                @if (session('resent'))
                    <div class="bg-secondary-200 border border-secondary-400 text-secondary-600 text-sm px-4 py-3 rounded mb-4">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif

                <p class="text-secondary-600">
                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}" class="no-underline hover:underline text-primary-800">{{ __('click here to request another') }}</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
