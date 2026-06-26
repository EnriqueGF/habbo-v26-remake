@extends('layouts.community')

@section('title', 'Ayuda')
@php($activeNav = '')

@section('content')
    <div id="faq" class="clearfix">
        <div id="faq-header" class="clearfix">
            <img src="/web-gallery/v2/images/faq/faq_header.png" alt="FAQ" />
            <form method="get" action="/help" class="search-box">
                <input type="text" id="faq-search" name="query" class="search-box-query search-box-onfocus" size="50"
                       value="{{ $query ?? 'Buscar...' }}" />
                <input type="submit" value="" title="Buscar" class="search" />
            </form>
        </div>

        <div id="faq-container" class="clearfix">
            <div id="faq-category-list">
                <ul class="faq">
                    @forelse($categories as $cat)
                        <li>
                            <a href="/help?id={{ $cat->id }}" name="">
                                <span class="faq-link">{{ $cat->title }}</span>
                            </a>
                        </li>
                    @empty
                        <li><span class="faq-link">No hay categor&iacute;as de ayuda disponibles.</span></li>
                    @endforelse
                </ul>
            </div>

            <div id="faq-category-content" class="clearfix">
                @if($results !== null)
                    {{-- Resultados de búsqueda --}}
                    @forelse($results as $item)
                        <h4 id="faq-item-header-{{ $item->id }}" class="faq-item-header faq-toggle">
                            <span class="faq-toggle" id="faq-header-text-{{ $item->id }}">{{ $item->title }}</span>
                        </h4>
                        <div id="faq-item-content-{{ $item->id }}" class="faq-item-content clearfix">
                            <div class="faq-item-content clearfix">{!! $item->content !!}</div>
                        </div>
                    @empty
                        <p class="faq-category-description">No se encontraron resultados. Busca de nuevo.</p>
                    @endforelse
                @elseif($category !== null)
                    {{-- Categoría seleccionada y sus preguntas --}}
                    @if(! empty($category->content))
                        <p class="faq-category-description">{!! $category->content !!}</p>
                    @endif
                    @forelse($items as $item)
                        <h4 id="faq-item-header-{{ $item->id }}" class="faq-item-header faq-toggle">
                            <span class="faq-toggle" id="faq-header-text-{{ $item->id }}">{{ $item->title }}</span>
                        </h4>
                        <div id="faq-item-content-{{ $item->id }}" class="faq-item-content clearfix">
                            <div class="faq-item-content clearfix">{!! $item->content !!}</div>
                        </div>
                    @empty
                        <p class="faq-category-description">Esta categor&iacute;a no tiene preguntas todav&iacute;a.</p>
                    @endforelse
                @else
                    {{-- Sin selección: invita a elegir una categoría --}}
                    <p class="faq-category-description">
                        Selecciona una categor&iacute;a de la izquierda o utiliza el buscador para encontrar ayuda.
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
