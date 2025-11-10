jQuery(document).ready(function() {
  let button = jQuery('#iching-button');
  let againButton = jQuery('#iching-again-button');
  againButton.hide();
  button.show();
  button.click(function() {

    var data = {
      action: 'get_divination',
      nonce: iching_ajax.nonce
    };

    jQuery.ajax({
      type: 'post',
      dataType: 'json',
      url: iching_ajax.ajax_url,
      data: data,
      success: function(response) {
        button.hide();
        let div = document.querySelector('#iching-divination');
        div.innerHTML += response;
        againButton.show();
      }
   })
  });
});
