$(function () {
  var user = null;
  user = JSON.parse(localStorage.getItem('N26user'));
  if (localStorage.getItem('N26token') && user) {
    $('#user-greeting').html('Hallo ' + user.firstName + ' ' + user.lastName + ' &nbsp; &nbsp; <i id="logout" class="material-icons click" title="Abmelden">highlight_off</i>').show();
    $('#user-login').hide();
    $('#pay').removeClass('disabled');
  } else {
    $('#user-greeting').hide();
    $('#user-login').show();
    $('#pay').addClass('disabled');
  }
  function checkLogin() {
    Number26().me();
    setTimeout(function () {
      user = JSON.parse(localStorage.getItem('N26user'));
      if (user && user.signupCompleted && user.email) {
        $('#user-greeting').html('Hallo ' + user.firstName + ' ' + user.lastName + ' &nbsp; &nbsp; <i id="logout" class="material-icons click" title="Abmelden">highlight_off</i>').fadeIn();
        $('#pay').removeClass('disabled');
        $('#logout').on('click', function () {
          Number26().logout();
          $('#user-greeting').fadeOut();
          $('#user-login').fadeIn();
          $('#pay').addClass('disabled');
          $('.invite-info').hide();
          $('#invite-ok').hide();
          $('#invite-error').hide();
          $('#invite-form').hide();
        });
      } else {
        $('#user-login').fadeIn();
        $('#pay').addClass('disabled');
        $('#login').on('click', function () {
          Number26().login();
          $('#user-login').fadeOut();
          $('#pay').removeClass('disabled');
          setTimeout(function () {
            checkLogin();
          }, 500);
        });
      }
    }, 500);
  }
  checkLogin();
  getExtractions();
  $('#amount').on('keyup', amountFormat);
  $('#amount').on('change', amountFormat);
  $('#pay').on('click', function () {
    Number26().transaction();
    $('#payment').fadeOut();
    $('#payment-approve').fadeIn();
    $('#invoice').fadeOut();
  });
});
function amountFormat() {
  var amount = 0;
  amount = $('#amount').val();
  if (!amount || amount == '') {
    amount = 0;
  }
  if (amount) {
    amount = amount.replace(' ', '');
    amount = amount.replace(',', '.');
  }
  if (isNaN(parseFloat(amount))) {
    amount = 0;
  }
  amount = parseFloat(amount).toFixed(2);
  $('#amount').data('value', amount);
  $('#amount_button').text('\u20AC ' + amount.replace('.', ','));
}
function getExtractions() {
  $.ajax({
    'url': 'data.json',
    'success': function (xhr2) {
      console.log('extractions', xhr2.extractions);
      data = xhr2.extractions;
      var values = {};
      values.iban = data.iban && data.iban.value || '', values.bic = data.bic && data.bic.value || '', values.name = data.senderName && data.senderName.value || '', values.amount = data.amountToPay && data.amountToPay.value.replace(':EUR', '').replace('.', ',') || 0, values.reference = data.paymentReference && data.paymentReference.value || data.invoiceId && data.invoiceId.value || data.referenceId && data.referenceId.value || data.customerId && data.customerId.value || '';
      $('#iban').val(values.iban);
      if (values.iban != '') {
        $('label[for=\'iban\']').addClass('active');
      }
      $('#bic').val(values.bic);
      if (values.bic != '') {
        $('label[for=\'bic\']').addClass('active');
      }
      $('#amount').val(values.amount);
      if (values.amount != '') {
        $('label[for=\'amount\']').addClass('active');
      }
      $('#name').val(values.name);
      if (values.name != '') {
        $('label[for=\'name\']').addClass('active');
      }
      $('#reference').val(values.reference);
      if (values.reference != '') {
        $('label[for=\'reference\']').addClass('active');
      }
      // set button value
      $('#amount_button').text('\u20AC ' + values.amount);
    }
  });
}