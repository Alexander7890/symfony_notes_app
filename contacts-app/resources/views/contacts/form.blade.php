@extends('layouts.app')

@section('title', $isEdit ? 'Edit contact' : 'New contact')

@section('content')
<a href="{{ route('contacts_index') }}" style="display:inline-block;margin-bottom:16px;">← Back to list</a>
<h2 class="space">{{ $isEdit ? 'Edit contact' : 'Create new contact' }}</h2>
@if($errors->any())
    <div style="background:#fee2e2;padding:12px;border-radius:8px;color:#b91c1c;margin-bottom:18px;">
        <strong>Please fix the following:</strong>
        <ul style="margin:10px 0 0 18px;">
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
            @foreach($groups as $g)
                <option value="{{ $g->id }}" @selected(old('groupId', $item->group_id) == $g->id)>{{ $g->name }}</option>
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
