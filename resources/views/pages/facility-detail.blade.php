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
                        <li class="breadcrumb-item"><a href="{{ route('facilities') }}">{{ __('menu.facilities') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section id="content">
        <div class="content-wrap">
            <div class="container">
                <div class="row">
                    @if ($facility->image)
                        <div class="col-lg-6 mb-4">
                            <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $pageTitle }}"
                                class="img-fluid rounded">
                        </div>
                    @endif

                    <div class="col-lg-{{ $facility->image ? '6' : '12' }}">
                        <div class="heading-block">
                            <h2>{{ $pageTitle }}</h2>
                        </div>

                        <div class="facility-content">
                            {!! $facility->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
