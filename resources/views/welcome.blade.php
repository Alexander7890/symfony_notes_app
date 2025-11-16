@extends('layouts.app')

@section('content')
    <div class="space">
        <h1>Welcome</h1>
        <p><a href="{{ route('contacts.index') }}">Перейти до контактів</a></p>
    </div>
@endsection
