@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="w-2/3 mb-3 mb-8 mx-auto border rounded-lg p-6 bg-white shadow-md">
        <div>
            <p class="title text-2xl mb-4">{{ $location->name }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Nickname:</span> {{ $location->nickname ?: 'None' }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Type:</span> {{ $location->type->name }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Parts in Location:</span> {{ $location->parts->count() }}</p>
        </div>
    </div>
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Storage Location Parts</div>
        </div>
        <div class="bg-white p-3 pb-6 rounded-b">
            
        </div>
    </div>
</div>
@endsection
