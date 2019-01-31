@extends('layouts.app')

@section('content')
<div class="flex items-center flex-col">
    <div class="md:w-1/2 md:mx-auto">
        <div class="rounded shadow">
            <div class="font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                Rebrickable Credentials
            </div>
            <form method="POST" action="{{ route('credentials.update') }}">
                @csrf
                @method('PATCH')
                <div class="bg-white p-3 pb-6 rounded-b">
                    <div class="field-group">
                        <label for="email">Email:</label>
                        <div class="field">
                            <input type="email" name="email" id="email" value="{{ old('email', $user->credentials->email) }}" required>
                            {!! $errors->first('email', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="password">Password:</label>
                        <div class="field">
                            <input type="password" name="password" id="password" value="{{ old('password', $user->credentials->password) }}" required>
                            {!! $errors->first('password', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="api_key">API Key:</label>
                        <div class="field">
                            <input type="text" name="api_key" id="api_key" value="{{ old('api_key', $user->credentials->api_key) }}" required>
                            {!! $errors->first('api_key', '<span class="danger">:message</span>') !!}
                        </div>
                    </div>

                    @if (count($errors))
                        <div class="field-group">
                            <ul class="danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="field-group">
                        <div class="ml-auto">
                            <a href="/profiles" class="mr-3">Cancel</a>
                            <button type="submit" class="btn is-primary">Update Credentials</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
