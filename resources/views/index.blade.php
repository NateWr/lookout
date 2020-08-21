<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <style type="text/css">
            ul {
                margin: 0;
                padding: 0;
                list-style: none;
            }

            li + li {
                margin-top: 2rem;
            }

            h2 {
                font-size: 1.2rem;
                line-height: 1.3em;
                margin-bottom: 0;
            }

            h2 a {
                text-decoration: none;
                color: #3b414a;
            }

            .by {
                margin-top: 0.25rem;
                font-size: 0.9rem;
                line-height: 1.5em;
            }

            .desc {
                margin-top: 1rem;
                font-size: 1.1rem;
                line-height: 1.5em;
            }

            .desc .message-body-actions /* Knight 1st Amendment Institute */
            {
                display: none;
            }
        </style>

    </head>
    <body>
        <h1>Articles</h1>
        <ul>
            @foreach ($articles as $article)
                <li>
                    <h2>
                        <a href="{{ $article->url }}">
                            {{ $article->title }}
                        </a>
                    </h2>
                    <p class="by">
                        <span>{{ $publications[$article->publication_id] }}</span>
                        /
                        <span>{{ $article->author }}</span>
                    </p>
                    <div class="desc">{!! $article->description !!}</div>
                </li>
            @endforeach
        </ul>
    </body>
</html>
