@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="flex mb-3 mb-8 mx-auto" style="width: 90%;">
        <div class="w-3/5">
            <img src="{{ $inventory->set->image_url }}" alt="{{ $inventory->set->name }}" class="shadow-lg rounded-lg">
        </div>
        <div class="flex-1 ml-3 pt-6">
            <p class="title text-2xl mb-4">{{ $inventory->set->name }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Set Number:</span> {{ $inventory->set->set_num }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Theme:</span> {{ $inventory->set->theme->theme_label }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Year:</span> {{ $inventory->set->year }}</p>
            <p class="font-hairline tracking-wide"><span class="font-semibold">Pieces:</span> {{ $inventory->set->num_parts }}</p>
        </div>
    </div>
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div class="flex-1">Lego Set Inventory Parts</div>
            <a href="{{ route('lego.inventory_parts.index', $inventory->id) }}" class="btn is-outlined is-header-btn text-sm">View as list</a>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-grid 
                label="Parts" 
                image_field="ldraw_image_url" 
                image_label_field="image_url" 
                per_page="50" 
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Quantity', field: 'quantity', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Color', field: 'color_name', title: false, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Location', field: 'location_name', title: false, sortable: true, sorted: false, sortdesc: false, boolean: true},
                    {label: 'Spare', field: 'is_spare', title: false, sortable: true, sorted: false, sortdesc: false, boolean: true},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id', 'color_id', 'color']"
                endpoint="{{ '/api/lego/inventory_parts/'.$inventory->id }}"></data-grid>
        </div>
    </div>
</div>
@endsection