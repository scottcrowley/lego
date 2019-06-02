@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div class="flex-1">Lego Sets</div>
            <a href="{{ route('lego.sets.index') }}" class="btn is-outlined is-header-btn text-sm">View as list</a>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-grid 
                label="Sets" 
                image_field="set_img_url" 
                image_label_field="name" 
                per_page="25" 
                :valnames="[
                    {label: 'Set Name', field: 'name', title: true, sortable: true, sorted: false, sortdesc: false},
                    {label: 'Set Number', field: 'set_num', title: false, sortable: true, sorted: false, sortdesc: false},
                    {label: 'Theme', field: 'theme_label', title: false, sortable: false, sorted: false, sortdesc: false},
                    {label: 'Year', field: 'year', title: false, sortable: true, sorted: true, sortdesc: true},
                    {label: 'Pieces', field: 'num_parts', title: false, sortable: true, sorted: false, sortdesc: false},
                    ]"
                endpoint="/api/lego/sets"></data-grid>
        </div>
    </div>
</div>
@endsection