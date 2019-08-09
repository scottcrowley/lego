@extends('layouts.app')

@section('content')
<div class="w-3/4 lg:w-1/2">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Part Relationships</div>
            {{-- <div class="ml-auto">
                <a href="{{ route('part_relationships.create') }}" class="btn is-small">Add New</a>
            </div> --}}
        </div>
        <div class="bg-white p-3 pb-6 rounded-b">
            @forelse ($partRelationships as $partRelationship)
                <div class="py-2 px-1 border rounded mt-3 flex text-secondary-darker">
                    <span>{{ $partRelationship->child_part_num }}</span>
                    <span>{{ $partRelationship->parent_part_num }}</span>
                </div>
            @empty
                <p>There are currently no Part Relationships in the database.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
