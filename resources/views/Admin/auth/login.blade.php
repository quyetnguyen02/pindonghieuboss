@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height:60vh;">
    <div class="card shadow-sm" style="max-width:420px; width:100%;">
        <div class="card-body">
            <h4 class="mb-3">Đăng nhập quản trị</h4>

            @if($errors->any())
                <div class="alert alert-danger small">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small">Tên đăng nhập</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="form-control form-control-sm" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label small">Mật khẩu</label>
                    <input type="password" name="password" class="form-control form-control-sm" required>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary">Đăng nhập</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
