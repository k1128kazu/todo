@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection

@section('content')
<div class="todo__alert">
    @if (session('message'))
    <div class="todo__alert--success">{{ session('message') }}</div>
    @endif

    @if ($errors->any())
    <div class="todo__alert--danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

<div class="todo__content">
    <div class="section__title">
        <h2>新規作成</h2>
    </div>

    <!-- ▼▼ 期日追加済み 新規作成フォーム ▼▼ -->
    <form class="create-form" action="/todos" method="post">
        @csrf
        <div class="create-form__item">
            <input
                class="create-form__item-input"
                type="text"
                name="content"
                value="{{ old('content') }}"
                placeholder="ToDo内容を入力"
                required />

            <select class="create-form__item-select" name="category_id" required>
                @foreach ($categories as $category)
                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>

            <!-- ✅ 期日入力欄を追加 -->
            <input
                class="create-form__item-input"
                type="date"
                name="deadline"
                value="{{ old('deadline') }}"
                required />
        </div>

        <div class="create-form__button">
            <button class="create-form__button-submit" type="submit">作成</button>
        </div>
    </form>
    <!-- ▲▲ 新規作成フォームここまで ▲▲ -->


    <div class="section__title">
        <h2>Todo検索</h2>
    </div>

    <form class="search-form" action="/todos/search" method="get">
        <div class="search-form__item">
            <input class="search-form__item-input" type="text" name="keyword" value="{{ request('keyword') }}">
            <select class="search-form__item-select" name="category_id">
                <option value="">カテゴリ</option>
                @foreach ($categories as $category)
                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="search-form__button">
            <button class="search-form__button-submit" type="submit">検索</button>
        </div>
    </form>
    <!-- ▼ Todo一覧タイトル -->
    <div class="section__title">
        <h2>Todo一覧</h2>
    </div>
    <!-- ✅ 並び替えフォームを追加 -->
    <div class="section__title">
        <h2>並び順</h2>
    </div>
    <form action="/" method="GET" class="sort-form">
        <label for="sort">並び順：</label>
        <select name="sort" id="sort" class="sort-form__select" onchange="this.form.submit()">
            <option value="created_at" @if($sort=='created_at' ) selected @endif>登録順</option>
            <option value="deadline" @if($sort=='deadline' ) selected @endif>期日順</option>
        </select>
    </form>
    <div class="todo-table">
        <table class="todo-table__inner">
            <thead>
                <tr class="todo-table__row">
                    <th class="todo-table__header">Todo</th>
                    <th class="todo-table__header">カテゴリ</th>
                    <th class="todo-table__header">期日</th>
                    <th class="todo-table__header">操作</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($todos as $todo)
                <tr class="todo-table__row">
                    <form class="update-form" action="/todos/update" method="post">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="id" value="{{ $todo->id }}">

                        <!-- Todo内容（直接編集可能） -->
                        <td class="todo-table__item">
                            <input
                                class="update-form__item-input"
                                type="text"
                                name="content"
                                value="{{ $todo->content }}"
                                required>
                        </td>

                        <!-- カテゴリ選択（編集可能） -->
                        <td class="todo-table__item">
                            <select class="update-form__item-select" name="category_id" required>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @if ($todo->category_id == $category->id) selected @endif>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </td>

                        <!-- 期日編集 -->
                        <td class="todo-table__item">
                            <input
                                class="update-form__item-input"
                                type="date"
                                name="deadline"
                                value="{{ $todo->deadline ? $todo->deadline->format('Y-m-d') : '' }}"
                                required>
                        </td>

                        <!-- 更新・削除ボタン -->
                        <td class="todo-table__item">
                            <button class="update-form__button-submit" type="submit">更新</button>
                    </form>

                    <form class="delete-form" action="/todos/delete" method="post" style="display:inline;">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="id" value="{{ $todo->id }}">
                        <button class="delete-form__button-submit" type="submit">削除</button>
                    </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection