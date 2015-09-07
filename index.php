<?php
require_once 'app/config.php';

if (!isset($tld)) {
    die('config required');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <link rel="shortcut icon" href="/assets/favicon.ico" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Rechnung zahlen. Nicht abtippen.</title>

  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
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
      <br><br>
      <h1 class="header center blue-text">rechnung&#8203;@<?php echo $tld ?></h1>
      <div class="row center">
          <h5 class="header col s12 light">Rechnung&nbsp;zahlen. Nicht&nbsp;abtippen. Einfach&nbsp;weiterleiten.</h5>
      </div>
      <div id="invite-ok" class="row center hidden green-text">
          <p>Schau' in dein E-Mail Postfach &mdash; deine Number26 Einladung und die Rechnung zum testen ist unterwegs.</p>
      </div>
      <div id="invite-error" class="row center hidden red-text">
          <p>Fehler: Ist deine E-Mail Adresse gültig?</p>
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
      <div id="invite-info" class="row center">
          <p class="small">Du bekommst ein kostenloses Number26 Konto mit € 10 Guthaben und ein E-Mail mit einer Rechnung zum testen.</p>
      </div>
    </div>
  </div>


  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">timer</i></h2>
            <h5 class="center">Zeit sparen.</h5>

            <p class="light center">
                Suchen und abtippen von Rechnungs&shy;daten nervt.<br />
                Kein Online-Banking Formular.<br />
                Einfach. Zeit sparen.<br />
            </p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
              <!-- inbox | send | insert_drive_file | check -->
            <h2 class="center light-blue-text"><i class="material-icons">email</i></h2>
            <h5 class="center">PDF senden.</h5>

            <p class="light center">
                Rechnungen kommen immer öfter als PDF Dokument per E-Mail an.<br />
                E-Mail an rechnung@<?php echo $tld ?> weiterleiten. Fertig.
            </p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">check</i></h2>
            <h5 class="center">Einfach zahlen.</h5>

            <p class="light center">
                Modernstes Online Banking.<br />
                Rechnungsdaten kontrollieren und Zahlung freigeben.<br />
                Sicher und einfach zahlen.
            </p>
          </div>
        </div>
      </div>

    </div>
    
    <div class="row center">
        <a href="https://number26.de/kosten/" target="_blank"><img src="img/online_banking_icon.png" /></a>
    </div>
    
    <br><br>

    <div class="section">

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
          <p class="grey-text text-lighten-4">
            Rechnung im PDF Format an <a href="mailto:rechnung@<?php echo $tld ?>">rechnung@<?php echo $tld ?></a> senden. Die Rechnungsdaten werden von <a target="_blank" href="https://gini.net">Gini</a> &mdash; wie von Geisterhand &mdash; erkannt &amp; du bekommst umgehend eine Antwort-Email an deine Absenderadresse mit einem Link zum bezahlen mit deinem Number26 Konto. Kein lästiges Online Banking Formular ausfüllen. Nie wieder!
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
          
          <br />
          <p class="grey-text text-lighten-4">
            Bei Fragen sende eine Nachricht an <a target="_blank" href="mailto:rene.kapusta@gmail.com">rene.kapusta@gmail.com</a> oder ruf' einfach an: <a  href="callto:+436605083280">+43 660 5083280</a> &mdash; <a target="_blank" href="https://twitter.com/rene_kapusta">Stalken</a> ist auch erlaubt.
        </p>
        <p class="grey-text text-lighten-4">
            <br /><br />
            This Non-Commerical project was made with &#9829; and coffein* <br /><br />@ <a target="_blank" href="http://www.diebox.info">dieBOX &mdash; coworkingspace</a> &mdash; Birkengasse 53, 3100 St. Pölten.<br /><br />
            *) <a target="_blank" href="https://www.facebook.com/FelixKaffee">Felix Kaffee</a> &amp; <a target="_blank" href="https://www.facebook.com/baernstein.dein.schluck">Bärnstein</a>
          </p>
        </div>

    </div>
    <div class="footer-copyright">
      <div class="container">
       SMART SEPA PAYMENT ™ | <a href="https://github.com/evo42/willzahlen">Open Source Software</a> | <a href="https://twitter.com/rene_kapusta">© Rene Kapusta</a>
      </div>
    </div>
  </footer>


  <script src="/js/jquery.min.js"></script>
  <!-- script src="/js/zepto.min.js"></script>
  <script src="/js/deferred.js"></script -->
  <script src="/js/drops.js"></script>
  <script src="/js/favico.js"></script>
  <script src="/js/materialize.min.js"></script>
  <script src="/js/init.js"></script>

  <script src="//static.getclicky.com/js" type="text/javascript"></script>
  <script type="text/javascript">try{ clicky.init(100873271); }catch(e){}</script>
  </body>
</html>
