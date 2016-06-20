function money( n, decimals, symbol ){
  var fn = parseFloat(n);

  return fn == 0 ? '0 ' + symbol : fn.toFixed(decimals) + ' ' + symbol;
}

function trend(v){
  if( v >= 0 ){
    return '<span style="color:green">+' + v.toFixed(2) + '%</span>';
  }
  else {
    return '<span style="color:red">' + v.toFixed(2) + '%</span>';
  }
}

function saveKey( label, value, onsuccess ) {
  var payload = {"keys":[{"label":label,"value":value}]};

  $.ajax({
      type: 'PUT',
      url: '/api/v1/me?api_token=' + api_token,
      data: JSON.stringify(payload),
      contentType: "application/json",
      dataType: 'json',
      success: onsuccess,
      error: function( xhr, status, error ) {
        $('#errors').html('');

        for( var i = 0; i < xhr.responseJSON.errors.length; i++ ){
          var err = xhr.responseJSON.errors[i];
          $('<li>' + err + '</li>').appendTo('#errors');
        }

        $('#errormodal').modal('show');
      }
  });
}

function deleteKey( key, onsuccess ) {
  $.ajax({
      type: 'DELETE',
      url: '/api/v1/me/key/' + key + '?api_token=' + api_token,
      contentType: "application/json",
      dataType: 'json',
      success: onsuccess,
      error: function( xhr, status, error ) {
        $('#errors').html('');

        for( var i = 0; i < xhr.responseJSON.errors.length; i++ ){
          var err = xhr.responseJSON.errors[i];
          $('<li>' + err + '</li>').appendTo('#errors');
        }

        $('#errormodal').modal('show');
      }
  });
}

function titleUpdate(data) {
  var trend = data['status']['price']['trends']['24h'];
  var price = data['status']['price'];
  var currency = data['currency'];

  if( trend != 0 ){
    document.title = price['value'] + ' ' + currency['symbol'] + ' ( ' + ( trend > 0 ? '+' : '' ) + trend.toFixed(2) + '% )';
  }
  else {
    document.title = price['value'] + ' ' + currency['symbol'];
  }
}

function initialize() {
  $('[data-toggle="tooltip"]').tooltip();

  $('#key_save').click(function(){
    var label   = $('#key_label').val();
    var value   = $('#key_value').val();
    var payload = {"keys":[{"label":label,"value":value}]};

    saveKey( label, value, function(data){
      $('#keymodal').modal('hide');
      update();
    });

    return false;
  });

  $('#add_key').click(function(){
    $('#key_modal_title').html('Add new Public Key');
    $('#key_label').val('');
    $('#key_value').val('');
    $('#keymodal').modal('show');
    return false;
  });
}

function refreshKeysHandlers() {
  $('.key_delete').click(function(){
    if( confirm("Are you sure you want to delete this key?" ) ){
      var key = $(this).attr('data-key');

      deleteKey( key, function(data){
        update();
      })
    }

    return false;
  });

  $('.key_edit').click(function(){
    var label = $(this).attr('data-label');
    var key = $(this).attr('data-key');

    $('#key_save').click(function(){
      var label   = $('#key_label').val();
      var value   = $('#key_value').val();
      var payload = {"keys":[{"label":label,"value":value}]};

      ajax( payload, function(data){
        $('#keymodal').modal('hide');
        update();
      });

      return false;
    });

    $('#key_modal_title').html('Edit this Public Key');
    $('#key_label').val(label);
    $('#key_value').val(key);
    $('#keymodal').modal('show');

    return false;
  });
}

var app = angular.module('OpenBank', ['chart.js'], function($interpolateProvider) {
  $interpolateProvider.startSymbol('<%');
  $interpolateProvider.endSymbol('%>');
});

app.controller( 'DashboardController', function($scope, $sce, $filter) {
  $scope.currency = { };

  $scope.btc = {
    total: 'Loading ...',
    timestamp: '...'
  };

  $scope.balance = {
    total: 'Loading ...',
    color: 'green',
    class: 'panel panel-success',
    trends: [
      $sce.trustAsHtml( trend(0) ),
      $sce.trustAsHtml( trend(0) ),
      $sce.trustAsHtml( trend(0) )
    ]
  };

  $scope.price = {
    current: 'Loading ...',
    raw: 100.0,
    timestamp: '...'
  };

  $scope.chart = {
    type: 0,
    data:   [[]],
    labels: [],
    names: [
      '1 Hour',
      '24 Hours',
      '1 Week',
      '1 Month'
    ],
    name: '1 Hour',
    error: '',
    setType: function(type) {
      $scope.chart.error = '';
      $scope.chart.type  = type;
      $scope.chart.name  = $scope.chart.names[type];
    }
  };

  $scope.chart.setType( init_chart_type );

  $scope.rates = {
    data:   [[1]],
    labels: ['Loading ...'],
    colours: [{
      "fillColor": "green"
    },{
      "fillColor": "red"
    }]
  };

  $scope.keys = [{
    created_at: 'Loading ...',
    label: 'Loading ...',
    balance: '...',
    value: ''
  }];

  $scope.money = money;

  $scope.updateBTC = function(data) {
    var balance  = data['status']['balance'];

    $scope.btc.total     = money( balance['btc'], 8, '฿' );
    $scope.btc.timestamp = $.timeago( new Date( balance['ts'] * 1000 ) );
  };

  $scope.updateBalance = function(data) {
    var balance  = data['status']['balance'];
    var trends   = data['status']['price']['trends'];
    var currency = data['currency'];
    var positive = trends['1m'] >= 0;

    $scope.balance.class   = positive ? 'panel panel-success' : 'panel panel-danger';
    $scope.balance.color   = positive ? 'green' : 'red';
    $scope.balance.total   = money( balance['fiat'], 2, currency['symbol'] );
    $scope.balance.trends  = $.map( trends, function(value, index){ return $sce.trustAsHtml( trend(value) ); });
  };

  $scope.updatePrice = function(data) {
    var price    = data['status']['price'];
    var currency = data['currency'];

    $scope.price.raw       = price['value'];
    $scope.price.current   = money( price['value'], 2, currency['symbol'] );
    $scope.price.timestamp = $.timeago( new Date( price['ts'] * 1000 ) );
  };

  $scope.updateChart = function(data) {
    $scope.chart.error  = data['history'][0]['complete'] ? '' : 'Not Enough Data';
    $scope.chart.data   = [ $.map( data['history'], function(value, index){ return value.price; }).reverse() ];
    $scope.chart.labels = $.map( data['history'], function(value, index){
      var date = new Date( value.ts * 1000 );

      if( $scope.chart.type == 0 ){
        var fmt = $filter('date')( date, 'HH:mm' );
        return ( index % 10 == 0 ? fmt : '' );
      }
      else if( $scope.chart.type == 1 ){
        var fmt = $filter('date')( date, 'HH:mm' );
        return ( index % 2 == 0 ? fmt : '' );
      }
      else if( $scope.chart.type == 2 ){
        var fmt = $filter('date')( date, 'EEEE' );
        return $filter('date')( date, 'EEEE' );
      }
      else if( $scope.chart.type == 3 ){
        var fmt = $filter('date')( date, 'EEEE' );
        return $filter('date')( date, 'dd MMM' );
      }
    }).reverse();
  };

  $scope.updateRates = function(data) {
    $scope.rates = data['rates'];
  };

  $scope.updateAll = function(){
    $.get( '/api/v1/me?r=' + new Date().getTime() + '&api_token=' + api_token + '&chart=' + $scope.chart.type, function(data){
      $scope.updateBTC(data);
      $scope.updateBalance(data);
      $scope.updatePrice(data);
      $scope.updateChart(data);
      $scope.updateRates(data);

      $scope.keys     = data['keys'];
      $scope.currency = data['currency'];

      $scope.$apply();

      titleUpdate(data);
      refreshKeysHandlers();
    });
  };

  setInterval( $scope.updateAll, 1000 );
});

$(function(){
  initialize();
});
