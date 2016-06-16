function money( n, decimals, symbol ){
  var fn = parseFloat(n);

  return fn == 0 ? '0 ' + symbol : fn.toFixed(decimals) + ' ' + symbol;
}

function trend(v){
  if( v >= 0 ){
    return '<span style="color:green">+' + v + '%</span>';
  }
  else {
    return '<span style="color:red">' + v + '%</span>';
  }
}

function ajax( payload, onsuccess, path = '/api/v1/me', method = 'PUT' ){
  $.ajax({
      type: method,
      url: path + "?api_token=" + api_token,
      data: JSON.stringify(payload),
      contentType: "application/json",
      dataType: 'json',

      success: onsuccess,

      error: function( xhr, status, error ) {
        alert( "ERROR:\n\n" + xhr.responseJSON.errors.join("\n") );
      }
  });
}

function btcUpdate(data){
  var balance = data['status']['balance'];

  $('#total_btc').html( money( balance['btc'], 8, '฿' ) );
  $('#balance_ts').html( '<small>Updated ' + $.timeago( new Date( balance['ts'] * 1000 ) ) + '</small>' );
}

function balanceUpdate(data){
  var balance = data['status']['balance'];
  var currency = data['currency'];
  var trends   = data['status']['price']['trends'];
  var positive = trends['24h'] >= 0;

  if( positive ){
    $('#balance_panel').attr( 'class', 'panel panel-success' );
    $('#total_fiat').attr('style', 'color: green');
  }
  else {
    $('#balance_panel').attr( 'class', 'panel panel-danger' );
    $('#total_fiat').attr('style', 'color: red');
  }

  $('#total_fiat').html( money( balance['fiat'], 2, currency['html'] ) );

  $('#trends').html('');
  $('<small>24 Hours : ' + trend(trends['24h']) + '</small>&nbsp;').appendTo('#trends');
  $('<small style="margin-left: 10px;">1 Week : ' + trend(trends['1w']) + '</small>').appendTo('#trends');
  $('<small style="margin-left: 10px;">1 Month : ' + trend(trends['1m']) + '</small>').appendTo('#trends');
}

function priceUpdate(data){
  var price = data['status']['price'];
  var currency = data['currency'];

  $('#price').html( money( price['value'], 2, currency['html'] ) );
  $('#price_ts').html( '<small>Updated ' + $.timeago( new Date( price['ts'] * 1000 ) ) + '</small>' );
}

function refreshKeysHandlers() {
  $('.key_delete').click(function(){
    if( confirm("Are you sure you want to delete this key?" ) ){
      var key = $(this).attr('data-key');

      ajax( '', function(data){
        update();
      },
      '/api/v1/me/key/' + key,
      'delete' );
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

function keysUpdate(data){
  var keys = data['keys'];

  $('#keys').html('');

  for( var i = 0; i < keys.length; i++ ){
    var key = keys[i];

    var html = '<tr>' +
                '<td><small>' + key['updated_at'] + '</small></td>' +
                '<td><strong>' + key['label'] + '<strong></td>' +
                '<td><small>' + key['value'] + '</small></td>' +
                '<td>' + money( key['balance'], 8, '฿' ) + '</td>' +
                '<td>' +
                  '<a href="#" class="btn btn-xs btn-danger key_delete" data-key="' + key['value'] + '"><i class="fa fa-trash"></i></a>' +
                  '&nbsp;' +
                  '<a href="#" class="btn btn-xs btn-warning key_edit" data-label="' + key['label'] + '" data-key="' + key['value'] + '"><i class="fa fa-pencil-square-o"></i></a>' +
                '</td>' +
               '</tr>';

    $(html).appendTo('#keys');
  }

  refreshKeysHandlers();
}

function chartUpdate(data) {
  var history = data['history'];

  $('#priceChart').html('');

  var chart_data = [];
  for( var i = 0; i < history.length; i++ ){
    var ts = new Date( parseInt(history[i]['ts']) * 1000 );
    var price = parseFloat(history[i]['price']);

    chart_data.push([ts,price]);
  }

  var data = new google.visualization.DataTable();
  data.addColumn('datetime', 'Time');
  data.addColumn('number', 'Pice');
  data.addRows(chart_data);

  var options = {
    pointSize: 5,
    chartArea: {'width': '90%', 'height': '85%'}
  };

  var chart = new google.visualization.LineChart(document.getElementById('priceChart'));

  chart.draw(data, options);
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

    ajax( payload, function(data){
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

function update(){
  console.log( 'Updating dashboard ...' );

  $.get( '/api/v1/me?api_token=' + api_token, function(data){
    btcUpdate(data);
    balanceUpdate(data);
    priceUpdate(data);

    chartUpdate(data);

    keysUpdate(data);
    titleUpdate(data);
  });
}

$(function(){
  google.charts.load('current', {packages: ['corechart', 'line']});
  initialize();
  update();
  setInterval(function(){update();}, 1500);
});
