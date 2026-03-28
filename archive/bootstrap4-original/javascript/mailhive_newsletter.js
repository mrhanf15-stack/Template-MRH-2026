/**
 * mailhive newsletter script 
 */

$(document).ready(function () {

    $("#prospect_email").focus(function() {
      $('#mailing_list dd').removeClass("error").addClass('focus');
    });

    $("#prospect_email").blur(function() {
      if ($("#prospect_email").attr("value") == "") {
	    $('#mailing_list dd').removeClass("focus", "error");
	  }
    });

    var loader = $('<div class="infomessage">loading...<br /></div>')
		.appendTo("#capture_prospect_submit_button_loader")
        .hide();

    $('#capture_prospect_submit_button').click(function () {
      loader.show();
      $('#capture_prospect_submit_button').hide();
      $('#error_invalid').hide();
      $('#error_duplicate').hide();
      $.ajax({
        type: 'POST',
        url: '//mr-hanf.de/mailhive.php/api/public/v1.0/newsletter/add/',
        dataType: 'json',
        data: {
          email: $('#prospect_email').val(),
          firstname: $('#prospect_firstname').val(),
          lastname: $('#prospect_lastname').val(),
          gender: $('#prospect_gender').val(),
          topic_ids: $('#prospect_topics').val(),
          lng_id: '2'
        },
        success: function (data) {
          if (data.error === false) {
            loader.hide();
            $('#subscribe_success').show();
            $('#capture_prospect_submit_button').hide();
            $('.subscribe_success_hide').hide();
            $('#capture_prospect dd').removeClass().addClass('success');
          } else if (data.error === "invalid_email") {
            loader.hide();
            $('#capture_prospect_submit_button').show();
            $('#capture_prospect dd').removeClass().addClass('error');
            $('#error_invalid').show();
          } else if (data.error === "duplicate") {
            loader.hide();
            $('#capture_prospect_submit_button').show();
            $('#capture_prospect dd').removeClass().addClass('error');
            $('#error_duplicate').show();
          } else if (data.error === true) {
            loader.hide();
            $('#capture_prospect_submit_button').show();
            $('#capture_prospect dd').removeClass().addClass('error');
          }
        }
      });
      return false;
    });
  });
