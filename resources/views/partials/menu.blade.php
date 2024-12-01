<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
            </li>
            <li class="nav-item">
                <a href="{{ route('systemCalendar') }}" class="nav-link {{ auth()->user()->isAdmin() ? 'active' : '' }}">
                    <i class="nav-icon fa-fw fas fa-calendar"></i>
                    {{ trans('global.systemCalendar') }}
                </a>
            </li>
            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a href="{{ route('events.index') }}" class="nav-link {{ auth()->user()->isAdmin() ? 'active' : '' }}">
                        <i class="fa-fw fas fa-calendar nav-icon"></i>
                        {{ trans('cruds.event.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ auth()->user()->isAdmin() ? 'active' : '' }}">
                        <i class="fa-fw fas fa-user nav-icon"></i>
                        {{ trans('cruds.user.title') }}
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt"></i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
