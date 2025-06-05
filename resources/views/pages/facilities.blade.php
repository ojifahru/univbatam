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

    <!-- Content
                      ============================================= -->
    <section id="content">
        <div class="content-wrap">
            <div class="container">
                <div class="fancy-title title-border">
                    <h2>{{ __('facilities.all_facilities') }}</h2>
                </div>

                <div class="row col-mb-50">
                    @forelse($facilities as $facility)
                        <div class="col-lg-4 col-md-6">
                            <div class="card">
                                @if ($facility->image)
                                    <img src="{{ asset('storage/' . $facility->image) }}"
                                        alt="{{ $facility->getTranslation('name', app()->getLocale(), false) }}"
                                        class="card-img-top">
                                @endif
                                <div class="card-body">
                                    <h4 class="card-title">
                                        {{ $facility->getTranslation('name', app()->getLocale(), false) }}</h4>
                                    <div class="card-text mb-3">
                                        {{ Str::limit(strip_tags($facility->getTranslation('description', app()->getLocale(), false)), 100) }}
                                    </div>

                                    @php
                                        // Get slug from current locale, fallback to any locale if not available
                                        $slug = $facility->getTranslation('slug', app()->getLocale(), false);

                                        if (empty($slug)) {
                                            // If slug is empty in current locale, try to get it from any locale
                                            foreach (
                                                LaravelLocalization::getSupportedLocales()
                                                as $localeCode => $properties
                                            ) {
                                                $tempSlug = $facility->getTranslation('slug', $localeCode, false);
                                                if (!empty($tempSlug)) {
                                                    $slug = $tempSlug;
                                                    break;
                                                }
                                            }
                                        }

                                        // If still empty, use the ID as fallback
                                        if (empty($slug)) {
                                            $slug = $facility->id;
                                        }
                                    @endphp

                                    <a href="{{ route('facility.detail', ['slug' => $slug]) }}"
                                        class="button button-small button-rounded">
                                        {{ __('facilities.read_more') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                {{ __('facilities.no_facilities') }}
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section><!-- #content end -->
@endsection
