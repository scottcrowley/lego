@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div class="flex-1">Lego Set Inventories</div>
            <a href="{{ route('lego.inventories.index') }}" class="btn is-outlined is-header-btn text-sm">View as list</a>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-grid 
                label="Inventories" 
                image_field="image_url" 
                image_label_field="name" 
                per_page="50" 
                :valnames="[
                    {label: 'Set Name', field: 'name', title: true, sortable: true, sorted: false, sortdesc: false, boolean: false, link: true, linkUrl: '/lego/inventory-parts-grid/{id}'},
                    {label: 'Set Number', field: 'set_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false, link: false, linkUrl: ''},
                    {label: 'Year', field: 'year', title: false, sortable: true, sorted: true, sortdesc: true, boolean: false, link: false, linkUrl: ''},
                    {label: 'Pieces', field: 'num_parts', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false, link: false, linkUrl: ''},
                    ]"
                :allowedparams="['name', 'set_num', 'theme_id', 'year', 'minyear', 'maxyear']"
                endpoint="/api/lego/inventories"></data-grid>
        </div>
    </div>
</div>
@endsection