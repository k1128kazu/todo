@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>ユーザー登録</h2>

    @if ($errors->any())
    <div class="auth-error">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/register" method="POST">
        @csrf

        <div class="auth-item">
            <label>名前</label>
            <input type="text" name="name" required autofocus>
        </div>

        <div class="auth-item">
            <label>メールアドレス</label>
            <input type="email" name="email" required>
        </div>

        <div class="auth-item">
            <label>パスワード</label>
            <input type="password" name="password" required>
        </div>

        <div class="auth-item">
            <label>パスワード確認</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="auth-btn">登録</button>
    </form>

    <p>
        <a href="/login">ログインはこちら</a>
    </p>
</div>
@endsection