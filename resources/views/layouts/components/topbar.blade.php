<!-- Topbar Start -->
<header class="app-header flex items-center gap-3 px-4">
    <!-- Sidenav Menu Toggle Button -->
    <button class="nav-link me-auto p-2" id="button-toggle-menu">
        <span class="sr-only">Menu Toggle Button</span>
        <span class="flex h-6 w-6 items-center justify-center">
            <i class="mgc_menu_line text-xl"></i>
        </span>
    </button>

    <!-- Topbar Brand Logo -->
    <a class="logo-box" href="{{ route('home') }}">
        <h1 class="text-center text-2xl font-bold">ARMS</h1>
    </a>

    <!-- Fullscreen Toggle Button -->
    <div class="hidden md:flex">
        <button class="nav-link p-2" data-toggle="fullscreen" type="button">
            <span class="sr-only">Fullscreen Mode</span>
            <span class="flex h-6 w-6 items-center justify-center">
                <i class="mgc_fullscreen_line text-2xl"></i>
            </span>
        </button>
    </div>

    <!-- Notification Bell Button -->
    <div class="relative hidden md:flex">
        <button class="nav-link p-2" data-fc-type="dropdown" data-fc-placement="bottom-end" type="button">
            <span class="sr-only">View notifications</span>
            <span class="flex h-6 w-6 items-center justify-center">
                <i class="mgc_notification_line text-2xl"></i>
            </span>
        </button>
        <div class="fc-dropdown z-50 mt-2 hidden w-80 rounded-lg border border-gray-200 bg-white opacity-0 shadow-lg transition-[margin,opacity] duration-300 fc-dropdown-open:opacity-100 dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-dashed border-gray-200 p-2 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h6 class="text-sm"> Notification</h6>
                    <a class="text-gray-500 underline" href="javascript: void(0);">
                        <small>Clear All</small>
                    </a>
                </div>
            </div>

            <div class="h-80 p-4" data-simplebar>

                <h5 class="mb-2 text-xs text-gray-500">Today</h5>

                <a class="mb-4 block" href="javascript:void(0);">
                    <div class="card-body">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg flex h-9 w-9 items-center justify-center rounded-full bg-primary text-white">
                                    <i class="mgc_message_3_line text-lg"></i>
                                </div>
                            </div>
                            <div class="ms-2 flex-grow truncate">
                                <h5 class="mb-1 text-sm font-semibold">Coming Soon <small class="ms-1 font-normal text-gray-500">1 min ago</small></h5>
                                <small class="noti-item-subtitle text-muted">Notification will be develop</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <a class="block border-t border-dashed border-gray-200 p-2 text-center font-semibold text-primary underline dark:border-gray-700" href="javascript:void(0);">
                View All
            </a>
        </div>
    </div>

    <!-- Light/Dark Toggle Button -->
    <div class="flex">
        <button class="nav-link p-2" id="light-dark-mode" type="button">
            <span class="sr-only">Light/Dark Mode</span>
            <span class="flex h-6 w-6 items-center justify-center">
                <i class="mgc_moon_line text-2xl"></i>
            </span>
        </button>
    </div>

    <!-- Profile Dropdown Button -->
    <div class="relative">
        <button class="nav-link" data-fc-type="dropdown" data-fc-placement="bottom-end" type="button">
            <img class="h-10 rounded-full" src="/images/users/user-6.jpg" alt="user-image">
        </button>
        <div class="fc-dropdown z-50 mt-2 hidden w-44 rounded-lg border border-gray-200 bg-white p-2 opacity-0 shadow-lg transition-[margin,opacity] duration-300 fc-dropdown-open:opacity-100 dark:border-gray-700 dark:bg-gray-800">
            <a class="flex items-center rounded-md px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="{{ route('logout') }}">
                <i class="mgc_exit_line me-2"></i>
                <span>Log Out</span>
            </a>
        </div>
    </div>
</header>
<!-- Topbar End -->

<!-- Topbar Search Modal -->
<div>
    <div class="fc-modal fixed start-0 top-0 z-50 hidden h-full w-full" id="topbar-search-modal">
        <div class="m-12 opacity-0 transition-all fc-modal-open:opacity-100 fc-modal-open:duration-500 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="mx-auto max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl transition-all dark:bg-slate-800">
                <div class="relative">
                    <div class="pointer-events-none absolute start-4 top-3.5 text-gray-900 text-opacity-40 dark:text-gray-200">
                        <i class="mgc_search_line text-xl"></i>
                    </div>
                    <input class="h-12 w-full border-0 bg-transparent pe-4 ps-11 text-gray-900 placeholder-gray-500 focus:ring-0 dark:text-gray-200 dark:placeholder-gray-300 sm:text-sm" type="search" placeholder="Search...">
                </div>
            </div>
        </div>
    </div>
</div>
