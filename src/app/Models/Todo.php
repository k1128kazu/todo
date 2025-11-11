<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    // 🔽 モデルで一括代入可能なカラムを定義
    protected $fillable = [
        'content',
        'category_id',
        'deadline',  // ✅ 期日を追加
    ];

    // 🔽 カラムの型キャスト
    protected $casts = [
        'deadline' => 'date', // Carbonオブジェクトとして扱えるように
    ];

    // 🔽 カテゴリとのリレーション（既存）
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // 🔽 スコープ: カテゴリ検索
    public function scopeCategorySearch($query, $category_id)
    {
        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }
    }

    // 🔽 スコープ: キーワード検索
    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('content', 'like', "%{$keyword}%");
        }
    }

    // ✅ スコープ: 期日範囲検索（開始・終了日指定）
    public function scopeDeadlineRange($query, $from, $to)
    {
        if (!empty($from) && !empty($to)) {
            $query->whereBetween('deadline', [$from, $to]);
        } elseif (!empty($from)) {
            $query->whereDate('deadline', '>=', $from);
        } elseif (!empty($to)) {
            $query->whereDate('deadline', '<=', $to);
        }
    }

    // ✅ スコープ: 並び替え（登録順 or 期日順）
    public function scopeSortBy($query, $sort)
    {
        if ($sort === 'deadline') {
            return $query->orderBy('deadline', 'asc');
        }
        return $query->orderBy('created_at', 'desc');
    }
}
