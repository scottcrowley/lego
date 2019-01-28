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

                    <div class="flex items-stretch mb-3">
                        <label for="username" class="text-right font-semibold text-secondary-dark text-sm pt-2 pr-3 align-middle w-1/4">UserName</label>
                        <div class="flex flex-col w-3/4">
                            <input id="username" type="text" class="flex-grow h-8 px-2 border rounded {{ $errors->has('username') ? 'border-error-dark' : 'border-secondary-light' }}" name="username" value="{{ old('username') }}" required autofocus>
                            {!! $errors->first('username', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="flex items-stretch mb-3">
                        <label for="name" class="text-right font-semibold text-secondary-dark text-sm pt-2 pr-3 align-middle w-1/4">Name</label>
                        <div class="flex flex-col w-3/4">
                            <input id="name" type="text" class="flex-grow h-8 px-2 border rounded {{ $errors->has('name') ? 'border-error-dark' : 'border-secondary-light' }}" name="name" value="{{ old('name') }}" required>
                            {!! $errors->first('name', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="flex items-stretch mb-3">
                        <label for="email" class="text-right font-semibold text-secondary-dark text-sm pt-2 pr-3 align-middle w-1/4">E-Mail Address</label>
                        <div class="flex flex-col w-3/4">
                            <input id="email" type="email" class="flex-grow h-8 px-2 border rounded {{ $errors->has('email') ? 'border-error-dark' : 'border-secondary-light' }}" name="email" value="{{ old('email') }}" required>
                            {!! $errors->first('email', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="flex items-stretch mb-4">
                        <label for="password" class="text-right font-semibold text-secondary-dark text-sm pt-2 pr-3 align-middle w-1/4">Password</label>
                        <div class="flex flex-col w-3/4">
                            <input id="password" type="password" class="flex-grow h-8 px-2 rounded border {{ $errors->has('password') ? 'border-error-dark' : 'border-secondary-light' }}" name="password" required>
                            {!! $errors->first('password', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="flex items-stretch mb-4">
                        <label for="password_confirmation" class="text-right font-semibold text-secondary-dark text-sm pt-2 pr-3 align-middle w-1/4">Confirm Password</label>
                        <div class="flex flex-col w-3/4">
                            <input id="password_confirmation" type="password" class="flex-grow h-8 px-2 rounded border {{ $errors->has('password_confirmation') ? 'border-error-dark' : 'border-secondary-light' }}" name="password_confirmation" required>
                            {!! $errors->first('password_confirmation', '<span class="text-error-dark text-sm mt-2">:message</span>') !!}
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-3/4 ml-auto">
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold py-2 px-4 rounded mr-3">
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
