@extends('layouts.community')

@section('title', 'Las noticias')
@php($activeNav = 'community')

@section('subnav')
    @include('partials.navi2', ['section' => 'community', 'active' => 'noticias'])
@endsection

@section('content')
    <div id="column1" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix default">
                <h2 class="title">News</h2>
                <div id="article-archive">
                    <h2>Las noticias</h2>
                    <ul>
                        @forelse($latest as $item)
                            <li><a href="/news?id={{ $item->num }}">{{ $item->title }}</a> &raquo;</li>
                        @empty
                            <li>Sin noticias</li>
                        @endforelse
                    </ul>
                    <h2>&iquest;M&aacute;s noticias?</h2>
                    <ul>
                        <li><a href="/news" class="article">Ver todas las noticias</a> &raquo;</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="column2" class="column">
        <div class="habblet-container">
            <div class="cbb clearfix notitle">
                @if($article)
                    <div id="article-wrapper">
                        <h2>{{ $article->title }}</h2>
                        <div class="article-meta">
                            Publicado el {{ $article->date }}
                            <a href="/news?category={{ urlencode($article->category) }}">{{ $article->category }}</a>
                        </div>
                        <p class="summary">{!! nl2br(e($article->short_story)) !!}</p>
                        <div class="article-body">
                            <p>{!! nl2br($article->story) !!}</p>
                            <div class="article-body">
                                <a href="/profile?name={{ urlencode($article->author) }}" target="_self">
                                    <img src="/web-gallery/album1/users_online.PNG" alt="User Profile" border="0" />
                                </a>
                                <b>{{ $article->author }}</b>
                            </div>
                        </div>
                    </div>
                @else
                    <div id="article-wrapper">
                        <h2>{{ $category ? 'Categoría' : $shortname.' News' }}</h2>
                        <div class="article-meta">
                            @if($category)
                                Estas son las últimas 25 noticias de la categoría <b>{{ $category }}</b>.
                            @else
                                Aquí están las noticias, de la más reciente a la más antigua.
                            @endif
                        </div>
                        <div class="article-body">
                            <ul>
                                @forelse($archive as $item)
                                    <li>{{ $item->date }} - <a href="/news?id={{ $item->num }}">{{ $item->title }}</a></li>
                                @empty
                                    <li>Sin noticias.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
