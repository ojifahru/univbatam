<!-- Header -->
        <header id="header">
            <div id="header-wrap">
                <div class="container">
                    <div class="header-row justify-content-lg-between">

                        <div id="logo" class="order-lg-2 col-auto px-0 me-lg-0">
                            <!-- Logo -->
                            <a href="{{ route('home') }}" class="logo text-decoration-none">
                                <img class="logo-default"  height="73"
                                    src="{{ asset('storage/' . $logo['logo']) }}" alt="Logo-Universitas-Batam">
                            </a>
                        </div>
                        <!-- #logo end -->

                        <div class="header-misc d-flex d-lg-none">



                        </div>

                        <div class="primary-menu-trigger">
                            <button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
                                <span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
                            </button>
                        </div>

                        {{-- Primary Navigation --}}
                        @include('layouts.navbar')

                    </div>
                </div>
            </div>
            <div class="header-wrap-clone"></div>
        </header>
        <!-- #header end -->
