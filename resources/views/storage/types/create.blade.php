@extends('layouts.app')

@section('content')
<div class="container flex mx-auto justify-center">
    <div class="w-3/4 lg:w-1/2">
        <div class="rounded shadow">
            <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                <div>Add A New Storage Type</div>
            </div>
            <div class="bg-white p-3 pb-6 rounded-b">
                <form method="POST" action="{{ route('storage.types.store') }}">
                    @include('storage.types._form', ['formType' => 'create'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
