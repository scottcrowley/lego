@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Lego Parts</div>
        </div>
        <div class="bg-white p-4 py-6 rounded-b">
            <data-table 
                label="Parts" 
                :colnames="[
                    {name: 'Part Number', sortable: true, sorted: false, sortDesc: false, boolean: false}, 
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false, boolean: false}, 
                    {name: 'Category', sortable: false, sorted: false, sortDesc: false, boolean: false}]"
                :valnames="['part_num', 'name', 'category_label']"
                :allowedparams="['name', 'part_num', 'part_category_id']"
                endpoint="/api/lego/parts"></data-table>
        </div>
    </div>
</div>
@endsection
