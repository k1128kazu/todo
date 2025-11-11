<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\Category;

class TodoController extends Controller
{
    /**
     * 一覧表示（登録順／期日順の切り替え対応）
     */
    public function index(Request $request)
    {
        // 並び順の指定（デフォルト：登録順）
        $sort = $request->get('sort', 'created_at');

        // 並び替え条件に応じて取得
        if ($sort === 'deadline') {
            $todos = Todo::with('category')->orderBy('deadline', 'asc')->get();
        } else {
            $todos = Todo::with('category')->orderBy('created_at', 'desc')->get();
        }

        // カテゴリ一覧を取得
        $categories = Category::all();

        return view('index', compact('todos', 'categories', 'sort'));
    }

    /**
     * 新規登録（期日必須）
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'deadline' => 'required|date', // ✅ 期日を必須に
        ]);

        Todo::create([
            'content' => $request->content,
            'category_id' => $request->category_id,
            'deadline' => $request->deadline,
        ]);

        return redirect('/')->with('message', 'ToDoを登録しました');
    }

    /**
     * 更新処理（期日変更対応）
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:todos,id',
            'content' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'deadline' => 'required|date', // ✅ 期日も更新可能
        ]);

        $todo = $request->only(['content', 'category_id', 'deadline']);
        Todo::find($request->id)->update($todo);

        return redirect('/')->with('message', 'ToDoを更新しました');
    }

    /**
     * 削除処理
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:todos,id',
        ]);

        Todo::find($request->id)->delete();

        return redirect('/')->with('message', 'ToDoを削除しました');
    }

    /**
     * ✅ 期日範囲検索（オプション機能）
     * deadline_from, deadline_to を使って絞り込み
     */
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'deadline_from' => 'nullable|date',
            'deadline_to' => 'nullable|date|after_or_equal:deadline_from',
            'sort' => 'nullable|in:created_at,deadline',
        ]);

        $sort = $request->get('sort', 'created_at');

        $query = Todo::with('category');

        if ($request->filled('keyword')) {
            $query->where('content', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('deadline_from') && $request->filled('deadline_to')) {
            $query->whereBetween('deadline', [$request->deadline_from, $request->deadline_to]);
        } elseif ($request->filled('deadline_from')) {
            $query->where('deadline', '>=', $request->deadline_from);
        } elseif ($request->filled('deadline_to')) {
            $query->where('deadline', '<=', $request->deadline_to);
        }

        if ($sort === 'deadline') {
            $query->orderBy('deadline', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $todos = $query->get();
        $categories = Category::all();

        return view('index', compact('todos', 'categories', 'sort'));
    }
}
