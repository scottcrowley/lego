@extends('layouts.app')

@section('title')
    Themes
@endsection

@section('content')
<div class="w-3/4">
    <div class="rounded shadow">
        <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
            <div>Lego Themes</div>
        </div>
        <div class="bg-white px-8 py-6 rounded-b">
            <data-table 
                label="Themes" 
                per_page="50" 
                :colnames="[
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false, boolean: false, width: 'w-3/5', link: true, linkUrl: '/lego/sets-grid?theme_label={name}'},
                    {name: 'Parent Themes', sortable: true, sorted: false, sortDesc: false, boolean: false, width: 'w-2/5'}]"
                :valnames="['name', 'parents_label']"
                :allowedparams="['name', 'parent_id']"
                :sort-order="['name']"
                endpoint="{{ route('api.lego.themes') }}"></data-table>
        </div>
    </div>
</div>
@endsection
