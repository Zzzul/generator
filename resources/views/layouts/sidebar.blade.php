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

                @foreach (config('generator.sidebars') as $sidebar)
                    <li class="sidebar-title">{{ $sidebar['header'] }}</li>

                    @foreach ($sidebar['menus'] as $menu)
                        @if ($menu['uri'] != null && empty($menu['sub_menus']))
                            <li
                                class="sidebar-item{{ request()->is($menu['uri'] == '/' ? $menu['uri'] : substr($menu['uri'] . '*', 1)) ? ' active' : '' }}">
                                <a href="{{ $menu['uri'] }}" class="sidebar-link">
                                    {!! $menu['icon'] !!}
                                    <span>{{ __($menu['title']) }}</span>
                                </a>
                            </li>
                        @else
                            <li
                                class="sidebar-item has-sub{{ request()->is(str()->slug($menu['title']) . '*') ? ' active' : '' }}">
                                <a href="#" class="sidebar-link">
                                    {!! $menu['icon'] !!}
                                    <span>{{ __($menu['title']) }}</span>
                                </a>
                                <ul class="submenu ">
                                    @foreach ($menu['sub_menus'] as $sub_menu)
                                        <li class="submenu-item">
                                            <a href="{{ $sub_menu['uri'] }}">
                                                {{ __($sub_menu['title']) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                @endforeach

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
