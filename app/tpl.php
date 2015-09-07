<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Rechnung zahlen. Nicht abtippen.</title>

  <!-- CSS  -->
  <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
  <div class="progress hidden">
    <div class="indeterminate"></div>
  </div>

  <div class="drag-over hidden">
      <div class="row">
        <div class="col s12 center">
            <h4><i class="center large mdi-navigation-check"></i></h4>
            <p class="center-align bold">Drop!</p>
        </div>
      </div>
  </div>

  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <div class="row center">
          <h5 class="header col s12 light">Rechnung&nbsp;zahlen. Nicht&nbsp;abtippen.</h5>
      </div>

      <div id="login-error" class="row center hidden red-text">
          <p>Fehler: Der Anmeldevorgang fehlgeschlagen. Bitte überprüfe den Benutzername und das Passwort.</p>
      </div>

      <div id="user" class="row center">
          <h5 id="user-greeting" class="hidden"></h5>
          
          <div id="user-login" class="_hidden">
              <h5><img height="35px" src="/img/logo_dark.png"></h5>
              
             <div class="row">
              <div class="col l3 m3 s2">
                  &nbsp;
              </div>
              <div class="input-field col l6 m6 s8">
                <input id="username" type="text" class="validate">
                <label for="username">Benutzername</label>
              </div>
              <div class="col l3 m3 s2">
                  &nbsp;
              </div>
            </div>
            
            <div class="row">
                <div class="col l3 m3 s2">
                    &nbsp;
                </div>
                
                <div class="input-field col l6 m6 s8">
                    <input id="password" type="password" class="validate">
                    <label for="password">Passwort</label>
                </div>
                <div class="col l3 m3 s2">
                    &nbsp;
                </div>
                
            </div>
            <div class="row">
                <div class="col l3 m3 s2">
                    &nbsp;
                </div>
                <div class="input-field col l6 m6 s8">
                    <a id="login" class="waves-effect waves-light btn l4 m6 s8"><i class="material-icons left">https</i>Mit Number26 Anmelden</a>
                </div>
                <div class="row">
                    <div class="col l3 m3 s2">
                        &nbsp;
                </div>
            </div>

            <div class="invite-info row center">
                <br/><br/><br/>
                <p><b>Du hast kein Number26 Konto? Es ist kostenlos und in 10 Minuten erledigt!</b></p>
            </div>

            <div id="invite-ok" class="row center hidden green-text">
                <p>Schau' in dein E-Mail Postfach &mdash; deine Number26 Einladung ist unterwegs.</p>
            </div>
            <div id="invite-error" class="row center hidden red-text">
                <p>Fehler: E-Mail Adresse bereits eingeladen oder ungültig.</p>
            </div>
            <div id="invite-form" class="row center">
                <div class="input-field col l2 m1 s1">
                    &nbsp;
                </div>
                <div class="input-field col l4 m5 s10">
                  <input id="invite" type="text" class="validate">
                  <label for="invite">Deine E-Mail Adresse</label>
                </div>


                <div class="input-field col l4 m5 s10">
                    <a href="#" id="invite-button" class="btn-large waves-effect waves-light blue">€ 10 Startguthaben</a>
               </div>

            </div>
            <div class="invite-info row center">
                <p class="small">Du bekommst ein kostenloses Number26 Konto mit € 10 Guthaben</p>
            </div>
          </div>
      </div>

      <div id="payment" class="row center">
          <div class="input-field col s6">
              <label for="name">Empfänger</label>
              <input type="text" name="name" id="name" required>
          </div>

          <div class="input-field col s6">
              <label for="amount">Betrag</label>
              <input type="text" data-value="" name="amount" id="amount" required>
          </div>

          <div class="input-field col s6">
            <input id="iban" type="text" class="validate">
            <label for="iban">IBAN</label>
          </div>

          <div class="input-field col s6">
              <label for="bic">BIC</label>
              <input type="text" name="bic" id="bic" >
          </div>

          <div class="input-field col s12">
              <label for="reference">Zahlungsreferenz</label>
              <input type="text" id="reference" name="reference" required>
          </div>

          <div class="input-field col s12">
              &nbsp;
          </div>

          <div class="input-field col s6">
              <label for="pin">Überweisungs-PIN</label>
              <input type="password" name="pin" id="pin">
          </div>

          <div class="input-field col s6">
              <a id="pay" class="pay-action waves-effect waves-light btn disabled"><i class="material-icons">verified_user</i> <span id="amount_button"></span> mit Number26 überweisen</a>
          </div>

      </div>
      
      <div id="payment-approve" class="row center hidden">
          <img src="/img/zahlung-freigeben.png">
      </div>

      <div id="payment-confirmed" class="row center green-text hidden">
          <h3 style="font-size: 2em"><i style="font-size: 1em" class="material-icons">check_circle</i> Zahlung durchgeführt.</h3>
      </div>

      <div id="payment-declined" class="row center red-text hidden">
          <h3 style="font-size: 2em"><i style="font-size: 1em" class="material-icons">delete</i> Zahlung abgebrochen.</h3>
      </div>


      <div id="invoice" class="row center">
          <div class="input-field col l2 m1 s1">
              &nbsp;
          </div>
          <div class="input-field col l8 m10 s10">
            <img class="responsive-img" src="invoice.jpg" >
          </div>
          <div class="input-field col l2 m1 s1">
              &nbsp;
          </div>
      </div>

      <div id="invite-info" class="row center">
          <p class="small">Die Transaktion läuft zwischen dir (deinem Webbrowser) und deiner Bank (Number26 API) ab. Es werden keine Daten an Dritte weitergegeben.</p>
      </div>
    </div>
  </div>

  <footer class="page-footer light-blue lighten-2">
    <div class="container">
      <div class="row">
        <div class="col l12 m12 s12">
          <h5 class="white-text">Wie funktioniert das?</h5>
          <p class="grey-text text-lighten-4">
            Nutze dein kostenloses <a target="_blank" href="https://number26.de/sicheres-online-mobile-banking/">Number26 Konto</a> zum einfachen und sicheren bezahlen von Rechnungen. Nie mehr abtippen.
          </p>
        </div>

    </div>
    <div class="footer-copyright">
      <div class="container">
       SMART SEPA PAYMENT
      </div>
    </div>
  </footer>

  <script src="/js/jquery.min.js"></script>

  <script src="/js/drops.js"></script>
  <script src="/js/favico.js"></script>
  <script src="/js/materialize.min.js"></script>
  <script src="/js/init.js"></script>
  <script src="/js/number26.js"></script>
  <script src="/js/willzahlen.js"></script>

  </body>
</html>
