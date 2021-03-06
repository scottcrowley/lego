@extends('layouts.app')

@section('title')
    Edit Location: {{ $location->nickname ?: $location->name }}
@endsection

@section('content')
<div class="w-3/4 lg:w-1/2">
    <div class="rounded shadow">
        <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
            <div>Edit {{ $location->name }}</div>
        </div>
        <div class="bg-white p-3 pb-6 rounded-b">
            <form method="POST" action="{{ route('storage.locations.update', $location->id) }}">
                @method('PATCH')
                @include('storage.locations._form', ['formType' => 'edit'])
            </form>
        </div>
    </div>
</div>
@endsection
