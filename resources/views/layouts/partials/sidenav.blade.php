<!-- Start Sidebar -->
<aside class="app-menu" id="app-menu">
    <!-- Sidenav Menu Brand Logo -->
    <a class="logo-box sticky top-0 flex min-h-topbar-height items-center justify-start px-6 backdrop-blur-xs"
        href="">
        <!-- Light Brand Logo -->
        <div class="logo-light">
            <img alt="Light logo" class="logo-lg h-6" src="/images/logo-light.png" />
            <img alt="Small logo" class="logo-sm h-6" src="/images/logo-sm.png" />
        </div>
        <!-- Dark Brand Logo -->
        <div class="logo-dark">
            <img alt="Dark logo" class="logo-lg h-6" src="/images/logo-dark.png" />
            <img alt="Small logo" class="logo-sm h-6" src="/images/logo-sm.png" />
        </div>
    </a>
    <!-- Sidenav Menu Toggle Button -->
    <div class="absolute top-0 end-5 flex h-topbar items-center justify">
        <button class="" id="button-hover-toggle">
            <i class="iconify tabler--circle size-5"></i>
        </button>
    </div>
    <!-- Sidenav Menu Item Link -->
    <div class="relative min-h-0 flex-grow">
        <div class="size-full" data-simplebar="">
            <ul class="side-nav p-3 hs-accordion-group">
                <li class="menu-title">
                    <span>Usuarios</span>
                </li>
                <li class="menu-item hs-accordion">
                    <a class="hs-accordion-toggle menu-link" href="javascript:void(0)">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <span class="menu-text"> Usuarios y Roles </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="sub-menu hs-accordion-content hidden">
                        <li class="menu-item">
                            @can('profile')
                                <a class="menu-link" href="{{ route('profile.edit') }}">
                                    <span class="menu-text"> Perfil Usuario </span>
                                </a>
                            @endcan
                        </li>
                        <li class="menu-item">
                            @can('app.users.index')
                                <a class="menu-link" href="{{ route('users.index') }}">
                                    <span class="menu-text"> Listar Usuarios </span>
                                </a>
                            @endcan
                        </li>
                        {{-- <li class="menu-item">
                            <a class="menu-link" href="{{ route('profile.edit') }}">
                                <span class="menu-text"> Roles </span>
                            </a>
                        </li>  --}}
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</aside>
<!-- End Sidebar -->
