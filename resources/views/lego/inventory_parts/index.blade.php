@extends('layouts.app')

@section('content')
<div class="w-full">
    <div class="flex bg-white border mb-8 md:mx-auto md:p-6 md:w-2/3 p-3 rounded-lg shadow-md w-full" style="width: 95%;">
        <div class="w-auto max-w-md">
            <img src="{{ $inventory->set->image_url }}" alt="{{ $inventory->set->name }}">
        </div>
        <div class="flex-1 ml-5 pt-6">
            <div class="flex flex-col h-full">
                <div>
                    <p class="title text-md md:text-2xl mb-4">{{ $inventory->set->name }}</p>
                    <p class="text-sm md:text-base mb-2 font-hairline tracking-wide"><span class="font-semibold">Set Number:</span> {{ $inventory->set->set_num }}</p>
                    <p class="text-sm md:text-base mb-2 font-hairline tracking-wide"><span class="font-semibold">Theme:</span> {{ $inventory->set->theme->theme_label }}</p>
                    <p class="text-sm md:text-base mb-2 font-hairline tracking-wide"><span class="font-semibold">Year:</span> {{ $inventory->set->year }}</p>
                    <p class="text-sm md:text-base font-hairline tracking-wide"><span class="font-semibold">Pieces:</span> {{ $inventory->set->num_parts }}</p>
                </div>
                <div class="mt-auto self-end">
                    <a href="{{ url()->previous() }}" class="btn is-primary px-3 py-1 text-xs">Go Back to Inventories</a>
                </div>
            </div>
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
                    {name: 'Name', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-3/5'}, 
                    {name: 'Qty', sortable: false, sorted: false, sortDesc: false, boolean: false, width: 'w-12'},
                    {name: 'Color', sortable: true, sorted: true, sortDesc: false, boolean: false, width: 'w-32'}, 
                    {name: 'Location', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-1/4'}, 
                    {name: 'Spare', sortable: true, sorted: false, sortDesc: false, boolean: true, width: 'w-12'}]"
                :valnames="['part_num', 'name', 'quantity', 'color_name', 'location_name', 'is_spare']"
                :allowedparams="['name', 'part_num', 'part_category_id', 'color_id', 'color']"
                :sort-order="['location_name', 'name', 'color_name']"
                endpoint="{{ route('api.lego.inventory_parts', $inventory->id) }}"></data-table>
        </div>
    </div>
</div>
@endsection
