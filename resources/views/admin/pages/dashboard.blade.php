@extends('admin.layout2')

@push('header')

@endpush

@section('title')
    Dashboard
@endsection
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    dashboard
@endsection