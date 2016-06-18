<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>OpenBank</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/app.css">

    <link rel="shortcut icon" href="/img/btc.png"/>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <script src="/bower/Chart.js/Chart.js"></script>
    <script src="/bower/angular-chart.js/dist/angular-chart.js"></script>
    <link rel="stylesheet" href="/bower/angular-chart.js/dist/angular-chart.css">


</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="/">
                    <img src="/img/btc.png" width="25px">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                      <li><a href="/login">Login</a></li>
                      <li><a href="/register">Register</a></li>
                    @else
                      <li><a href="/settings" style="padding-top: 20px;">
                        <i class="fa fa-btn fa-lg fa-cog"></i></a>
                      </li>
                      <li>
                        <a href="/logout" style="padding-top: 20px;">
                          <i class="fa fa-btn fa-lg fa-sign-out"></i>
                        </a>
                      </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <br/><br/>

    <div class="navbar navbar-default navbar-fixed-bottom">
    <div class="container-fluid">
      <small>
      <p class="navbar-text pull-left">© 2016 - OpenBank is made with <span style="color:red">♥</span> by
        <a href="https://github.com/evilsocket" target="_blank" >Simone 'evilsocket' Margaritelli</a>
      </p>
      </small>
    </div>

  </div>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

    @yield('pagescript')
</body>
</html>
