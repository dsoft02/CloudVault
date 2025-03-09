<div class="main-header-container container-fluid">

    <!-- Start::header-content-left -->
    <div class="header-content-left">

        <!-- Start::header-element -->
        <div class="header-element">
            <div class="horizontal-logo">
                <a href="{{ route('dashboard') }}" class="header-logo">
                    <img src="{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
                    <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                    <img src="{{ asset('assets/images/brand-logos/desktop-white.png') }}" alt="logo" class="desktop-white">
                    <img src="{{ asset('assets/images/brand-logos/toggle-white.png') }}" alt="logo" class="toggle-white">
                </a>
            </div>
        </div>
        <!-- End::header-element -->


        <!-- Start::header-element -->
        <div class="header-element">
            <!-- Start::header-link -->
            <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);">
                <i class="header-icon fe fe-align-left"></i>
            </a>
            <!-- End::header-link -->
        </div>
        <!-- End::header-element -->
    </div>
    <!-- End::header-content-left -->

    <!-- Start::header-content-right -->
    <div class="header-content-right">
        <!-- Start::header-element -->
        <div class="header-element header-theme-mode">
            <!-- Start::header-link|layout-setting -->
            <a href="javascript:void(0);" class="header-link layout-setting">
                <span class="light-layout">
                    <!-- Start::header-link-icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-120q-150 0-255-105T120-480q0-150 105-255t255-105q14 0 27.5 1t26.5 3q-41 29-65.5 75.5T444-660q0 90 63 153t153 63q55 0 101-24.5t75-65.5q2 13 3 26.5t1 27.5q0 150-105 255T480-120Zm0-80q88 0 158-48.5T740-375q-20 5-40 8t-40 3q-123 0-209.5-86.5T364-660q0-20 3-40t8-40q-78 32-126.5 102T200-480q0 116 82 198t198 82Zm-10-270Z"/></svg>
                    <!-- End::header-link-icon -->
                </span>
                <span class="dark-layout">
                    <!-- Start::header-link-icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" fill="currentColor" height="24" viewBox="0 -960 960 960" width="24"><path d="M480-360q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35Zm0 80q-83 0-141.5-58.5T280-480q0-83 58.5-141.5T480-680q83 0 141.5 58.5T680-480q0 83-58.5 141.5T480-280ZM200-440H40v-80h160v80Zm720 0H760v-80h160v80ZM440-760v-160h80v160h-80Zm0 720v-160h80v160h-80ZM256-650l-101-97 57-59 96 100-52 56Zm492 496-97-101 53-55 101 97-57 59Zm-98-550 97-101 59 57-100 96-56-52ZM154-212l101-97 55 53-97 101-59-57Zm326-268Z"/></svg>
                    <!-- End::header-link-icon -->
                </span>
            </a>
            <!-- End::header-link|layout-setting -->
        </div>
        <!-- End::header-element -->

        <!-- Start::header-element -->
        <div class="header-element header-fullscreen">
            <!-- Start::header-link -->
            <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-open full-screen-icon header-link-icon" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-close full-screen-icon header-link-icon d-none" fill="currentColor" height="24" viewBox="0 -960 960 960" width="24"><path d="M320-200v-120H200v-80h200v200h-80Zm240 0v-200h200v80H640v120h-80ZM200-560v-80h120v-120h80v200H200Zm360 0v-200h80v120h120v80H560Z"/></svg>
            </a>
            <!-- End::header-link -->
        </div>
        <!-- End::header-element -->

        <!-- Start::header-element -->
        <div class="header-element headerProfile-dropdown">
            <!-- Start::header-link|dropdown-toggle -->
            <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <img src="{{  asset('assets/images/faces/user.png') }}" alt="img" width="37" height="37" class="rounded-circle">
            </a>
            <!-- End::header-link|dropdown-toggle -->
            <ul class="main-header-dropdown dropdown-menu pt-0 header-profile-dropdown dropdown-menu-end main-profile-menu" aria-labelledby="mainHeaderProfile">
                <li>
                    <div class="main-header-profile bg-primary menu-header-content text-fixed-white">
                        <div class="my-auto">
                            <h6 class="mb-0 lh-1 text-fixed-white">{{ Auth::user()->name }}</h6><span class="fs-11 op-7 lh-1">Premium Member</span>
                        </div>
                    </div>
                </li>
                <li><a class="dropdown-item d-flex" href="{{ route('profile.edit') }}"><i class="bx bx-user-circle fs-18 me-2 op-7"></i>Profile</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"><i class="bx bx-log-out fs-18 me-2 op-7"></i>Sign Out</a>
                    </form>
                </li>
            </ul>
        </div>
        <!-- End::header-element -->

    </div>
    <!-- End::header-content-right -->

</div>