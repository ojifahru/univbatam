<div class="container">
    <!-- Footer Widgets
    ============================================= -->
    <div class="footer-widgets-wrap">
        <div class="row col-mb-50">
            <div class="col-lg-8">
                <div class="row col-mb-50">
                    <div class="col-md-0">
                        <div class="widget">
                            @php
                                $identity = \App\Models\Identity::first();
                                $logo = \App\Models\Logo::first();
                            @endphp

                            <div
                                style="background: url('{{ asset('css/templateweb/images/world-map.png') }}') no-repeat center center; background-size: 100%;">
                                <img src="{{ asset('storage/' . $logo->footer_logo) }}" alt="Footer Logo"
                                    class="footer-logo" height="150px">
                                <address>
                                    <strong>{{ $identity->name }}</strong><br>
                                    {{ $identity->address }} || <i class="fa fa-phone" style="color: #FFCC00"></i>
                                    {{ $identity->phone }}<br>
                                    <i class="fa fa-envelope" style="color: #FFCC00"></i> {{ $identity->email }}<br>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <strong>Peta Lokasi</strong>
                <div class="google-maps">
                    <iframe src="{{ $identity->maps }}" width="600" height="450" style="border:0;"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Copyrights
============================================= -->
<div id="copyrights">
    <div class="container">
        <div class="w-100 text-center">
            Copyrights &copy; {{ date('Y') }} All Rights Reserved by Universitas Batam.
        </div>
    </div>
</div><!-- #copyrights end -->

@php
    $message_wa = '&text=' . urlencode('Halo, Universitas Batam');
    $phone = $identity->whatsapp;
    $link_wa =
        request()->userAgent() && preg_match('/(android|iphone|mobile|tablet)/i', request()->userAgent())
            ? "https://api.whatsapp.com/send?phone={$phone}{$message_wa}"
            : "https://web.whatsapp.com/send?phone={$phone}{$message_wa}";
@endphp

<div class="widget-whatsapp" id="chatmobile">
    <a target="_blank" class="text-white" href="{{ $link_wa }}">
        <i class="fa-brands fa-whatsapp" aria-hidden="true" style="font-size:40px;"></i>
    </a>
</div>
