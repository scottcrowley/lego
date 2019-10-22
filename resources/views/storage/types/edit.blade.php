@extends('layouts.app')

@section('title')
    Edit Location Type: {{ $type->name }}
@endsection

@section('content')
<div class="w-3/4 lg:w-1/2">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Edit {{ $type->name }}</div>
        </div>
        <div class="bg-white p-3 pb-6 rounded-b">
            <form method="POST" action="{{ route('storage.types.update', $type->id) }}">
                @method('PATCH')
                @include('storage.types._form', ['formType' => 'edit'])
            </form>
        </div>
    </div>
</div>
@endsection
