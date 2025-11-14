<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Todo;
use App\Models\Category;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // 全機能ログイン必須
    }

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
        $sort        = $request->input('sort', 'created_at');

        // ▼▼ ログインユーザーのToDoだけ取得 ▼▼
        $query = Todo::with('category')
            ->where('user_id', Auth::id());

        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }

        if (!empty($keyword)) {
            $query->where('content', 'like', "%{$keyword}%");
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('deadline', [$start_date, $end_date]);
        } elseif (!empty($start_date)) {
            $query->where('deadline', '>=', $start_date);
        } elseif (!empty($end_date)) {
            $query->where('deadline', '<=', $end_date);
        }

        $orderColumn = $sort === 'deadline' ? 'deadline' : 'created_at';
        $query->orderBy($orderColumn, 'asc');

        $todos = $query->paginate(10)->withQueryString();

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
            'deadline' => 'required|date',
        ]);

        Todo::create([
            'content' => $request->content,
            'category_id' => $request->category_id,
            'deadline' => $request->deadline,
            'user_id' => Auth::id(), // ★ログインユーザーに紐付け
        ]);

        return redirect('/')->with('message', 'ToDoを登録しました');
    }

    /**
     * 更新処理（ログインユーザーのToDoのみ）
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:todos,id',
            'content' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'deadline' => 'required|date',
        ]);

        // ★自分のToDoだけ更新可能
        $todo = Todo::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $todo->update([
            'content' => $request->content,
            'category_id' => $request->category_id,
            'deadline' => $request->deadline,
        ]);

        return redirect('/')->with('message', 'ToDoを更新しました');
    }

    /**
     * 削除処理（自分のToDoのみ）
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:todos,id',
        ]);

        // ★自分のToDoだけ削除可能
        $todo = Todo::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $todo->delete();

        return redirect('/')->with('message', 'ToDoを削除しました');
    }
}
