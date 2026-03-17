@extends('layouts.auth')

@section('title', '로그인 - ' . config('app.name'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h5 class="card-title mb-4">로그인</h5>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">이메일</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">비밀번호</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                               required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
                        <label for="remember" class="form-check-label">로그인 유지</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">로그인</button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-secondary">Google로 로그인</a>
                </div>

                <p class="mt-4 mb-0 text-center text-muted small">
                    계정이 없으신가요? <a href="{{ route('register') }}">회원가입</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
