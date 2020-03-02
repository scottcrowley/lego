@extends('layouts.app')

@section('title')
    Colors
@endsection

@section('content')
<div class="w-3/4">
    <div class="rounded shadow">
        <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
            <div>Lego Colors</div>
        </div>
        <div class="bg-white px-8 py-6 rounded-b">
            <data-table 
                label="Colors" 
                :colnames="[
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false, boolean: false, width: 'w-1/2'}, 
                    {name: 'RGB Value', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-1/4'}, 
                    {name: 'Transparent', sortable: true, sorted: false, sortDesc: false, boolean: true, width: 'w-1/4'}]"
                :valnames="['name', 'rgb', 'is_trans']" 
                :allowedparams="['name']"
                :sort-order="['name']"
                endpoint="{{ route('api.lego.colors') }}"></data-table>
        </div>
    </div>
</div>
@endsection
