@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Lego Colors</div>
        </div>
        <div class="bg-white px-8 py-6 rounded-b">
            <data-table 
                label="Colors" 
                :colnames="[
                    {name: 'Name', sortable: true, sorted: true, sortDesc: false}, 
                    {name: 'RGB Value', sortable: true, sorted: false, sortDesc: false}, 
                    {name: 'Transparenet', sortable: true, sorted: false, sortDesc: false}]"
                :valnames="['name', 'rgb', 'is_trans']" 
                endpoint="/api/lego/colors"></data-table>
        </div>
    </div>
</div>
@endsection