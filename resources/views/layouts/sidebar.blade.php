<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="/">
                        <img src="{{ asset('mazer') }}/images/logo/logo.png" alt="Logo" srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item{{ request()->is('dashboard') || request()->is('/') ? ' active' : '' }}">
                    <a href="/" class="sidebar-link">
                        <i class="bi bi-speedometer2"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>

                <li class="sidebar-item{{ request()->is(config('generator.route') . '*') ? ' active' : '' }}">
                    <a href="{{ route(config('generator.route') . '.create') }}" class="sidebar-link">
                        <i class="bi bi-grid"></i>
                        <span>{{ __('Generators') }}</span>
                    </a>
                </li>

                {{-- @canany(['view something', 'view something']) --}}
                <li class="sidebar-item has-sub{{ request()->is('master-data*') ? ' active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-collection"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="submenu ">
                        {{-- @can('view something') --}}
                        <li class="submenu-item">
                            <a href="#">{{ __('Product') }}</a>
                        </li>
                        {{-- @endcan --}}
                    </ul>
                </li>
                {{-- @endcanany --}}

                {{-- don`t remove comment below "sidebarTemplate", to generate a sidebar menu --}}
                {{-- sidebarTemplate --}}

                {{-- @can('view product') --}}
                <li class="sidebar-item{{ request()->is('products*') ? ' active' : '' }}">
                    <a href="{{ route('products.index') }}" class="sidebar-link">
                        <i class="bi bi-patch-question"></i>
                        <span>{{ __('Products') }}</span>
                    </a>
                </li>
                {{-- @endcan --}}

                <li class="sidebar-title">Account</li>

                @can('view user')
                    <li class="sidebar-item{{ request()->is('users*') ? ' active' : '' }}">
                        <a href="{{ route('users.index') }}" class="sidebar-link">
                            <i class="bi bi-people"></i>
                            <span>{{ __('Users') }}</span>
                        </a>
                    </li>
                @endcan

                @can('view role')
                    <li class="sidebar-item{{ request()->is('roles*') ? ' active' : '' }}">
                        <a href="{{ route('roles.index') }}" class="sidebar-link">
                            <i class="bi bi-person-check"></i>
                            <span>{{ __('Roles & Permissions') }}</span>
                        </a>
                    </li>
                @endcan

                <li class="sidebar-item{{ request()->is('profile*') ? ' active' : '' }}">
                    <a href="{{ route('profile') }}" class="sidebar-link">
                        <i class="bi bi-person-badge"></i>
                        <span>{{ __('Profile') }}</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="bi bi-door-open"></i>
                        <span> {{ __('Logout') }}</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
