@extends('layouts.app')

@section('title', 'privacy-poicy')
@section('content')
    <section class="page-title page-title-mini">
        <div class="container">
            <div class="page-title-row">

                <div class="page-title-content">
                    <h1>Privacy Policy</h1>
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
    <section id="content">
        <div class="content-wrap">
            <div class="container">

                <div class="row gx-5 col-mb-80">
                    <aside class="sidebar col-lg-12">
                        {!! $procedure->value !!}

                    </aside><!-- .sidebar end -->
                </div>
            </div>
        </div>
    </section><!-- #content end -->
@endsection
