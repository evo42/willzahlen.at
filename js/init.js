$(function () {

  drops('body', {
    'url': '/app/upload.php',
    'complete': function () {
      dragLeave();
      $('#progress').hide();
    },
    'success': function (xhr, file) {
      $('.progress').hide();
      var url = JSON.parse(xhr.response).url;
      dragLeave();
      $('#progress').hide();
      
      if (url) {
        window.location = url;
      } else {
        console.log('** upload error', xhr.response);
      }
    },
    'dragover': function () {
      dragOver();
    },
    'dragleave': function () {
      dragLeave();
    },
    'drop': function () {
      $('.progress').show();
    }
  });

  $('.progress').hide();
  $('#invite-button').on('click', function () {
    var email = $('#invite').val().trim();
    $.ajax({
      type: 'POST',
      url: '/app/invite.php',
      data: { 'invite': email },
      success: function (data) {
        if (data.status == 'OK') {
          $('#invite-form').fadeOut();
          $('#invite-info').fadeOut();
          $('#invite-ok').fadeIn();
          $('#invite-error').hide();
        } else {
          $('#invite-error').fadeIn();
          $('#invite-ok').hide();
        }
      },
      error: function (data) {
        $('#invite-ok').hide();
        $('#invite-error').fadeIn();
      }
    });
  });
});
function dragLeave() {
  $('.drag-over').hide();
  return true;
}
function dragOver() {
  // mdi-navigation-check or mdi-file-cloud-done
  $('.drag-over').show();
  return true;
}