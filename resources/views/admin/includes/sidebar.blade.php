<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}"> <img alt="image" src="{{ asset('assets/img/logo.png') }}" class="header-logo" /> <span
                    class="logo-name">Groww</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
                <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-desktop"></i><span>Dashboard</span></a>
            </li>
            <li><a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users"></i><span>Users Management</span></a></li>
            <li><a class="nav-link" href="{{ route('user.winners') }}"><i class="fas fa-trophy"></i><span>Winners List</span></a></li>
            <li><a class="nav-link" href="{{ route('users.star') }}"><i class="fas fa-bookmark"></i><span>Start Players</span></a></li>
            <li><a class="nav-link" href="{{ route('agents.index') }}"><i class="fas fa-user-secret"></i><span>Agents Management</span></a></li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-gamepad"></i><span>Game</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('markets') }}">Market List</a></li>
                    <li><a class="nav-link" href="{{ route('game.settings') }}">Game Settings</a></li>
                </ul>
            </li>
            <li><a class="nav-link" href="{{ route('results') }}"><i class="fas fa-clipboard-list"></i><span>Results Management</span></a></li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-bookmark"></i><span>Starline Markets</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('markets.starline') }}">Starline Markets List</a></li>
                    <li><a class="nav-link" href="{{ route('starline.settings') }}">Starline Settings</a></li>
                    <li><a class="nav-link" href="{{ route('star.results') }}">Starline Markets Result</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-poll"></i><span>Delhi Markets</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('markets.delhi') }}">Delhi Markets List</a></li>
                    <li><a class="nav-link" href="{{ route('delhi.settings') }}">Delhi Settings</a></li>
                    <li><a class="nav-link" href="{{ route('result.delhi.list') }}">Delhi Markets Result</a></li>
                </ul>
            </li>
            <li><a class="nav-link" href="{{ route('bids.all') }}"><i class="fab fa-bitcoin"></i><span>Bids</span></a></li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-file-invoice"></i><span>Reports</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('report.general') }}">General Reports</a></li>
                    <li><a class="nav-link" href="{{ route('report.withdraw') }}">Withdraw Report</a></li>
                    <li><a class="nav-link" href="{{ route('report.deposit') }}">Deposit Report</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-cogs"></i><span>Settings</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('setting.ratio') }}">Ratio Settings</a></li>
                    <li><a class="nav-link" href="{{ route('setting.star') }}">Starline Ratio Settings</a></li>
                    <li><a class="nav-link" href="{{ route('setting.delhi') }}">Delhi Ratio Settings</a></li>
                    <li><a class="nav-link" href="{{ route('setting.other') }}">Other Settings</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="far fa-images"></i><span>Slider Settings</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('setting.slider', ['type' => 'other']) }}">Home Slider</a></li>
                    <li><a class="nav-link" href="{{ route('setting.slider', ['type' => 'starline']) }}">Starline Slider</a></li>
                    <li><a class="nav-link" href="{{ route('setting.slider', ['type' => 'delhi']) }}">Delhi Slider</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-clipboard"></i><span>Notice Board</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('notice-board', ['type' => 'other']) }}">Home Board</a></li>
                    <li><a class="nav-link" href="{{ route('notice-board', ['type' => 'starline']) }}">Starline Board</a></li>
                    <li><a class="nav-link" href="{{ route('notice-board', ['type' => 'delhi']) }}">Delhi Board</a></li>
                </ul>
            </li>
{{--            @php--}}
{{--                $status = \App\Models\Setting::first();--}}
{{--                $status = $status->withdraw_status;--}}
{{--            @endphp--}}
{{--            <li>--}}
{{--                <a class="nav-link withdraw-status" href="{{ route('withdraw.status') }}" data-status="{{ $status }}">--}}
{{--                    <i class="fas fa-sign-out-alt"></i>--}}
{{--                    @if($status == 1)--}}
{{--                        <span>Withdraw Is Active</span>--}}
{{--                    @else--}}
{{--                        <span>Withdraw Is UnActive</span>--}}
{{--                    @endif--}}
{{--                </a>--}}
{{--            </li>--}}
            <li><a class="nav-link" href="{{ route('withdrawal-settings') }}"><i class="fas fa-wallet"></i><span>Withdrawal Settings</span></a></li>
        </ul>
    </aside>
</div>
