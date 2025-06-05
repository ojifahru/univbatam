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
                        <li class="breadcrumb-item">
                            @if ($isAnnouncement)
                                <a href="{{ route('announcements') }}">{{ __('menu.announcements') }}</a>
                            @else
                                <a href="{{ route('news') }}">{{ __('menu.news') }}</a>
                            @endif
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="content-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        @if ($news->image)
                            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $pageTitle }}"
                                class="card-img-top rounded-top-4"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                        @endif
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center mb-4">
                                <span class="badge bg-danger me-2">{{ $news->category->name ?? 'Berita' }}</span>
                                <span class="text-muted"><i
                                        class="uil uil-schedule me-1"></i>{{ \Carbon\Carbon::parse($news->published_at)->translatedFormat('d F Y') }}</span>
                            </div>

                            <div class="content-body">
                                {!! $news->getTranslation('content', app()->getLocale()) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- You can add sidebar content here like recent news, categories, etc. -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body">
                            <h4 class="card-title">{{ __('menu.recent_news') }}</h4>
                            <!-- Add recent news listing here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
