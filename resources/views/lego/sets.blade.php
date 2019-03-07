@extends('layouts.app')

@section('content')
<div class="container flex mx-auto justify-center">
    <div class="w-3/4">
        <div class="rounded shadow">
            <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                <div>Lego Sets</div>
            </div>
            <div class="bg-white p-3 pb-6 rounded-b">
                <table-sets></table-sets>
            </div>
        </div>
    </div>
</div>
@endsection
