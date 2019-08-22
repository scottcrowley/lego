@extends('layouts.app')

@section('content')
<div class="w-full">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div class="flex-1">Lego Set Inventories</div>
            <a href="{{ route('lego.inventories.grid') }}" class="btn is-outlined is-header-btn text-sm">View as grid</a>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-table 
                label="Inventories" 
                :colnames="[
                    {name: 'Set Number', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-24'}, 
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false, boolean: false, width: 'w-3/5'}, 
                    {name: 'Theme', sortable: false, sorted: false, sortDesc: false, boolean: false, width: 'w-2/5'},
                    {name: 'Year', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-16'}, 
                    {name: 'Pieces', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-16'}]"
                :valnames="['set_num', 'name', 'theme_label', 'year', 'num_parts']"
                :allowedparams="['name', 'set_num', 'theme_id', 'year', 'minyear', 'maxyear']"
                endpoint="/api/lego/inventories"></data-table>
        </div>
    </div>
</div>
@endsection
