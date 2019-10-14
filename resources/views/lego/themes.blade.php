@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Lego Themes</div>
        </div>
        <div class="bg-white px-8 py-6 rounded-b">
            <data-table 
                label="Themes" 
                :colnames="[
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false, boolean: false, width: 'w-3/5'},
                    {name: 'Parent Themes', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-2/5'}]"
                :valnames="['name', 'parents_label']"
                :allowedparams="['name', 'parent_id']"
                :sort-order="['name']"
                endpoint="{{ route('api.lego.themes') }}"></data-table>
        </div>
    </div>
</div>
@endsection
