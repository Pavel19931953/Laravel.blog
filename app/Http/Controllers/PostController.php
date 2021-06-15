<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()//отвечает показ главной страницы и создаем вид
    {//получаем категории 
        $posts = Post::with('category')->orderBy('id', 'desc')->paginate(2);
        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {//мы должны получить пост если он есть а если нет 404
        $post = Post::where('slug', $slug)->firstOrFail();//если не получили пост ошибка 404
        $post->views += 1;//счетчик просмотров увеличиваетс на единицу после просмотра
        $post->update();//и обновляем
        return view('posts.show', compact('post'));

}

}
