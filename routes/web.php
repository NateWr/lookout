<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    $articles = DB::table('articles')
        ->orderBy('retrieved_at', 'desc')
        ->orderBy('id', 'asc')
        ->limit(100)
        ->get();

    $publications = [];
    foreach (DB::table('publications')->get() as $publication) {
        $publications[$publication->id] = $publication->name;
    }

    return view('index', [
        'articles' => $articles,
        'publications' => $publications,
    ]);
});
