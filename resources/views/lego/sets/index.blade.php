@extends('layouts.app')

@section('title')
    Sets
@endsection

@section('content')
<div class="w-full">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div class="flex-1">Lego Sets</div>
            <a href="{{ route('lego.sets.grid') }}" class="btn is-outlined is-header-btn text-sm">View as grid</a>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-table 
                label="Sets" 
                :colnames="[
                    {name: 'Set Number', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-24'}, 
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false, boolean: false, width: 'w-3/5'}, 
                    {name: 'Theme', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-2/5'},
                    {name: 'Year', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-16'}, 
                    {name: 'Pieces', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-16'}]"
                :valnames="['set_num', 'name', 'theme_label', 'year', 'num_parts']"
                :allowedparams="['name', 'set_num', 'theme_id', 'year', 'minyear', 'maxyear']"
                :sort-order="['-year', 'theme_label', 'name']"
                endpoint="/api/lego/sets"></data-table>
        </div>
    </div>
</div>
@endsection
