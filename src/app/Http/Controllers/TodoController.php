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
        $categories = Category::all();

        $category_id = $request->category_id;
        $keyword     = $request->keyword;
        $start_date  = $request->start_date;
        $end_date    = $request->end_date;
        $sort        = $request->input('sort', 'created_at'); // 並び替え用

        $query = Todo::with('category');

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        if (!empty($keyword)) {
            $query->where('content', 'like', "%{$keyword}%");
        }

        // ✅ 修正ポイント： 'due_date' → 'deadline'
        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('deadline', [$start_date, $end_date]);
        } elseif (!empty($start_date)) {
            $query->where('deadline', '>=', $start_date);
        } elseif (!empty($end_date)) {
            $query->where('deadline', '<=', $end_date);
        }

        // 並び順（登録順 or 期日順）
        $orderColumn = $sort === 'deadline' ? 'deadline' : 'created_at';
        $query->orderBy($orderColumn, 'asc');

        $todos = $query->get();

        return view('index', compact(
            'todos',
            'categories',
            'category_id',
            'keyword',
            'start_date',
            'end_date',
            'sort'
        ));
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
