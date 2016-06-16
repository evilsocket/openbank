$(function(){

$('#save').click(function(){
  var data = {};

  $.map( $('.setting'), function (el) { data[$(el).attr('id')] = $(el).val(); });

  $.ajax({
      type: 'PUT',
      url: "/api/v1/me?_method=PUT&api_token=" + api_token,
      data: JSON.stringify({"settings": data}),
      contentType: "application/json",
      dataType: 'json',

      success: function(data) {
        window.location.href = '/';
      },

      error: function( xhr, status, error ) {
        alert( "ERROR:\n\n" + xhr.responseJSON.errors.join("\n") );
      }
  });

  return false;
});

});
