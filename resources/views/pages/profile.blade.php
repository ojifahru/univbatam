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
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('home.home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                    </ol>
                </nav>

            </div>
        </div>
    </section>

    <!-- Content -->
    <section id="content">
        <div class="content-wrap">
            <!--====== ABOUT PART START ======-->
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="nav canvas-tabs tabs nav-tabs flex-column mb-3" id="canvas-side-tab" role="tablist"
                            data-animate="fadeInLeft">

                            @foreach ($aboutUsEntries as $index => $entry)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                        id="canvas-{{ $entry->slug }}-tab-side" data-bs-toggle="pill"
                                        data-bs-target="#{{ $entry->slug }}-side" type="button" role="tab"
                                        aria-controls="canvas-{{ $entry->slug }}-side"
                                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ $entry->key }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <div id="canvas-side-tab-content" class="tab-content" data-animate="fadeInUp">
                            @foreach ($aboutUsEntries as $index => $entry)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                    id="{{ $entry->slug }}-side" role="tabpanel"
                                    aria-labelledby="canvas-{{ $entry->slug }}-tab-side" tabindex="0">
                                    {!! $entry->value !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!--====== ABOUT PART END ======-->
        </div>
    </section><!-- #content end -->
@endsection
