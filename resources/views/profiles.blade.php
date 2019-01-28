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
                <p class=""><span class="font-bold">Member Since:</span> {{ $user->created_at->diffForHumans() }}</p>
                @if (auth()->id() == $user->id)
                    <p class="mt-6">
                        <a href="/profiles/edit" class="btn is-primary">Edit Profile</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
