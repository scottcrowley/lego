@extends('layouts.app')

@section('title')
    Your Parts
@endsection

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="rounded shadow">
        <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
            <div>Your Lego Parts - Individual</div>
        </div>
        <div class="bg-white px-4 pb-6 rounded-b">
            <data-grid-with-filters 
                label="Parts" 
                image_field="ldraw_image_url" 
                image_label_field="image_url"
                per_page="100"
                :allow_image_swap=true
                :filters="[
                    {label: 'Name', param: 'name', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Part Number', param: 'part_num', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Category', param: 'category_label', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Storage Location', param: 'location_name', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Color', param: 'color', type: 'text', classes: 'flex-1 ml-3'},
                    ]"
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Color', field: 'color_name', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Quantity', field: 'quantity', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Location', field: 'location_name', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id', 'location_id', 'color', 'location_name', 'category_label']"
                :sort-order="['name']"
                endpoint="{{ route('api.users.parts.individual') }}"></data-grid-with-filters>
        </div>
    </div>
</div>
@endsection
