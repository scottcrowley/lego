@extends('layouts.app')

@section('title')
    Parts
@endsection

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="rounded shadow">
        <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
            <div class="flex-1">Lego Parts</div>
            <a href="{{ route('lego.parts.index') }}" class="btn is-outlined is-header-btn text-sm">View as list</a>
        </div>
        <div class="bg-white px-4 pb-6 rounded-b">
            <data-grid-with-filters 
                label="Parts" 
                image_field="image_url" 
                image_label_field="name" 
                per_page="100" 
                :filters="[
                    {label: 'Name', param: 'name', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Part Number', param: 'part_num', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Category', param: 'category_label', type: 'text', classes: 'flex-1 ml-3'},
                    ]"
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Quantity Owned', field: 'owned_part_count', title: false, sortable: false, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Storage Location', field: 'owned_part_location_name', title: false, sortable: false, sorted: false, sortdesc: false, boolean: false},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id', 'category_label']"
                :sort-order="['name']"
                endpoint="{{ route('api.lego.parts') }}"></data-grid-with-filters>
        </div>
    </div>
</div>
@endsection
