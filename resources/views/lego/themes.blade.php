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
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false},
                    {name: 'Parent Themes', sortable: false, sorted: false, sortDesc: false}]"
                :valnames="['name', 'parents_label']" 
                endpoint="/api/lego/themes"></data-table>
        </div>
    </div>
</div>
@endsection