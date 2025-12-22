<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::published()
            ->with(['author', 'relatedAward'])
            ->latest('published_at');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        return Inertia::render('News/Index', [
            'news' => $query->paginate(12)->withQueryString(),
            'filters' => $request->only(['search', 'category'])
        ]);
    }

    public function show(string $slug)
    {
        $article = News::published()
            ->with(['author', 'relatedAward'])
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('News/Show', [
            'article' => $article
        ]);
    }
}
