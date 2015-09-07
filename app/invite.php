<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

require_once 'config.php';

// todo: a bit more security
// todo: add phone number support
$email = trim($_REQUEST['invite']);
$spam = false;

if (empty($email)) {
    $email = file_get_contents('php://input');
    $email = trim(urldecode(str_replace('invite=', '', $email)));
}

if (empty($email)) {
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'ERROR', 'msg' => 'empty e-mail address.'));
    die();
}

$files = array('testrechnung.pdf');
$path = __DIR__.'/../assets/';
$mailto = $email;
$from_mail = 'rechnung@'.$tld;
$from_name = 'rechnung@'.$tld;
$replyto = 'hallo@'.$tld;

$subject = '€ Welcome '.$email.'!';
$message = "Hello @ $tld &mdash; ".$email;
mail_attachment(array(), null, $replyto, $replyto, $replyto, $mailto, $subject, $message);

if (validEmail($email)) {
    // login
    $cmd = "curl -H 'Authorization: Basic $n26_bearer' -d 'username=".urlencode($n26_user).'&password='.urlencode($n26_pass)."&grant_type=password' -X POST $n26_api/oauth/token";

    $response = shell_exec($cmd);
    $json = json_decode($response);
    $access_token = $json->access_token;

    // send invitation
    $cmd = "curl -H 'Authorization: bearer $access_token' -H 'Content-Type: application/json' -H 'Accept: application/json' -d '{email: \"".$email."\"}' -X POST $n26_api/api/aff/invite";

    $response = shell_exec($cmd);
    // print_r($response);
    $json = json_decode($response);
    // print_r($json);
    // todo: response without content -- check for http status

    if (isset($json->status) && $json->status == 'OK') {
        $subject = '€ '.$tld.' Konto anlegen';
        $message = '<b>Hallo!</b><br/><br/>
        Sehr gut! Du kannst dein Number26 Konto sofort er&ouml;ffnen. Dauert nur ein paar Minuten -- siehe E-Mail mit Einladungs-Code von Number26.<br/><br/>
        Bei Fragen einfach auf dieses E-Mail antworten (hallo@'.$tld.').<br/><br/>
        Beste Gr&uuml;&szlig;e,<br/>hallo@'.$tld;

        mail_attachment(array(), null, $mailto, $from_mail, $from_name, $replyto, $subject, $message);
    } elseif (isset($json->title) && $json->title == 'Oooops') {
        $subject = '€ '.$tld.' Konto vorhanden';
        $message = '<b>Hallo!</b><br/><br/>
        Sehr gut! Du kannst dich einfach mit deinem Number26 Kontodaten anmelden.<br/><br/>
        Es sind keine Daten f&uuml;r Dritte zug&auml;nglich.<br /><br />
        Bei Fragen einfach auf dieses E-Mail antworten (hallo@'.$tld.').<br/><br/>
        Beste Gr&uuml;&szlig;e,<br/>hallo@'.$tld;

        mail_attachment(array(), null, $mailto, $from_mail, $from_name, $replyto, $subject, $message);
    } else {
        $subject = '€ Rechnung zum testen';
        $message = 'Hallo!<br/><br/>Hier ist deine Rechnung zum Testen.<br/><br/>Die Kontodaten sind echt. Das Geld erreicht einen Empf&auml;nger und wird für Kaffee verwendet. Danke f&uuml;r die Unterst&uuml;tzung von Open Source Software.<br/><br/>Bei Fragen einfach auf dieses E-Mail antworten (hallo@'.$tld.').<br/><br/>Beste Gr&uuml;&szlig;e,<br/> team@'.$tld;

        mail_attachment($files, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message);
    }
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'OK', 'msg' => $email.' is ok.'));
} else {
    header('Content-Type: application/json');
    echo json_encode(array('status' => 'ERROR', 'msg' => $email.' seems to be no valid e-mail address.'));
}

/**
 * Validate an email address.
 * Provide email address (raw input)
 * Returns true if the email address has the email 
 * address format and the domain exists.
 *
 * found @ http://www.linuxjournal.com/article/9585?page=0,3
 */
function validEmail($email)
{
    // return true;
    $isValid = true;

    $atIndex = strrpos($email, '@');
    if (is_bool($atIndex) && !$atIndex) {
        $isValid = false;
    } else {
        $domain = substr($email, $atIndex + 1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
            // local part length exceeded
         $isValid = false;
        } elseif ($domainLen < 1 || $domainLen > 255) {
            // domain part length exceeded
         $isValid = false;
        } elseif ($local[0] == '.' || $local[$localLen - 1] == '.') {
            // local part starts or ends with '.'
         $isValid = false;
        } elseif (preg_match('/\\.\\./', $local)) {
            // local part has two consecutive dots
         $isValid = false;
        } elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
            // character not valid in domain part
         $isValid = false;
        } elseif (preg_match('/\\.\\./', $domain)) {
            // domain part has two consecutive dots
         $isValid = false;
        } elseif (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace('\\\\', '', $local))) {
            // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace('\\\\', '', $local))) {
             $isValid = false;
         }
        }
        /*
        // todo
        if ($isValid && !(checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A'))) {
            // domain not found in DNS
         $isValid = false;
        }
        */
    }

    return $isValid;
}

// http://stackoverflow.com/a/13459244
function mail_attachment($files, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message)
{
    $uid = md5(uniqid(time()));

    $header = 'From: '.$from_name.' <'.$from_mail.">\r\n";
    $header .= 'Reply-To: '.$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= 'Content-Type: multipart/mixed; boundary="'.$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= '--'.$uid."\r\n";
    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";

    foreach ($files as $filename) {
        $file = $path.$filename;
        $name = basename($file);
        $file_size = filesize($file);
        $handle = fopen($file, 'r');
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));

        $header .= '--'.$uid."\r\n";
        $header .= 'Content-Type: application/octet-stream; name="'.$filename."\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= 'Content-Disposition: attachment; filename="'.$filename."\"\r\n\r\n";
        $header .= $content."\r\n\r\n";
    }

    $header .= '--'.$uid.'--';

    return mail($mailto, $subject, '', $header);
}
