<div class="app-menu">

    <!-- Sidenav Brand Logo -->
    <a class="logo-box" href="{{ route('home') }}">
        {{-- <!-- Light Brand Logo -->
        <div class="logo-light">
            <img class="logo-lg h-6" src="/images/logo-light.png" alt="Light logo">
            <img class="logo-sm" src="/images/logo-sm.png" alt="Small logo">
        </div>

        <!-- Dark Brand Logo -->
        <div class="logo-dark">
            <img class="logo-lg h-6" src="/images/logo-dark.png" alt="Dark logo">
            <img class="logo-sm" src="/images/logo-sm.png" alt="Small logo">
        </div> --}}

        <h1 class="text-center text-2xl font-bold">ARMS</h1>
    </a>

    <!-- Sidenav Menu Toggle Button -->
    <button class="absolute end-2 top-5 rounded-full p-1.5" id="button-hover-toggle">
        <span class="sr-only">Menu Toggle Button</span>
        <i class="mgc_round_line text-xl"></i>
    </button>

    <!--- Menu -->
    <div class="srcollbar" data-simplebar>
        <ul class="menu" data-fc-type="accordion">
            {{-- <li class="menu-title">Menu</li> --}}

            <li class="menu-item">
                <a class="menu-link" href="{{ route('home') }}">
                    <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            {{-- <li class="menu-title">Management</li> --}}

            <li class="menu-item">
                <a class="menu-link" href="{{ route('index.tenant') }}">
                    <span class="menu-icon"><i class="mgc_department_line"></i></span>
                    <span class="menu-text">Tenants</span>
                </a>
            </li>
        </ul>

        <!-- Help Box Widget -->
        {{-- <div class="mx-5 my-10">
            <div class="help-box rounded-md bg-black/5 p-6 text-center">
                <div class="mb-4 flex justify-center">
                    <svg aria-hidden="true" width="30" height="18">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15 0c-4 0-6.5 2-7.5 6 1.5-2 3.25-2.75 5.25-2.25 1.141.285 1.957 1.113 2.86 2.03C17.08 7.271 18.782 9 22.5 9c4 0 6.5-2 7.5-6-1.5 2-3.25 2.75-5.25 2.25-1.141-.285-1.957-1.113-2.86-2.03C20.42 1.728 18.718 0 15 0ZM7.5 9C3.5 9 1 11 0 15c1.5-2 3.25-2.75 5.25-2.25 1.141.285 1.957 1.113 2.86 2.03C9.58 16.271 11.282 18 15 18c4 0 6.5-2 7.5-6-1.5 2-3.25 2.75-5.25 2.25-1.141-.285-1.957-1.113-2.86-2.03C12.92 10.729 11.218 9 7.5 9Z" fill="#38BDF8"></path>
                    </svg>
                </div>
                <h5 class="mb-2">Unlimited Access</h5>
                <p class="mb-3">Upgrade to plan to get access to unlimited reports</p>
                <a class="btn btn-sm bg-secondary text-white" href="javascript: void(0);">Upgrade</a>
            </div>
        </div> --}}
    </div>
</div>
<!-- Sidenav Menu End  -->
