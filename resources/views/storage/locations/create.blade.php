@extends('layouts.app')

@section('content')
<div class="w-3/4 lg:w-1/2">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>{{ (! is_null($location->name)) ? 'Copy '.$location->name : 'Add A New Storage Location' }}</div>
        </div>
        <div class="bg-white p-3 pb-6 rounded-b">
            <form method="POST" action="{{ route('storage.locations.store') }}">
                @include('storage.locations._form', ['formType' => 'create'])
            </form>
        </div>
    </div>
</div>
@endsection
