@extends('layouts.app')

@section('pagescript')
  <script type="text/javascript">
    var api_token = '{{ $user->api_token }}';
  </script>
  <script src="/js/home.js?t=<?= time() ?>"></script>
  <script src="/js/jquery.timeago.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop

@include('home.keymodal')

@section('content')
<div class="container-fluid" ng-app="OpenBank" ng-controller="DashboardController">

  <div class="row">
    <div class="col-md-4">
      @include('home.balance')
    </div>

    <div class="col-md-4">
      @include('home.btc')
    </div>

    <div class="col-md-4">
      @include('home.price')
    </div>
  </div>

  <div class="row">
    <div class="col-md-8">
      @include('home.chart')
    </div>

    <div class="col-md-4">
      @include('home.rates')
    </div>
  </div>


  <div class="row">
    <div class="col-md-12">
      @include('home.keys')
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <center>
        <small style="color: #ccc">
          Response generated in <span ng-bind="ms"></span> ms.
        </small>
      </center>
    </div>
  </div>
</div>
@endsection
