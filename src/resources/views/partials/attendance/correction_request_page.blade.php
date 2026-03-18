@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/partials/correction_request.css') }}">
@endsection

@section('header-menu')
@include($headerView)
@endsection

@section('content')
@include('partials.attendance.correction_request')
@endsection

@section('js')
<script src="{{ asset('js/common/tab-switch.js') }}"></script>
@endsection
