@extends('layouts.app')

@section('title')
    Part Categories
@endsection

@section('content')
<div class="container flex mx-auto justify-center">
    <div class="w-3/4">
        <div class="rounded shadow">
            <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
                <div>Lego Part Categories</div>
            </div>
            <div class="bg-white px-8 py-6 rounded-b">
                <data-table 
                    label="Part Categories" 
                    :colnames="[
                        {name: 'Name', sortable: true, sorted: true, sortDesc: false, boolean: false, width: 'w-3/4'}, 
                        {name: 'Part Count', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-1/4'}]"
                    :valnames="['name', 'part_count']"
                    :allowedparams="['name']"
                    :sort-order="['name']"
                    endpoint="{{ route('api.lego.part_categories') }}"></data-table>
            </div>
        </div>
    </div>
</div>
@endsection
