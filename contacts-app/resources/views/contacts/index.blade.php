@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
<div class="row space">
    <h1 style="margin:0">Contacts</h1>
    <a href="{{ route('contacts_new') }}">
        <button type="button" class="primary">+ New contact</button>
    </a>
</div>
<form method="get" class="row space" style="gap:10px;align-items:flex-end;">
    <div style="flex:1; min-width:200px;">
        <label style="display:block;margin-bottom:4px;">Search</label>
        <input type="text" name="q" placeholder="Name, email, phone" value="{{ $q }}">
    </div>
    <div>
        <label style="display:block;margin-bottom:4px;">Group</label>
        <select name="group" style="min-width:160px;">
            <option value="">All groups</option>
            @foreach($groups as $g)
                <option value="{{ $g->id }}" @selected($groupId == $g->id)>{{ $g->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label style="display:block;margin-bottom:4px;">Per page</label>
        <select name="perPage" onchange="this.form.submit()">
            @foreach([5,10,20,50] as $n)
                <option value="{{ $n }}" @selected($perPage == $n)>{{ $n }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <button type="submit" class="primary">Apply</button>
    </div>
</form>
@if($total === 0)
    <p>No contacts found.</p>
@else
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Group</th>
            <th style="width:150px;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($contacts as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->name }}</td>
                <td><a href="mailto:{{ $c->email }}">{{ $c->email }}</a></td>
                <td>{{ $c->phone ?? '—' }}</td>
                <td>
                    @if($c->group)
                        <span class="badge">{{ $c->group->name }}</span>
                    @else
                        —
                    @endif
                </td>
                <td>
                    <a href="{{ route('contacts_edit', ['id' => $c->id]) }}">
                        <button type="button">Edit</button>
                    </a>
                    <form method="post" action="{{ route('contacts_delete', ['id' => $c->id]) }}" class="inline"
                          onsubmit="return Modal.confirm('Delete this contact?', this);">
                        @csrf
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row space" style="justify-content:flex-end;">
        <div>
            Page {{ $page }} of {{ $totalPages }}
            @if($page > 1)
                <a href="{{ route('contacts_index', array_merge(request()->query(), ['page' => $page-1])) }}">Prev</a>
            @endif
            @if($page < $totalPages)
                <a href="{{ route('contacts_index', array_merge(request()->query(), ['page' => $page+1])) }}">Next</a>
            @endif
        </div>
    </div>
@endif
@endsection
