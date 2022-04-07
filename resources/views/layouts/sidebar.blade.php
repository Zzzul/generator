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
                <li class="sidebar-item{{ request()->is('/') || request()->is('dashboard') ? ' active' : '' }}">
                    <a class="sidebar-link" href="/">
                        <i class="bi bi-speedometer"></i>
                        <span> {{ __('Dashboard') }}</span>
                    </a>
                </li>

                @foreach (config('generator.sidebars') as $sidebar)
                    @if (isset($sidebar['permissions']))
                        @canany($sidebar['permissions'])
                            <li class="sidebar-title">{{ $sidebar['header'] }}</li>

                            @foreach ($sidebar['menus'] as $menu)
                                @php
                                    $permissions = empty($menu['permission']) ? $menu['permissions'] : [$menu['permission']];
                                @endphp

                                @canany($permissions)
                                    @if (empty($menu['sub_menus']))
                                        @can($menu['permission'])
                                            <li class="sidebar-item{{ \App\Generators\GeneratorUtils::isActiveMenu($menu['route']) }}">
                                                <a href="{{ $menu['route'] }}" class="sidebar-link">
                                                    {!! $menu['icon'] !!}
                                                    <span>{{ __($menu['title']) }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    @else
                                        <li class="sidebar-item has-sub{{ \App\Generators\GeneratorUtils::isActiveMenu($menu['permissions']) }}">
                                            <a href="#" class="sidebar-link">
                                                {!! $menu['icon'] !!}
                                                <span>{{ __($menu['title']) }}</span>
                                            </a>
                                            <ul class="submenu ">
                                                @canany($menu['permissions'])
                                                    @foreach ($menu['sub_menus'] as $sub_menu)
                                                        @can($sub_menu['permission'])
                                                            <li class="submenu-item">
                                                                <a href="{{ $sub_menu['route'] }}">
                                                                    {{ __($sub_menu['title']) }}
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    @endforeach
                                                @endcanany
                                            </ul>
                                        </li>
                                    @endif
                                @endcanany
                            @endforeach
                        @endcanany
                    @endif
                @endforeach

                @if (env('APP_ENV') === 'local')
                @php
                    $generator = str(config('generator.name'))->plural();
                @endphp
                    <li class="sidebar-title">{{ __("$generator") }}</li>

                    <li class="sidebar-item{{ request()->is($generator . '/create') ? ' active' : '' }}">
                        <a class="sidebar-link" href="{{ route($generator . '.create') }}">
                            <i class="bi bi-grid-fill"></i>
                            <span> {{ __('CRUD ' . str($generator)->singular()->ucfirst()) }}</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-title">Account</li>

                <li class="sidebar-item{{ request()->is('profile') ? ' active' : '' }}">
                    <a class="sidebar-link" href="{{ route('profile') }}">
                        <i class="bi bi-person-badge-fill"></i>
                        <span> {{ __('Profile') }}</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="bi bi-door-open-fill"></i>
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
