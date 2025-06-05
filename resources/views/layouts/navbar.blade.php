<nav class="primary-menu order-lg-1 col-lg-5 px-0" style="position:inherit;">

    <ul class="menu-container justify-content-lg-end">
        <li class="menu-item">
            <a class="menu-link" href="{{ route('profile') }}">
                <div>{{ __('menu.profile') }}</div>
            </a>
        </li>
        <li class="menu-item">
            <a class="menu-link" href="{{ route('facilities') }}">
                <div>{{ __('menu.facilities') }}</div>
            </a>
            <ul class="sub-menu-container">
                @foreach ($facilities as $facility)
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('facility.detail', ['slug' => $facility->slug]) }}">
                            <div>{{ $facility->name }}</div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="menu-item mega-menu">
            <a class="menu-link" href="#">
                <div>{{ __('menu.departments') }}</div>
            </a>
            <div class="mega-menu-content mega-menu-style-2">
                <div class="container">
                    <div class="row">
                        @foreach ($faculties as $faculty)
                            <ul class="sub-menu-container mega-menu-column col-lg-3">
                                <li class="menu-item mega-menu-title">
                                    <a class="menu-link" href="#">
                                        <div>{{ $faculty->name }}</div>
                                    </a>
                                    @foreach ($faculty->departements as $departement)
                                        <ul class="sub-menu-container">
                                            <li class="menu-item">
                                                <a class="menu-link"
                                                    href="{{ route('department', ['slug' => $departement->slug]) }}">
                                                    <div>{{ $departement->name }}</div>
                                                </a>
                                            </li>
                                        </ul>
                                    @endforeach
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </li>
    </ul>

</nav>

<nav class="primary-menu order-lg-3 col-lg-5 px-0" style="position:inherit;">
    <div class="menu-container">
        <ul class="menu-container">
            <li class="menu-item">
                <a class="menu-link" href="#">
                    <div>{{ __('menu.information') }}</div>
                </a>
                <ul class="sub-menu-container">
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('news') }}">
                            <div>{{ __('menu.news') }}</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a class="menu-link" href="{{ route('announcements') }}">
                            <div>{{ __('menu.announcements') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item">
                <a class="menu-link" href="{{ route('identities') }}">
                    <div>{{ __('menu.contact') }}</div>
                </a>
            </li>
            <li class="menu-item">
                <a class="menu-link" href="https://pendaftaran.univbatam.ac.id/registrasi/index.php" target="_blank">
                    <div>{{ __('menu.Online registration') }}</div>
                </a>
            </li>
        </ul>
    </div>
</nav><!-- #primary-menu end -->
