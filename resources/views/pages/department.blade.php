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
                        <li class="breadcrumb-item">{{ $department->faculty->name }}</li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $department->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section id="content">
        <div class="content-wrap">
            <div class="container">
                <div class="row gx-5">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    @if ($description)
                                        {!! $description !!}
                                    @else
                                        {!! $department->description !!}
                                    @endif
                                </div>
                                <br>
                                <div>
                                    <ul class="nav canvas-alt-tabs tabs-alt tabs nav-tabs mb-3" id="tabs-profile"
                                        role="tablist" style="--bs-nav-link-font-weight: 600;">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="canvas-home-alt-tab" data-bs-toggle="pill"
                                                data-bs-target="#home-alt" type="button" role="tab"
                                                aria-controls="canvas-home-alt" aria-selected="true"><i
                                                    class="bi-eye-fill"></i> Visi</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="canvas-profile-alt-tab" data-bs-toggle="pill"
                                                data-bs-target="#profile-alt" type="button" role="tab"
                                                aria-controls="canvas-profile-alt" aria-selected="false"><i
                                                    class="bi-rocket-takeoff"></i> Misi</button>
                                        </li>
                                    </ul>
                                    <div id="canvas-TabContent2" class="tab-content">
                                        <div class="tab-pane fade show active" id="home-alt" role="tabpanel"
                                            aria-labelledby="canvas-home-tab" tabindex="0">
                                            @if ($visi)
                                                {!! $visi !!}
                                            @else
                                                <div class="alert alert-info">
                                                    {{ __('department.visi_not_available') }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="tab-pane fade" id="profile-alt" role="tabpanel"
                                            aria-labelledby="canvas-profile-alt-tab" tabindex="0">
                                            @if ($misi)
                                                {!! $misi !!}
                                            @else
                                                <div class="alert alert-info">
                                                    {{ __('department.misi_not_available') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 line d-block d-md-none"></div>
                    <div class="col-md-3">
                        <div class="list-group">
                            <a href="http://akademik2.univbatam.ac.id/"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                <div>Portal Pegawai</div>
                            </a>
                            <a href="http://mahasiswa2.univbatam.ac.id/"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                <div>Portal Mahasiswa</div>
                            </a>
                            <a href="http://dosen2.univbatam.ac.id/"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                <div>Portal Dosen</div>
                            </a>
                            <a href="https://lppm.univbatam.ac.id/"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                <div>Portal LPPM</div>
                            </a>
                            <a href="http://ejurnal.univbatam.ac.id/"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                <div>Portal Jurnal Online</div>
                            </a>
                            <a href="https://elibrary.univbatam.ac.id/"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                <div>Portal E-Library</div>
                            </a>
                            <a href="https://kepk.univbatam.ac.id/"
                                class="list-group-item list-group-item-action d-flex justify-content-between">
                                <div>Portal KEPK</div>
                            </a>
                            <!-- Sidebar content here -->
                        </div>
                    </div>
                </div>
                <div class="line"></div>
                <div class="heading-block mt-0 text-center">
                    <h2 data-animate="swing">Informasi {{ $department->name }}</h2>
                    <span class="mx-auto"></span>
                </div>
                <div class="post-grid row col-mb-30">
                    @forelse ($news as $article)
                        <div class="entry col-lg-4 col-md-6">
                            <div class="grid-inner shadow-sm card rounded-5">
                                @if (isset($article['gambar']))
                                    <img src="{{ route('proxy.image', [
                                        'imagePath' => $article['gambar'],
                                        'dept_url' => $baseUrl,
                                    ]) }}"
                                        alt="{{ $article['judul'] ?? 'News Image' }}" class="card-img-top"
                                        onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="No image available"
                                        class="card-img-top">
                                @endif
                                <div class="p-4">
                                    <div class="entry-title">
                                        <h5 class="fw-medium text-danger mb-3">Berita</h5>
                                        <h2 class="text-transform-none ls-0 h5">
                                            <a href="{{ $baseUrl }}/{{ $article['judul_seo'] ?? '#' }}">
                                                {{ $article['judul'] ?? 'Untitled' }}
                                            </a>
                                        </h2>
                                    </div>
                                    <div class="entry-meta">
                                        <ul>
                                            <li>
                                                <i class="uil uil-calendar-alt"></i>
                                                {{ isset($article['tanggal']) ? \Carbon\Carbon::parse($article['tanggal'])->translatedFormat('d F Y') : 'No date' }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                Belum ada informasi terbaru untuk program studi ini.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="promo parallax promo-dark bg-color promo-full mb-5">
                <img src="{{ asset('css/templateweb/images/parallax/bg-contact.png') }}" class="parallax-bg">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg">
                            <center>
                                <a href="{{ $department->department_link }}" class="btn btn-lg btn-warning m-0"
                                    target="_blank">
                                    <font color="#fff">Website Fakultas</font>
                                </a>
                            </center>
                        </div>
                        <div class="col-md-7">
                            <blockquote>
                                @if ($email || $alamat || $no_telp)
                                    <ul class="list-unstyled mb-0 text-white">
                                        <li class="mb-2 h5 text-white">
                                            <i class="icon-building position-relative me-2 top-2"
                                                style="color: #ffffff;"></i>
                                            {{ $department->name }}
                                            @if ($department->faculty)
                                                <span class="text-white">({{ $department->faculty->name }})</span>
                                            @endif
                                        </li>
                                        @if ($alamat)
                                            <li class="mb-2 text-white">
                                                <i class="icon-location-arrow1 position-relative me-2 top-2"
                                                    style="color: #ffffff;"></i> {{ $alamat }}
                                            </li>
                                        @endif
                                        @if ($email)
                                            <li class="mb-2">
                                                <i class="icon-mail position-relative me-2 top-2"
                                                    style="color: #ffffff;"></i>
                                                <a href="mailto:{{ $email }}"
                                                    class="text-white">{{ $email }}</a>
                                            </li>
                                        @endif
                                        @if ($no_telp)
                                            <li class="mb-0">
                                                <i class="icon-phone position-relative me-2 top-2"
                                                    style="color: #ffffff;"></i>
                                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $no_telp) }}"
                                                    class="text-white">{{ $no_telp }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                @else
                                    <div class="alert alert-info mb-0">
                                        Contact information not available.
                                    </div>
                                @endif
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- #content end -->
@endsection
