<?php
require_once './app/config.php';

if (!isset($tld)) {
    die('¯\_(ツ)_/¯');
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Rechnung@BANKpay.plus payzahlen — nicht abtippen — BANKpay+</title>

  <!-- CSS -- ATTENTION: do wean Google™ APIs und FONTS g'used. -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

  <!-- structured meta-data -->
  <meta property="og:title" content="Mit BANKpay+ bezahlen." >
  <meta property="og:description" content="Einfach sicher via Online Banking einkaufen.">
  <meta property="og:image" itemprop="image" content="https://dummyimage.com/1200x1200/ffffff/000000.png%26text=BANKpay+">
  <meta property="og:image:secure_url" itemprop="image" content="https://dummyimage.com/1200x1200/ffffff/000000.png%26text=BANKpay+">
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="1200" />
  <meta property="og:type" content="website">

  <!-- Fonts -- DSGVO or so : /
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Rubik+Microbe&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> -->
  <link href="http://fonts.cdnfonts.com/css/poppins" rel="stylesheet">
  <link href="http://fonts.cdnfonts.com/css/nunito" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="/css/flag-icons.min.css">

  <!-- 3rd party -- DSGVO or so : / -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/a8334b3968.js" crossorigin="anonymous"></script>
</head>
<body>
  <!-- mostly hidden UI / UX elements -->
  <div class="progress hidden">
      <div class="indeterminate"></div>
  </div>
  <div class="drag-over hidden">
      <div class="row">
        <div class="col s12 center">
            <h4><i class="center large mdi-navigation-check"></i></h4>
            <p class="center-align bold">Drop. Rechnung bitte!</p>
        </div>
      </div>
  </div>

  <!-- BANKpay+ information + sign-up -->
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <div class="row center">
          <h5 class="header col s12 light">Rechnung&nbsp;<em>payzahlen</em> — nicht&nbsp;abtippen — einfach&nbsp;weiterleiten:</h5>
      </div>
      <h1 class="header center blue-text">rechnung&#8203;@<?php echo $tld ?></h1>
      <div id="invite-ok" class="row center hidden green-text">
          <p>Schau' in dein E-Mail Postfach &mdash; deine BANKpay+ Einladung und eine Rechnung zum testen sind unterwegs.</p>
      </div>
      <div id="invite-error" class="row center hidden red-text">
          <p>Achtung: Ist deine E-Mail Adresse gültig?</p>
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
              <a href="#" id="invite-button" class="btn-large waves-effect waves-light blue">BANKpay+ nutzen</a>
         </div>

      </div>
      <div id="invite-info" class="row center">
          <p class="small">Du bekommst ein kostenloses BANKpay+ karte.digital Konto zum einfach sicheren bezahlen mit SEPA Instant und Open Banking.</p>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
      <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">document_scanner</i></h2>
            <h5 class="center">PDF senden.</h5>

            <p class="light center">
                Rechnungen per E-Mail / PDF Dokument erhalten?<br />
                <em><a href="mailto:rechnung@<?php echo $tld ?>?subject=Rechnung weiterleiten und via Link in der Auto-Antwort einfach sicher bezahlen">rechnung@<?php echo $tld ?></a></em><br />
                <strong>Einfach weiterleiten. Fertig.</strong>
            </p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">check</i></h2>
            <h5 class="center">Einfach zahlen.</h5>

            <p class="light center">
                Mit deiner Bank bezahlen.<br />
                Rechnung kontrollieren &amp; Zahlung freigeben.<br />
                <strong>Einfach payzahlen. Sicher.</strong>
            </p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">favorite</i></h2>
            <h5 class="center">Zeit sparen.</h5>

            <p class="light center">
                Suchen und abtippen von Rechnungs&shy;daten nervt.<br />
                Ohne mBanking Formular.<br />
                <strong>Einfach erledigt. Sofort.</strong><br />
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="row center">
          <a href="https://BANKpay.plus" title="BANKpay+ Instant SEPA Wallet"><img src="img/bankpay.plus-logo.png" alt="BANKpay+ Logo - black simple font on white background." /></a>
      </div>
    </div>
  </div>

  <footer class="page-footer light-blue lighten-2">
    <div class="container">
      <div class="row">
        <div class="col l12 m12 s12">
          <h5 class="white-text">Wie funktioniert das?</h5>
          <p class="grey-text text-lighten-4">
            Nutze am besten dein <a target="_blank" href="https://bunq.eu">bunq Konto</a> zum einfachen und sicheren bezahlen von Rechnungen. Nie mehr abtippen.<br /> Jedes bestehende IBAN Konto kann benutzt werden.
          </p>
          <p class="grey-text text-lighten-4">
            Rechnung im PDF Format an <a href="mailto:rechnung@<?php echo $tld ?>">rechnung@<?php echo $tld ?></a> senden. Die Rechnungsdaten werden von <a target="_blank" href="https://gini.net">Gini</a> &mdash; wie von Geisterhand &mdash; erkannt &amp; du bekommst umgehend eine Antwort-Email an deine Absenderadresse mit einem Link zum bezahlen mit deinem IBAN Konto. Kein lästiges Online Banking Formular ausfüllen. Nie wieder!
          </p>
          <p class="grey-text text-lighten-4">
            Kontrolliere mit einem Blick, ob Gini alles richtig erkannt hat.
            Die Transaktion muss jetzt nur mehr mit deinem Überweisungs-PIN bestätigt und die Zahlung via Number26 Mobile App freigeben werden.
          </p>

          <p class="grey-text text-lighten-4">
            <strong>SICHER</strong>: Die Interaktionen mit deinem Konto laufen über eine verschlüsselte SSL Verbindung ab. Keine Daten werden an <?php echo $tld ?> oder eine andere dritte Partei gesendet &mdash; alles passiert in deinem Browser und bei Number26.<br />
            Die Rechnungen und extrahierte Daten werden in einem deutschen Rechen&shy;zentrum (ISO 27001 zertifiziert) bis zur erfolgreichen Bezahlung <a target="_blank" href="http://developer.gini.net/gini-api/html/guides/cms-encryption.html">verschlüsselt gespeichert</a> und danach sofort gelöscht. Nicht bezahlte Rechnungen und deren Daten werden nach 12 Stunden automatisch und zuverlässig gelöscht &mdash; <a target="_blank" href="https://www.gini.net/datenschutz/">Datenschutz</a> bei Gini.
          </p>

          <p class="grey-text text-lighten-4">
            <strong>OFFEN</strong>: Der Quellcode der verwendeten <a target="_blank" href="https://github.com/evo42/number26-api">Number26 JavaScript API</a> und der gesamten <a target="_blank" href="https://github.com/evo42/willzahlen"><?php echo $tld ?> Applikation</a> ist als Open Source Software veröffentlicht. Einfach mal reinlesen oder auf deinem eigenen Server installieren.
          </p>

          <p class="grey-text text-lighten-4">
            Bei Fragen sende eine Nachricht an <a target="_blank" href="mailto:rene.kapusta@gmail.com">rene.kapusta@gmail.com</a> oder ruf' einfach an: <a  href="callto:+436605083280">+43 660 5083280</a> &mdash; <a target="_blank" href="https://twitter.com/rene_kapusta">Stalken</a> ist auch erlaubt.
          </p>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
       BANKpay+ ™ | K42 Ventures OÜ 🇪🇺 <a href="https://github.com/evo42/willzahlen">Open Source Software</a> <a href="https://LinkedIn.com/in/renekapusta/">@rene_kapusta</a>
      </div>
    </div>
  </footer>

  <script src="/js/jquery.min.js"></script>
  <!-- script src="/js/zepto.min.js"></script>
  <script src="/js/deferred.js"></script -->
  <script src="/js/iban.js"></script>
  <script src="/js/drops.js"></script>
  <script src="/js/favico.js"></script>
  <script src="/js/materialize.min.js"></script>
  <script src="/js/init.js"></script>

  <!-- script src="https://assets.what3words.com/sdk/v3/what3words.js?key=NZ49V740"></script -->
  </body>
</html>
