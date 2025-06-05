@extends('layouts.app')

@section('title', $pageTitle)
@section('content')

    <section class="page-title page-title-mini">
        <div class="container">
            <div class="page-title-row">
                <div class="page-title-content">
                    <h1>{{ $pageTitle }}</h1>
                </div>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('facilities.home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>
    <br>
    <div class="container">
        <div class="post-grid row col-mb-30">
            @forelse ($news as $item)
                <div class="entry col-lg-4 col-md-6">
                    <div class="grid-inner shadow-sm card rounded-5">
                        @if ($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                alt="{{ $item->getTranslation('title', app()->getLocale()) ?? 'News Image' }}"
                                class="card-img-top"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                        @else
                            <img src="{{ asset('images/placeholder.jpg') }}" alt="No image available" class="card-img-top">
                        @endif
                        <div class="p-4">
                            <div class="entry-title">
                                <h5 class="fw-medium text-danger mb-3">
                                    {{ $item->category ? $item->category->name : 'Berita' }}
                                </h5>
                                <h2 class="text-transform-none ls-0 h5">
                                    <a
                                        href="{{ $item->category_id == 3
                                            ? route('announcements.detail', ['slug' => Str::slug($item->getTranslation('title', app()->getLocale()))])
                                            : route('news.detail', ['slug' => Str::slug($item->getTranslation('title', app()->getLocale()))]) }}">
                                        {{ $item->getTranslation('title', app()->getLocale()) ?? 'Untitled' }}
                                    </a>
                                </h2>
                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li>
                                        <i class="uil uil-schedule"></i>
                                        {{ \Carbon\Carbon::parse($item->published_at)->translatedFormat('d F Y') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No news available at this time.
                    </div>
                </div>
            @endforelse
        </div>

        @if (isset($news) && count($news) > 0 && method_exists($news, 'links'))
            <div class="pagination-container mt-5 mb-0">
                {{ $news->links() }}
            </div>
        @endif
    </div>

    <section id="content">
    @endsection
