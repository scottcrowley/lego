@extends('layouts.app')

@section('title')
    Sets
@endsection

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div class="flex-1">Lego Sets</div>
            <a href="{{ route('lego.sets.index') }}" class="btn is-outlined is-header-btn text-sm">View as list</a>
        </div>
        <div class="bg-white px-4 pb-6 rounded-b">
            <data-grid-with-filters 
                label="Sets" 
                image_field="image_url" 
                image_label_field="name" 
                per_page="50" 
                :filters="[
                    {label: 'Name', param: 'name', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Set Number', param: 'part_num', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Theme', param: 'theme_label', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Year', param: 'year', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Minimum Year', param: 'minyear', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Maximum Year', param: 'maxyear', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Minimum Pieces', param: 'minpieces', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Maximum Pieces', param: 'maxpieces', type: 'text', classes: 'flex-1 ml-3'},
                    ]"
                :valnames="[
                    {label: 'Set Name', field: 'name', title: true, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Set Number', field: 'set_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Theme', field: 'theme_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Year', field: 'year', title: false, sortable: true, sorted: true, sortdesc: true, boolean: false},
                    {label: 'Pieces', field: 'num_parts', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    ]"
                :allowedparams="['name', 'set_num', 'theme_id', 'year', 'minyear', 'maxyear', 'theme_label', 'minpieces', 'maxpieces']"
                :sort-order="['-year', 'theme_label', 'name']"
                endpoint="/api/lego/sets"></data-grid-with-filters>
        </div>
    </div>
</div>
@endsection
