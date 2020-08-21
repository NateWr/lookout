<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('lookout:process', function () {
    $dirs = Storage::directories('scans');
    foreach ($dirs as $dir) {
        $publicationSlug = str_replace('scans/', '', $dir);
        $publication = DB::table('publications')
            ->where('slug', '=', $publicationSlug)
            ->first();
        $scans = Storage::files($dir);
        foreach ($scans as $scan) {
            $articles = json_decode(Storage::get($scan));

            // Normalize date format
            $articles = array_map(
                function($article) {
                    $time = strtotime($article->date);
                    if (!$time) {
                        $time = time();
                    }
                    $article->date = date('Y-m-d H:i:s', $time);
                    return $article;
                },
                $articles
            );

            // Create the publication if it doesn't exist
            if (!$publication) {
                DB::table('publications')->insert([
                    'slug' => $publicationSlug,
                    'name' => $articles[0]->publication,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $publication = DB::table('publications')
                    ->where('slug', '=', $publicationSlug)
                    ->first();
                if (!$publication) {
                    Log::error('Articles located in ' . $dir . ' could not be indexed because no matching publication was found and a new publication could not be created.');
                    return;
                }
            }

            // Exclude articles that have already been indexed
            $articles = array_filter($articles, function($article) use ($publication) {
                return !DB::table('articles')
                    ->where('publication_id', '=', $publication->id)
                    ->whereDate('date', substr($article->date, 0, 10))
                    ->where('url', '=', $article->url)
                    ->where('author', '=', $article->author)
                    ->first();
            });

            // Prepare for inserting to the database
            $rows = array_map(
                function($article) use ($publication) {
                    $time = strtotime($article->date);
                    if (!$time) {
                        $time = time();
                    }
                    return [
                        'title' => $article->title,
                        'url' => $article->url,
                        'date' => date('Y-m-d H:i:s', $time),
                        'date_text' => $article->dateText,
                        'author' => $article->author,
                        'description' => $article->description,
                        'available' => $article->available ?? true,
                        'publication_id' => $publication->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'retrieved_at' => date('Y-m-d H:i:s', round($article->retrieved_at / 1000)),
                    ];
                },
                $articles
            );

            DB::table('articles')->insert($rows);
        }

        Storage::deleteDirectory($dir);
    }
})->describe('Process scans and save new publications.');
