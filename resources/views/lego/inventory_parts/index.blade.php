@extends('layouts.app')

@section('content')
<div class="w-full">
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
            <a href="{{ route('lego.inventory_parts.grid', $inventory->id) }}" class="btn is-outlined is-header-btn text-sm">View as grid</a>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-table 
                label="Parts" 
                :colnames="[
                    {name: 'Part Number', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-32'}, 
                    {name: 'Name', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-1/2'}, 
                    {name: 'Qty', sortable: false, sorted: false, sortDesc: false, boolean: false, width: 'w-12'},
                    {name: 'Color', sortable: true, sorted: true, sortDesc: false, boolean: false, width: 'w-32'}, 
                    {name: 'Location', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-2/5'}, 
                    {name: 'Spare', sortable: true, sorted: false, sortDesc: false, boolean: true, width: 'w-12'}]"
                :valnames="['part_num', 'name', 'quantity', 'color_name', 'location_name', 'is_spare']"
                :allowedparams="['name', 'part_num', 'part_category_id', 'color_id', 'color']"
                endpoint="{{ '/api/lego/inventory_parts/'.$inventory->id }}"></data-table>
        </div>
    </div>
</div>
@endsection
