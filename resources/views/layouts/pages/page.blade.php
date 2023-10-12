<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Simple CMS"/>
    <meta name="author" content="Sheikh Heera"/>
    <link rel="shortcut icon" href={{ asset("favicon.png") }} />

    <title> @yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href={{ asset("assets/bootstrap/css/bootstrap.css") }} rel="stylesheet"/>
    <link href={{ asset("assets/bootstrap/css/custom.css") }} rel="stylesheet"/>

</head>
<body>

<div id="throbber" style="display:none; min-height:120px;"></div>
<div id="noty-holder"></div>
<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img src="http://placehold.it/200x50&text=TIENDA ONLINE" alt="Tienda Online">
            </a>
        </div>
        <!-- Top Menu Items -->
        @if(auth()->user())
            <ul class="nav navbar-right top-nav">
                <li><a href="#" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Stats"><i class="fa fa-bar-chart-o"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> asdsa <b class="fa fa-angle-down"></b></a>
                    <ul class="dropdown-menu">
                        <!--<li><a href="#"><i class="fa fa-fw fa-user"></i> Edit Profile</a></li>
                        <li><a href="#"><i class="fa fa-fw fa-cog"></i> Change Password</a></li>
                        <li class="divider"></li>-->
                        <li><a href="/"><i class="fa fa-fw fa-power-off"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="#" data-toggle="collapse" data-target="#submenu-1"><i class="fa fa-fw fa-search"></i> MENU <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                        <ul id="submenu-1" class="collapse">
                            <li><a href="/"><i class="fa fa-angle-double-right"></i>Products</a></li>
                        </ul>
                    </li>
                    <!--<li>
                        <a href="#" data-toggle="collapse" data-target="#submenu-2"><i class="fa fa-fw fa-star"></i>  MENU 2 <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                        <ul id="submenu-2" class="collapse">
                            <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.1</a></li>
                            <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.2</a></li>
                            <li><a href="#"><i class="fa fa-angle-double-right"></i> SUBMENU 2.3</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="investigaciones/favoritas"><i class="fa fa-fw fa-user-plus"></i>  MENU 3</a>
                    </li>
                    <li>
                        <a href="sugerencias"><i class="fa fa-fw fa-paper-plane-o"></i> MENU 4</a>
                    </li>
                    <li>
                        <a href="faq"><i class="fa fa-fw fa fa-question-circle"></i> MENU 5</a>
                    </li>-->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        @else
            <ul class="nav navbar-right top-nav">
                <li>
                    <a href="/" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Stats">Login</a>
                </li>
                <li class="dropdown">
                    <a href="/" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Stats">Register</a>
                </li>
            </ul>
        @endif
    </nav>

    <div id="page-wrapper">
        <div class="container-fluid">

            <div class="btn-toolbar">
                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <div class="ml-5">
                        <button type="submit" class="btn btn-primary">Create Order</button>
                    </div>
                </form>
                <a href="{{route('plates.index')}}" class="btn btn-primary"><i class="icon-pencil"></i>List Plates</a>
                <a href="{{route('orders.index')}}" class="btn btn-primary"><i class="icon-pencil"></i>Orders in progress</a>
                <a href="{{route('ingredients.index')}}" class="btn btn-primary"><i class="icon-pencil"></i>Ingredients in warehouse</a>
                <a href="{{route('purchases.index')}}" class="btn btn-primary"><i class="icon-pencil"></i>Purchases Historical</a>
                <a href="{{route('orders.process')}}" class="btn btn-primary"><i class="icon-pencil"></i>Orders process</a>
            </div>
            <!-- Page Heading -->
            @yield('content')
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
</div><!-- /#wrapper -->
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="{{ asset('assets/bootstrap/js/bootstrap.js') }}"></script>
<script !src="">
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $(".side-nav .collapse").on("hide.bs.collapse", function() {
            $(this).prev().find(".fa").eq(1).removeClass("fa-angle-right").addClass("fa-angle-down");
        });
        $('.side-nav .collapse').on("show.bs.collapse", function() {
            $(this).prev().find(".fa").eq(1).removeClass("fa-angle-down").addClass("fa-angle-right");
        });
    })

</script>
</body>
</html>
