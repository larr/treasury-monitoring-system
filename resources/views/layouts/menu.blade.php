<li class="has-submenu">
    <a href="{{ route('tr.admin.dashboard') }}"><i class="ti-home"></i>Dashboard</a>
</li>
@switch(strtolower($login_user->usertype->user_type_name))
    @case('admin')
        <li id="users" class="has-submenu">
            <a href="{{ route('tr.users') }}"><i class="ti-user"></i>Users</a>
        </li>
        <li id="cash" class="has-submenu">
            <a href="{{ route('tr.cash') }}"><i class="ti-user"></i>Cash</a>
        </li>
        <li id="logbook" class="has-submenu">
            <a href="{{ route('tr.logbook') }}"><i class="ti-book"></i>Logbook</a>
        </li>
        <li id="setting" class="has-submenu">
            <a href="#"><i class="ti-settings"></i>Access</a>
            <ul class="submenu">
                <li><a href="{{ route('tr.admin.useraccessbanks') }}">Business unit</a></li>
                <li id="addbank"><a href="{{ route('tr.admin.addbank') }}">Bank</a></li>
            </ul>
        </li>
        @break;
    @case('treasury')
        <li class="has-submenu">
            <a href="{{ route('trlogbookmonthlist') }}"><i class="ti-book"></i>Logbook</a>
        </li>
        @break;
    @case('accounting')
        <li id="reports" class="has-submenu">
            <a href="{{ route('tr.reports') }}"><i class="ti-receipt"></i>Reports</a>
            <ul class="submenu">
                <li id="logbook"><a href="{{ route('tr.reports.logbook') }}">Logbook</a></li>
            </ul>
        </li>
        @break;
    @default
@endswitch
