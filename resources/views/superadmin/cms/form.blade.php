@extends('superadmin.layout')
@section('title', ($item ? 'Edit' : 'Add').' '.$label)
@section('page_title', ($item ? 'Edit' : 'Add').' '.$label)
@section('nav_'.$type, 'is-active')
@section('page_actions')
    <a class="sa-btn sa-btn-secondary" href="{{ route('superadmin.cms.'.$type) }}">Back</a>
@endsection
@section('content')
<form method="post" action="{{ $item ? route('superadmin.cms.update', [$type, $item->id]) : route('superadmin.cms.store', $type) }}" class="sa-card">
    @csrf
    @foreach($fields as $name => $meta)
        <div class="sa-field">
            @if(($meta['type'] ?? 'text') === 'checkbox')
                <label class="sa-check">
                    <input type="checkbox" name="{{ $name }}" value="1" @if(old($name, $item->{$name} ?? true)) checked @endif>
                    {{ $meta['label'] }}
                </label>
            @elseif(($meta['type'] ?? '') === 'textarea')
                <label>{{ $meta['label'] }}</label>
                <textarea name="{{ $name }}" rows="6">{{ old($name, $item->{$name} ?? '') }}</textarea>
            @else
                <label>{{ $meta['label'] }}</label>
                <input type="{{ $meta['type'] ?? 'text' }}" name="{{ $name }}" value="{{ old($name, $item->{$name} ?? ($name === 'sort' ? 0 : '')) }}">
            @endif
        </div>
    @endforeach
    <button class="sa-btn sa-btn-primary" type="submit">Save</button>
</form>
@endsection
