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
      <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-heading">Settings</div>
              <div class="panel-body">
                  <form class="form-horizontal" role="form" method="POST" action="#">

                      @foreach( \App\UserSetting::available() as $name => $descriptor )

                        <div class="form-group">
                            <label for="{{ $name }}" class="col-md-4 control-label">{!! $descriptor->label() !!}</label>
                            <div class="col-md-6">
                              {!! $descriptor->html( $user ) !!}
                            </div>
                        </div>

                      @endforeach

                      <hr/>

                      <div class="form-group">
                        <label for="api_key" class="col-md-4 control-label">
                          OpenBank API Key
                          <br/>
                          <small style="color:#999; font-weight:normal">
                            Your API key for this OpenBank instance.</small>
                        </label>
                        <div class="col-md-6">
                          <input type="text" name="api_key" class="form-control" id="api_key" value="{{ $user->api_token }}"  disabled="disabled"/>
                        </div>
                      </div>

                      <br/><br/>

                      <div class="form-group">
                          <div class="col-md-6 col-md-offset-9">
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
