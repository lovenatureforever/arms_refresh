<div class="app-menu">

    <!-- Sidenav Brand Logo -->
    <a class="logo-box" href="{{ route('home') }}">
        {{--
        <!-- Light Brand Logo -->
        <div class="logo-light">
            <img class="h-6 logo-lg" src="/images/logo-light.png" alt="Light logo">
            <img class="logo-sm" src="/images/logo-sm.png" alt="Small logo">
        </div>

        <!-- Dark Brand Logo -->
        <div class="logo-dark">
            <img class="h-6 logo-lg" src="/images/logo-dark.png" alt="Dark logo">
            <img class="logo-sm" src="/images/logo-sm.png" alt="Small logo">
        </div> --}}

        <h1 class="text-2xl font-bold text-center">ARMS</h1>
    </a>

    <!-- Sidenav Menu Toggle Button -->
    <button class="absolute end-2 top-5 rounded-full p-1.5" id="button-hover-toggle">
        <span class="sr-only">Menu Toggle Button</span>
        <i class="text-xl mgc_round_line"></i>
    </button>

    <!--- Menu -->
    <div class="srcollbar" data-simplebar>
        <ul class="menu" data-fc-type="accordion">
            {{-- <li class="menu-title">Menu</li> --}}

            <li class="menu-item">
                <a class="menu-link" href="{{ route('home') }}">
                    <span class="menu-icon"><i class="mgc_dashboard_4_line"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            {{-- <li class="menu-title">Management</li> --}}

            <li class="menu-item">
                <a class="menu-link" href="{{ route('auditpartners.index') }}">
                    <span class="menu-icon"><i class="mgc_user_follow_line"></i></span>
                    <span class="menu-text">Audit Partner</span>
                </a>
            </li>

            {{-- <li class="menu-item">
                <a class="menu-link" href="{{ route('companies.index') }}">
                    <span class="menu-icon"><i class="mgc_building_5_line"></i></span>
                    <span class="menu-text">Company Access</span>
                </a>
            </li> --}}

            <li class="menu-item">
                <a class="menu-link" href="{{ route('users.index') }}">
                    <span class="menu-icon"><i class="mgc_group_line"></i></span>
                    <span class="menu-text">Users</span>
                </a>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="{{ route('auditfirm.show') }}">
                    <span class="menu-icon"><i class="mgc_certificate_2_line"></i></span>
                    <span class="menu-text">Audit Firm Info</span>
                </a>
            </li>
            {{--
            <li class="menu-item">
                <a class="menu-link" href="{{ route('data.migration') }}">
                    <span class="menu-icon"><i class="mgc_copy_line"></i></span>
                    <span class="menu-text">Data Migration</span>
                </a>
            </li>
            --}}

            <!--
            <li class="menu-item">
                <a class="menu-link" href="#">
                    <span class="menu-icon"><i class="mgc_group_line"></i></span>
                    <span class="menu-text">General Setup</span>
                </a>
            </li>
            -->

        </ul>

        <!-- Help Box Widget -->
        {{-- <div class="mx-5 my-10">
            <div class="p-6 text-center rounded-md help-box bg-black/5">
                <div class="flex justify-center mb-4">
                    <svg aria-hidden="true" width="30" height="18">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15 0c-4 0-6.5 2-7.5 6 1.5-2 3.25-2.75 5.25-2.25 1.141.285 1.957 1.113 2.86 2.03C17.08 7.271 18.782 9 22.5 9c4 0 6.5-2 7.5-6-1.5 2-3.25 2.75-5.25 2.25-1.141-.285-1.957-1.113-2.86-2.03C20.42 1.728 18.718 0 15 0ZM7.5 9C3.5 9 1 11 0 15c1.5-2 3.25-2.75 5.25-2.25 1.141.285 1.957 1.113 2.86 2.03C9.58 16.271 11.282 18 15 18c4 0 6.5-2 7.5-6-1.5 2-3.25 2.75-5.25 2.25-1.141-.285-1.957-1.113-2.86-2.03C12.92 10.729 11.218 9 7.5 9Z"
                            fill="#38BDF8"></path>
                    </svg>
                </div>
                <h5 class="mb-2">Unlimited Access</h5>
                <p class="mb-3">Upgrade to plan to get access to unlimited reports</p>
                <a class="text-white btn btn-sm bg-secondary" href="javascript: void(0);">Upgrade</a>
            </div>
        </div> --}}
    </div>
</div>
<!-- Sidenav Menu End  -->
