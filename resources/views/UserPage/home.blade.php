<!-- resources/views/home.blade.php -->
@extends('UserPage.layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <!-- Main content + Sidebar -->
    <div class="main-wrapper" style="padding: 20px">
        @include('UserPage.layouts.banner')
    </div>

    @foreach($categoryListProducts as $category_id)
        @include('UserPage.layouts.product')
    @endforeach
@endsection
@push('scripts')
    @vite('resources/js/UserPage/home.js')
@endpush
