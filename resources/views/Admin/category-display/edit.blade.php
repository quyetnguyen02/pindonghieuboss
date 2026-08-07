@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Cấu Hình Hiển Thị Danh Mục</h1>
            <p class="text-muted small mb-0">Chọn danh mục nào được hiển thị trên trang chủ.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">← Quay lại Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.category-display.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3 row gy-2">
                    @foreach($categories as $category)
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-check form-check-inline w-100">
                                <input class="form-check-input" type="checkbox"
                                       name="show_categories[]"
                                       value="{{ $category->id }}"
                                       {{ $category->show_on_homepage ? 'checked' : '' }}>
                                <span class="form-check-label">{{ $category->name }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
