@extends('layouts.app')

@section('content')
<div class="w-3/4 lg:w-1/2">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Themes</div>
            {{-- <div class="ml-auto">
                <a href="{{ route('themes.create') }}" class="btn is-small">Add New</a>
            </div> --}}
        </div>
        <div class="bg-white p-3 pb-6 rounded-b">
            @forelse ($themes as $theme)
                <div class="py-2 px-1 border rounded mt-3 flex text-secondary-darker">
                    <span>{{ $theme->name }}</span>
                    {{-- <a href="{{ route('storage.themes.edit', $theme->id) }}" class="ml-auto text-sm">edit</a> --}}
                </div>
            @empty
                <p>There are currently no Themes in the database.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
