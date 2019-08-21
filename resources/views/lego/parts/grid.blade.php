@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div class="flex-1">Lego Parts</div>
            <a href="{{ route('lego.parts.index') }}" class="btn is-outlined is-header-btn text-sm">View as list</a>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-grid 
                label="Parts" 
                image_field="image_url" 
                image_label_field="name" 
                per_page="25" 
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id']"
                endpoint="/api/lego/parts"></data-grid>
        </div>
    </div>
</div>
@endsection
