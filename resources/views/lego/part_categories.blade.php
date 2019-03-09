@extends('layouts.app')

@section('content')
<div class="container flex mx-auto justify-center">
    <div class="w-3/4">
        <div class="rounded shadow">
            <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                <div>Lego Part Categories</div>
            </div>
            <div class="bg-white px-8 py-6 rounded-b">
                <data-table 
                    label="Part Categories" 
                    :colnames="[
                        {name: 'Name', sortable: true, sorted: true, sortDesc: false}, 
                        {name: 'Part Count', sortable: true, sorted: false, sortDesc: false}]"
                    :valnames="['name', 'part_count']" 
                    endpoint="/api/lego/part_categories"></data-table>
            </div>
        </div>
    </div>
</div>
@endsection
