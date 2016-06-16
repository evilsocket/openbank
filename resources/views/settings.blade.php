@extends('layouts.app')

@section('pagescript')
  <script type="text/javascript">
    var api_token = '{{ $user->api_token }}';
  </script>
  <script src="/js/settings.js?t=<?= time() ?>"></script>
@stop

@section('content')
<div class="container">

  <div class="row" id="settings_form">
      <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-default">
              <div class="panel-heading">Settings</div>
              <div class="panel-body">
                  <form class="form-horizontal" role="form" method="POST" action="#">

                      @foreach( \App\UserSetting::getValidNames() as $name )

                        <div class="form-group">
                            <label for="{{ $name }}" class="col-md-4 control-label">{{ ucfirst($name) }}</label>
                            <div class="col-md-6">
                              {!! \App\UserSetting::getHtmlFor( $user, $name ) !!}
                            </div>
                        </div>

                      @endforeach

                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-4">
                            <button id="save" class="btn btn-success">
                                <i class="fa fa-btn fa-floppy-o"></i> Save
                            </button>
                          </div>
                      </div>

                  </form>
              </div>
          </div>
      </div>
  </div>

</div>
@endsection
