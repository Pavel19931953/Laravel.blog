<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();//получаем категорию или 404 ошибка
        $posts = $category->posts()->orderBy('id', 'desc')->paginate(2);
        return view('categories.show', compact('category', 'posts'));
    }

}