@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div class="flex-1">Lego Sets</div>
            <a href="{{ route('lego.sets.grid') }}" class="btn is-outlined is-header-btn text-sm">View as grid</a>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-table 
                label="Sets" 
                :colnames="[
                    {name: 'Set Number', sortable: true, sorted: false, sortDesc: false, boolean: false}, 
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false, boolean: false}, 
                    {name: 'Theme', sortable: false, sorted: false, sortDesc: false, boolean: false},
                    {name: 'Year', sortable: true, sorted: false, sortDesc: false, boolean: false}, 
                    {name: 'Pieces', sortable: true, sorted: false, sortDesc: false, boolean: false}]"
                :valnames="['set_num', 'name', 'theme_label', 'year', 'num_parts']"
                :allowedparams="['name', 'set_num', 'theme_id', 'year', 'beforeyear', 'afteryear']"
                endpoint="/api/lego/sets"></data-table>
        </div>
    </div>
</div>
@endsection
