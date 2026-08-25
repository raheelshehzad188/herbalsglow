@extends('admins.master')
@section('theme_settings','is-active')
@section('title','Theme customizer')
@section('page_title','Theme customizer')
@section('page_subtitle', ($schema['theme_name'] ?? ('Theme '.$themeId)).' · '.$store->name)

@section('content')
@if(session('msg'))
    <div class="sa-card" style="margin-bottom:16px">{{ session('msg') }}</div>
@endif

<div class="sa-card" style="margin-bottom:16px">
    <p class="sa-muted">
        Store <strong>{{ $store->name }}</strong> is assigned <strong>{{ $schema['theme_name'] ?? ('Theme '.$themeId) }}</strong>.
        Options below come only from this theme’s <code>settings.json</code>.
        Values are saved for this store (other stores using the same theme keep their own values).
    </p>
    @if(!empty($schema['error']))
        <p style="color:#b42318">{{ $schema['error'] }}@if($schemaPath) ({{ $schemaPath }})@endif</p>
    @elseif($schemaPath)
        <p class="sa-muted">Schema: <code>{{ $schemaPath }}</code></p>
    @endif
</div>

@if(empty($schema['groups']))
    <div class="sa-card">No customizable fields for this theme.</div>
@else
<form method="post" action="{{ url('/admin/theme-settings') }}">
    @csrf
    @foreach($schema['groups'] as $group)
        <div class="sa-card" style="margin-bottom:16px">
            <h3>{{ $group['label'] ?? $group['id'] }}</h3>
            @foreach(($group['fields'] ?? []) as $field)
                @php
                    $key = $field['key'] ?? '';
                    if ($key === '') { continue; }
                    $type = $field['type'] ?? 'boolean';
                    $val = $values[$key] ?? ($field['default'] ?? null);
                @endphp
                <div style="margin:12px 0">
                    @if($type === 'boolean')
                        <input type="hidden" name="ts[{{ $key }}]" value="0">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                            <input type="checkbox" name="ts[{{ $key }}]" value="1" {{ $val ? 'checked' : '' }}>
                            <span>{{ $field['label'] ?? $key }}</span>
                        </label>
                    @elseif($type === 'color')
                        <label>{{ $field['label'] ?? $key }}</label>
                        <input type="color" name="ts[{{ $key }}]" value="{{ $val ?: '#111111' }}" style="display:block;margin-top:6px">
                    @elseif($type === 'select')
                        <label>{{ $field['label'] ?? $key }}</label>
                        <select name="ts[{{ $key }}]" class="form-control" style="max-width:360px;margin-top:6px">
                            @foreach(($field['options'] ?? []) as $optVal => $optLabel)
                                <option value="{{ $optVal }}" {{ (string)$val === (string)$optVal ? 'selected' : '' }}>{{ $optLabel }}</option>
                            @endforeach
                        </select>
                    @else
                        <label>{{ $field['label'] ?? $key }}</label>
                        <input type="text" name="ts[{{ $key }}]" value="{{ $val }}" class="form-control" style="max-width:360px;margin-top:6px">
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
    <button type="submit" class="sa-btn sa-btn-primary">Save theme settings</button>
</form>
@endif
@endsection
