<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Treasury Monitoring System - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="A beautiful theme." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
@switch(strtolower($login_user->usertype->user_type_name))
@case('accounting')
    <link rel="stylesheet" href="{{ asset('admin/css/green.css') }}">
@break;
@case('treasury')
<link rel="stylesheet" href="{{ asset('admin/css/blue.css') }}">
@break;
@default
    <link rel="stylesheet" href="{{ asset('admin/css/purple.css') }}">
@endswitch
@stack('styles')
    <script src="{{ asset('admin/assets/js/modernizr.min.js') }}"></script>

</head>

<body>

<!-- Navigation Bar-->
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">

            <!-- Logo container-->
            <div class="logo">
                <!-- Text Logo -->
                <a href="{{ route('home') }}" class="logo">
                    <span class="logo-small"><i class="mdi mdi-radar"></i></span>
                    <span class="logo-large"><i class="mdi mdi-radar"></i> TMS <span class="small-text">[ {{ $login_user->businessunit->bname }} ]</span></span>
                </a>
                <!-- Image Logo -->
                <!--<a href="index.html" class="logo">-->
                <!--<img src="assets/images/logo_dark.png" alt="" height="24" class="logo-lg">-->
                <!--<img src="assets/images/logo_sm.png" alt="" height="24" class="logo-sm">-->
                <!--</a>-->

            </div>
            <!-- End Logo container-->

            <div class="menu-extras topbar-custom">

                <ul class="list-inline float-right mb-0">

                    <li class="menu-item list-inline-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>

                    <li class="list-inline-item dropdown notification-list">
                        <a class="nav-link dropdown-toggle waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="{{ ($login_user->gender == 'male')?asset('admin/assets/images/users/avatar-1.jpg'):asset('admin/assets/images/users/avatar-3.jpg') }}" alt="user" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h5 class="text-overflow"><small class="text-white">Welcome ! {{ $login_user->firstname }}</small> </h5>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="mdi mdi-account"></i> <span>Profile</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="mdi mdi-settings"></i> <span>Settings</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="mdi mdi-lock-open"></i> <span>Lock Screen</span>
                            </a>

                            <!-- item-->
                            <a href="{{ route('logout') }}" class="dropdown-item notify-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout"></i> <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>
            </div>
            <!-- end menu-extras -->

            <div class="clearfix"></div>

        </div> <!-- end container -->
    </div>
    <!-- end topbar-main -->

    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">
                    @include('layouts.menu')
                </ul>
                <!-- End navigation menu -->
            </div> <!-- end #navigation -->
        </div> <!-- end container -->
    </div> <!-- end navbar-custom -->
</header>
<!-- End Navigation Bar-->


<div class="wrapper">
    <div class="container-fluid">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="btn-group pull-right">
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            @yield('crumb')
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $page_title }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->
        @yield('content')

    </div> <!-- end container -->
</div>
<!-- end wrapper -->


<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                2019 - {{ date('Y') }} © TMS - Devops
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->

<!-- jQuery  -->
<script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/popper.min.js') }}"></script><!-- Popper for Bootstrap --><!-- Tether for Bootstrap -->
<script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/waves.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.scrollTo.min.js') }}"></script>
@stack('scripts')
</body>

</html>