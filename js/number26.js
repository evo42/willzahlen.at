var Number26 = function (options) {
  var bearer = 'YW5kcm9pZDpzZWNyZXQ=';
  var api = 'https://api.tech26.de';
  // var promises = [];
  var N26 = {};
  N26.login = function () {
    var username = $('#username').val().trim();
    var password = $('#password').val().trim();
    var accessToken = false;
    $.ajax({
      type: 'POST',
      url: api + '/oauth/token',
      dataType: 'json',
      data: {
        'username': username,
        'password': password,
        'grant_type': 'password'
      },
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', 'Basic ' + bearer);
      },
      success: function (data) {
        console.log(data);
        accessToken = data.access_token;
        localStorage.setItem('N26token', accessToken);
        N26.me();
      },
      error: function (data) {
        console.log(data);
      }
    });
  };
  N26.logout = function () {
    localStorage.setItem('N26token', null);
    localStorage.setItem('N26user', null);
    localStorage.removeItem('N26token');
    localStorage.removeItem('N26user');
  };
  N26.transaction = function () {
    var bearer = localStorage.getItem('N26token');
    var pin = $('#pin').data('value') || $('#pin').val() || $('#pin').text();
    var iban = $('#iban').data('value') || $('#iban').val() || $('#iban').text();
    var bic = $('#bic').data('value') || $('#bic').val() || $('#bic').text();
    var amount = $('#amount').data('value') || $('#amount').val() || $('#amount').text();
    var name = $('#name').data('value') || $('#name').val() || $('#name').text();
    var reference = $('#reference').data('value') || $('#reference').val() || $('#reference').text();
    // todo better checks for the amount...
    amount = parseFloat(amount.replace(',', '.')).toFixed(2);
    $.ajax({
      type: 'POST',
      url: api + '/api/transactions',
      dataType: 'json',
      data: JSON.stringify({
        'pin': pin,
        'transaction': {
          'partnerBic': bic,
          'amount': amount,
          'type': 'DT',
          'partnerIban': iban,
          'partnerName': name,
          'referenceText': reference
        }
      }),
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', 'bearer ' + bearer);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
      },
      success: function (data) {
        console.log(data);
        var favicon = new Favico({ animation: 'slide', bgColor : '#5CB85C', textColor : '#ff0'});
        favicon.badge(1);
        localStorage.setItem('N26transId', data.id);
        setTimeout(checkStatus, 1000);
      },
      error: function (data) {
        var favicon = new Favico({ animation: 'slide'});
        favicon.badge(-1);

        console.log(data);
        $('#payment-approve').fadeOut();
        $('#payment').fadeIn();
        $('#payment-declined').fadeIn();
        if (data.responseJSON[0] && data.responseJSON[0].field == 'pin') {
          $('#payment-declined h3').html('<i style="font-size: 1em" class="material-icons">error_outline</i> Transaktion kann nicht durchgef\xFChrt werden. PIN ung\xFCltig.');
        } else if (data.responseJSON && data.responseJSON.message || data.responseJSON[0] && data.responseJSON[0].message) {
          if (data.responseJSON && data.responseJSON.message) {
            msg = data.responseJSON.message;
          } else {
            msg = data.responseJSON[0].message;
          }
          $('#payment-declined h3').html('<i style="font-size: 1em" class="material-icons">error_outline</i> Fehler: ' + msg);
        } else {
          $('#payment-declined h3').html('<i style="font-size: 1em" class="material-icons">error_outline</i> Transaktion kann nicht durchgef\xFChrt werden.');
        }
      }
    });
  };
  N26.me = function () {
    var bearer = localStorage.getItem('N26token');
    // var defered = $.Deferred();//create a defered object
    // promises.push(defered.promise());//store the promise to the list to be resolved later
    $.ajax({
      type: 'GET',
      url: api + '/api/me',
      dataType: 'json',
      data: {},
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', 'bearer ' + bearer);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
      },
      success: function (data) {
        // defered.resolve(data); //resolve the defered when ajax call has finished
        console.log(data);
        localStorage.setItem('N26user', JSON.stringify(data));
        return data;
      },
      error: function (data) {
        console.log(data);
      }
    });  // return promises;
  };
  N26.accounts = function () {
    var bearer = localStorage.getItem('N26token');
    $.ajax({
      type: 'GET',
      url: api + '/api/accounts',
      dataType: 'json',
      data: {},
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', 'bearer ' + bearer);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
      },
      success: function (data) {
        console.log(data);
      },
      error: function (data) {
        console.log(data);
      }
    });
  };
  N26.transactions = function () {
    var bearer = localStorage.getItem('N26token');
    $.ajax({
      type: 'GET',
      url: api + '/api/transactions?sort=visibleTS&dir=DESC&limit=50',
      dataType: 'json',
      data: {},
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', 'bearer ' + bearer);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
      },
      success: function (data) {
        console.log(data);
      },
      error: function (data) {
        console.log(data);
      }
    });
  };
  N26.cards = function () {
    var bearer = localStorage.getItem('N26token');
    $.ajax({
      type: 'GET',
      url: api + '/api/cards',
      dataType: 'json',
      data: {},
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', 'bearer ' + bearer);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
      },
      success: function (data) {
        console.log(data);
      },
      error: function (data) {
        console.log(data);
      }
    });
  };
  N26.addresses = function () {
    var bearer = localStorage.getItem('N26token');
    $.ajax({
      type: 'GET',
      url: api + '/api/addresses',
      dataType: 'json',
      data: {},
      beforeSend: function (xhr) {
        xhr.setRequestHeader('Authorization', 'bearer ' + bearer);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Content-Type', 'application/json');
      },
      success: function (data) {
        console.log(data);
      },
      error: function (data) {
        console.log(data);
      }
    });
  };
  return N26;
};
function checkStatus() {
  var confirmed = false;

  function isConfirmed() {
    var api = 'https://api.tech26.de';
    var bearer = localStorage.getItem('N26token');
    var transId = localStorage.getItem('N26transId');
    $('#payment-confirmed').hide();
    $('#payment-declined').hide();
    if (transId) {
      $.ajax({
        type: 'GET',
        url: api + '/api/transactions/' + transId,
        dataType: 'json',
        data: {},
        beforeSend: function (xhr) {
          xhr.setRequestHeader('Authorization', 'bearer ' + bearer);
          xhr.setRequestHeader('Accept', 'application/json');
          xhr.setRequestHeader('Content-Type', 'application/json');
        },
        success: function (trans) {
          var favicon = new Favico({ animation: 'slide', bgColor : '#5CB85C', textColor : '#ff0'});
          favicon.badge(-1);

          if (trans.userCertified) {
            $('#payment-approve').fadeOut();
            $('#payment-confirmed').fadeIn();
            $('#payment-declined').hide();
            $('#invoice').hide();
            localStorage.setItem('N26transId', null);
          } else if (trans.title == 'Oooops') {
            $('#payment-approve').fadeOut();
            $('#payment-confirmed').hide();
            $('#payment-declined').fadeIn();
            localStorage.setItem('N26transId', null);
          } else {
            setTimeout(isConfirmed, 1000);
          }
        },
        error: function (resp) {
          console.log(resp);
          var favicon = new Favico({ animation: 'slide'});
          favicon.badge(-1);

          $('#payment-approve').fadeOut();
          $('#payment-confirmed').hide();
          $('#payment-declined').fadeIn();
          localStorage.setItem('N26transId', null);
        }
      });
    }
  }
  isConfirmed();
}