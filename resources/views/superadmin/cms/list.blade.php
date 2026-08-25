@extends('superadmin.layout')
@section('title', $label)
@section('page_title', $label)
@section('page_subtitle', 'Edit what customers see on the public SaaS site')
@section('nav_'.$type, 'is-active')
@section('page_actions')
    <a class="sa-btn sa-btn-primary" href="{{ route('superadmin.cms.create', $type) }}">Add</a>
@endsection
@section('content')
<div class="sa-card">
    <table class="sa-table">
        <thead>
            <tr>
                @foreach($columns as $col)
                    <th>{{ $col }}</th>
                @endforeach
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                @foreach($columns as $field => $col)
                    <td>
                        @if($field === 'status')
                            {{ $item->status ? 'Active' : 'Hidden' }}
                        @elseif($field === 'highlight')
                            {{ $item->highlight ? 'Yes' : 'No' }}
                        @else
                            {{ $item->{$field} }}
                        @endif
                    </td>
                @endforeach
                <td style="white-space:nowrap">
                    <a class="sa-btn sa-btn-secondary" href="{{ route('superadmin.cms.edit', [$type, $item->id]) }}">Edit</a>
                    <form method="post" action="{{ route('superadmin.cms.delete', [$type, $item->id]) }}" style="display:inline" onsubmit="return confirm('Delete this item?')">
                        @csrf
                        <button class="sa-btn sa-btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="{{ count($columns)+1 }}">Nothing here yet. Click Add.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
