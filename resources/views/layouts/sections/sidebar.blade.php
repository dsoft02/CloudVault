<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-white">
            <img src="{{ asset('assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
            </div>
            <ul class="main-menu active">
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Main</span></li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                <li class="slide {{ setActiveRoute(['dashboard','folders.open','search']) }}">
                    <a href="{{ route('dashboard') }}" class="side-menu__item {{ setActiveRoute(['dashboard','folders.open','search']) }}">
                        <i class="fe fe-database side-menu__icon"></i>
                        <span class="side-menu__label">My Drive </span>
                    </a>
                </li>
                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide  {{ setActiveRoute('recycle.bin') }}">
                    <a href="{{ route('recycle.bin') }}" class="side-menu__item  {{ setActiveRoute('recycle.bin') }}">
                        <i class="fe fe-trash side-menu__icon"></i>
                        <span class="side-menu__label">Recycle Bin</span>
                    </a>
                </li>
                <!-- End::slide -->


                <!-- Start::slide -->
                <li class="slide {{ setActiveRoute('profile.edit') }}">
                    <a href="{{ route('profile.edit') }}" class="side-menu__item {{ setActiveRoute('profile.edit') }}">
                        <i class="fe fe-user side-menu__icon"></i>
                        <span class="side-menu__label">Profile</span>
                    </a>
                </li>
                <!-- End::slide -->


                <!-- Start::slide -->
                <li class="slide">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="side-menu__item d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"><i class="bx bx-log-out fs-18 me-2 op-7 side-menu__icon"></i><span class="side-menu__label">Sign Out</span></a>
                    </form>
                </li>
                <!-- End::slide -->

            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>