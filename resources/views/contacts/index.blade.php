@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
<div class="row space">
    <h1 style="margin:0;">Contacts</h1>
    <a href="{{ route('contacts.create') }}"><button class="primary">+ New contact</button></a>
</div>
<form method="get" class="row space" style="gap:10px;">
    <input type="text" name="q" placeholder="Search..." value="{{ $q }}" style="flex:1; min-width:180px;">
    <select name="group" style="min-width:160px;">
        <option value="">All groups</option>
        @foreach($groups as $group)
            <option value="{{ $group->id }}" @selected($groupId == $group->id)>{{ $group->name }}</option>
        @endforeach
    </select>
    <select name="perPage" onchange="this.form.submit()">
        @foreach([5,10,20,50] as $size)
            <option value="{{ $size }}" @selected(request('perPage',10)==$size)> {{ $size }} / page </option>
        @endforeach
    </select>
    <button type="submit">Apply</button>
</form>
@if($contacts->isEmpty())
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
        @foreach($contacts as $contact)
            <tr>
                <td>{{ $contact->id }}</td>
                <td>{{ $contact->name }}</td>
                <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                <td>{{ $contact->phone ?? '—' }}</td>
                <td>
                    @if($contact->group)
                        <span class="badge">{{ $contact->group->name }}</span>
                    @else
                        —
                    @endif
                </td>
                <td style="text-align:right;">
                    <a href="{{ route('contacts.edit', $contact) }}"><button type="button">Edit</button></a>
                    <form method="post" action="{{ route('contacts.destroy', $contact) }}" class="inline" onsubmit="return Modal.confirm('Delete this contact?', this);">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="row space" style="justify-content:flex-end;">
    {{ $contacts->links() }}
</div>
@endif
@endsection
