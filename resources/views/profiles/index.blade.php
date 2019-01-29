@extends('layouts.app')

@section('content')
<div class="flex items-center">
    <div class="md:w-1/2 md:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                {{ $user->username }}'s Profile
            </div>
            <div class="bg-white p-3 pb-6 rounded-b">
                <p class="mb-2"><span class="font-bold">Name:</span> {{ $user->name }}</p>
                <p class="mb-2"><span class="font-bold">Member Since:</span> {{ $user->created_at->diffForHumans() }}</p>
                @if (auth()->id() == $user->id)
                    @if (!$user->validAPI())
                        <p class="text-error-dark p-4 text-center">Your profile is missing a valid Rebrickable API Key!
                            <br>Please add one before proceeding.</p>
                    @else
                        <p class="mb-2"><span class="font-bold">Rebrickable Email:</span> {{ $user->credentials->email }}</p>
                        <p class="mb-2"><span class="font-bold">Rebrickable API Key:</span> {{ $user->credentials->api_key }}</p>
                        <p class="mb-2"><span class="font-bold">Rebrickable Token:</span> {{ $user->credentials->token }}</p>
                    @endif
                    <p class="mt-6">
                        <a href="/profiles/edit" class="btn is-primary">Edit Profile</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
