@extends('layouts.app') {{-- atau layout yang kamu pakai --}}

@section('content')
    <div class="container">
        <div class="row" id="news-list">
            {{-- Tempat berita akan ditampilkan --}}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('https://kedokteran.univbatam.ac.id/api/berita')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('news-list');
                    data.forEach(item => {
                        const col = document.createElement('div');
                        col.classList.add('col-md-4', 'mb-4');

                        col.innerHTML = `
                        <div class="card h-100">
                            <img src="https://kedokteran.univbatam.ac.id/asset/foto_berita/${item.gambar}" class="card-img-top" alt="${item.judul}">
                            <div class="card-body">
                                <h5 class="card-title">${item.judul}</h5>
                                <p class="card-text"><small class="text-muted">${item.tanggal}</small></p>
                                <a href="/berita/${item.judul_seo}" class="btn btn-primary">Lihat Detail</a>
                            </div>
                        </div>
                    `;
                        container.appendChild(col);
                    });
                })
                .catch(error => {
                    console.error('Gagal mengambil data:', error);
                });
        });
    </script>
@endsection
