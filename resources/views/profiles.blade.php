@extends('layouts.app')

@section('content')
<div class="flex items-center">
    <div class="md:w-1/2 md:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                {{ str_plural($user->name) }} Profile
            </div>
            <div class="bg-white p-3 rounded-b">
                <p class="text-grey-dark">
                    
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
