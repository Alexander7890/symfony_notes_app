@extends('layouts.app')

@section('title', $isEdit ? 'Edit contact' : 'New contact')

@section('content')
<a href="{{ route('contacts.index') }}"><button type="button">← Back</button></a>
<h2 class="space">{{ $isEdit ? 'Edit contact' : 'Create new contact' }}</h2>
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="post">
    @csrf
    <div class="space">
        <label>Name *</label>
        <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
    </div>
    <div class="space">
        <label>Email *</label>
        <input type="email" name="email" value="{{ old('email', $item->email) }}" required>
    </div>
    <div class="space">
        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $item->phone) }}">
    </div>
    <div class="space">
        <label>Group</label>
        <select name="groupId">
            <option value="">No group</option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}" @selected(old('groupId', optional($item->group)->id) == $group->id)>{{ $group->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="space">
        <label>Note</label>
        <textarea name="note" rows="4">{{ old('note', $item->note) }}</textarea>
    </div>
    <button type="submit" class="primary">{{ $isEdit ? 'Save changes' : 'Create' }}</button>
</form>
@endsection
