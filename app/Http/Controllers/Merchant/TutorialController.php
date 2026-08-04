<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use Inertia\Inertia;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = Tutorial::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($t) {
                return [
                    'id' => $t->id,
                    'title' => $t->title,
                    'category' => $t->category,
                    'youtube_url' => $t->youtube_url,
                    'youtube_id' => $t->youtube_id,
                    'embed_url' => $t->embed_url,
                    'description' => $t->description,
                    'duration' => $t->duration ?: 'فيديو تعليمي',
                ];
            });

        $categories = array_values(array_unique(array_merge(['الكل'], $tutorials->pluck('category')->toArray())));

        return Inertia::render('Merchant/Tutorials/Index', [
            'tutorials' => $tutorials,
            'categories' => $categories,
        ]);
    }
}
