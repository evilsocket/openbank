<nav class="navbar navbar-default navbar-static-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
          <span class="sr-only">Toggle Navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>

      <a class="navbar-brand" href="/">
          <img src="/img/btc.png" width="25px">
      </a>
    </div>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
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
