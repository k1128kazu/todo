@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>ログイン</h2>

    @if ($errors->any())
    <div class="auth-error">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="/login" method="POST">
        @csrf

        <div class="auth-item">
            <label>メールアドレス</label>
            <input type="email" name="email" required autofocus>
        </div>

        <div class="auth-item">
            <label>パスワード</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="auth-btn">ログイン</button>
    </form>

    <p>
        <a href="/register">新規ユーザー登録はこちら</a>
    </p>
</div>
@endsection