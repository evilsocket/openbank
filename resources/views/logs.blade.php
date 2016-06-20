@extends('layouts.app')

@section('content')
<div class="container">

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">
            Logs

            <span style="float:right">
              <a type="button" class="btn btn-danger btn-xs" href="/logs/clear">
                Clear
              </a>
            </span>
          </div>
          <div class="panel-body">
            <table class="table">
              <tbody>
                @foreach( $logs as $i => $l )
                  <tr>
                    <td>
                      <span class="label label-{{ $l['label'] }}">{{ $l['type'] }}</span>
                    </td>
                    <td>{{ $l['date'] }}</td>
                    <td>
                      @if( $l['msg'] == $l['full'] )
                        <small>{{ $l['msg'] }}</small>
                      @else
                        <a href="#" onclick="$('#log_{{$i}}').toggle(); return false;">
                          <small>{{ $l['msg'] }}</small>
                        </a>
                        <div style="display:none" id="log_{{$i}}">
                          <br/><br/>
                          <code style="font-size:11px">{{ $l['full'] }} </code>
                        </div>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>

</div>
@endsection
