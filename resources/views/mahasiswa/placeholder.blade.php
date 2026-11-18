@extends('layouts.mahasiswa')

@section('title', $title)
@section('menu', $menu)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/find-opportunity.css') }}">
@endpush

@section('content')
    <div class="page-content">
        <div class="empty-state">
            <div>✦</div>
            <h3>Halaman {{ $title }}</h3>
            <p>Halaman ini belum dikonversi ke Laravel.</p>
        </div>
    </div>
@endsection
