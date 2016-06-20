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
        @if (Auth::guest())
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/login">Login</a></li>
            <li><a href="/register">Register</a></li>
          </ul>
        @else
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                {{ $user->name }} <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="/settings">
                    <i class="fa fa-btn fa-lg fa-cog"></i> Settings
                  </a>
                </li>
                <li>
                  <a href="/logs">
                    <i class="fa fa-btn fa-lg fa-file-text-o"></i> Logs
                  </a>
                </li>
                <li role="separator" class="divider"></li>
                <li>
                  <a href="/logout">
                    <i class="fa fa-btn fa-lg fa-sign-out"></i> Logout
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        @endif
    </div>
  </div>
</nav>
