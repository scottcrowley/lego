@extends('layouts.app')

@section('title')
    Inventory: {{ $inventory->set->set_num }} - {{ $inventory->set->name }}
@endsection

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="flex flex-col lg:flex-row bg-white border mb-8 mx-auto md:p-6 md:w-2/3 p-3 rounded-lg shadow-md w-full" style="width: 95%;">
        <div class="w-auto max-w-md">
            <img src="{{ $inventory->set->image_url }}" alt="{{ $inventory->set->name }}" class="block mx-auto lg:mx-0">
        </div>
        <div class="flex-1 m-0 lg:ml-5 pt-6">
            <div class="flex flex-col h-full">
                <div>
                    <p class="title text-md md:text-2xl mb-4">{{ $inventory->set->name }}</p>
                    <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Set Number:</span> {{ $inventory->set->set_num }}</p>
                    <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Theme:</span> {{ $inventory->set->theme->theme_label }}</p>
                    <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Year:</span> {{ $inventory->set->year }}</p>
                    <p class="text-sm md:text-base font-normal tracking-wide"><span class="font-semibold">Pieces:</span> {{ $inventory->set->num_parts }}</p>
                </div>
                <div class="mt-auto self-end">
                    <a href="{{ route('lego.inventory_parts.stickered', $inventory->id) }}" class="btn is-primary px-3 py-1 text-xs">Manage Stickered Parts</a>
                    <a href="{{ url()->previous() }}" class="btn is-primary px-3 py-1 text-xs">Go Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="rounded shadow">
        <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
            <div class="flex-1">Lego Set Inventory Parts</div>
            <a href="{{ route('lego.inventory_parts.index', $inventory->id) }}" class="btn is-outlined is-header-btn text-sm">View as list</a>
        </div>
        <div class="bg-white px-4 pb-6 rounded-b">
            <data-grid-inventory-parts 
                label="Parts" 
                image_field="ldraw_image_url" 
                image_label_field="image_url" 
                per_page="100" 
                :allow_image_swap=true
                selection_get_endpoint="{{ route('api.lego.inventory_parts.selection.get', $inventory->id) }}"
                selection_update_endpoint="{{ route('api.lego.inventory_parts.selection.update') }}"
                :filters="[
                    {label: 'Name', param: 'name', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Part Number', param: 'part_num', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Color', param: 'color', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Category', param: 'category_label', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Location', param: 'location_name', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Exclude Spare Parts', param: 'exclude_spare', type: 'checkbox', value: 1, classes: 'ml-3', defaultvalue: true},
                    ]"
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Quantity', field: 'quantity_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Color', field: 'color_name', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Location', field: 'location_name', title: false, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Spare', field: 'is_spare', title: false, sortable: true, sorted: false, sortdesc: false, boolean: true},
                    {label: 'Stickered', field: 'stickered_parts_count', title: false, sortable: false, sorted: false, sortdesc: false, boolean: false},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id', 'color_id', 'color', 'category_label', 'exclude_spare', 'location_name']"
                :sort-order="['location_name', 'name', 'color_name']"
                endpoint="{{ route('api.lego.inventory_parts', $inventory->id) }}"></data-grid-inventory-parts>
        </div>
    </div>
</div>
@endsection
