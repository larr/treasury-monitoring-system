<!DOCTYPE html>
<html>

<!-- Mirrored from coderthemes.com/minton/purple-hori/pages-404.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 25 Mar 2019 08:17:22 GMT -->
<head>
    <meta charset="utf-8" />
    <title>Minton - Responsive Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('admin/css/purple.css') }}">

    <script src="{{ asset('admin/assets/js/modernizr.min.js') }}"></script>

</head>
<body>

<div class="ex-page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <svg class="svg-box" width="380px" height="500px" viewBox="0 0 837 1045" version="1.1"
                     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
                    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                       sketch:type="MSPage">
                        <path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z"
                              id="Polygon-1" stroke="#3bafda" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        <path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z"
                              id="Polygon-2" stroke="#7266ba" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        <path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z"
                              id="Polygon-3" stroke="#f76397" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        <path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z"
                              id="Polygon-4" stroke="#00b19d" stroke-width="6" sketch:type="MSShapeGroup"></path>
                        <path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z"
                              id="Polygon-5" stroke="#ffaa00" stroke-width="6" sketch:type="MSShapeGroup"></path>
                    </g>
                </svg>
            </div>

            <div class="col-lg-6">
                <div class="message-box">
                    <h1 class="m-b-0">{{ $exception->getStatusCode() }}</h1>
                    <h4>
                        Whoops, something went wrong on our servers.
                    </h4>
                    <div class="buttons-con">
                        <div class="action-link-wrap">
                            <a onclick="history.back(-1)" href="#" class="btn btn-custom btn-primary waves-effect waves-light m-t-20">Go Back</a>
                            <a href="{{ route('home') }}" class="btn btn-custom btn-primary waves-effect waves-light m-t-20">Go to Home Page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- jQuery  -->
<script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/popper.min.js') }}"></script><!-- Popper for Bootstrap --><!-- Tether for Bootstrap -->
<script src="{{ asset('admin/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/waves.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.scrollTo.min.js') }}"></script>
</body>

<!-- Mirrored from coderthemes.com/minton/purple-hori/pages-404.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 25 Mar 2019 08:17:22 GMT -->
</html>