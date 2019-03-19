@extends('layouts.app')

@section('content')
<div class="flex items-center w-full">
    <div class="w-full sm:w-3/4 lg:w-1/2 mx-2 sm:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                {{ $user->username }}'s Profile
            </div>
            <div class="bg-white p-3 pb-6 rounded-b">
                <p class="mb-2"><span class="font-bold">Name:</span> {{ $user->name }}</p>
                <p class="mb-2"><span class="font-bold">Member Since:</span> {{ $user->created_at->diffForHumans() }}</p>
                @if (auth()->id() == $user->id)
                    @if (!$user->validCredentials())
                        <p class="text-error-dark p-4 text-center">Valid Rebrickable credentials are missing from the .env file!
                            <br>Please update them before proceeding.</p>
                    @endif
                    <p class="mt-6">
                        <a href="{{ route('profile.edit') }}" class="btn is-primary">Edit Profile</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
