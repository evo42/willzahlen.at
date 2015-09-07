<?php

// https://github.com/JMensch/attachment-scraper
// https://gist.github.com/tedivm/7932042
// http://www.damnsemicolon.com/php/php-pipe-bounce-emails
// http://www.damnsemicolon.com/php/php-parse-emails-email-piping-attachments-part-3


error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

echo '... ';
require_once 'config.php';
require_once 'gini.php';
echo '... ';

class Email_reader
{
    // imap server connection
    public $conn;

    // inbox storage and inbox message count
    private $inbox;
    private $msg_cnt;

    // email login credentials
    private $server;
    private $user;
    private $pass;
    private $port;

    // connect to the server and get the inbox emails
    public function __construct()
    {
        global $mail_user, $mail_pass, $mail_server, $mail_port;
        // email login credentials
        $this->server = $mail_server;
        $this->user = $mail_user;
        $this->pass = $mail_pass;
        $this->port = $mail_port;

        $this->connect();
        $this->inbox();
    }

    // close the server connection
    public function close()
    {
        $this->inbox = array();
        $this->msg_cnt = 0;

        imap_close($this->conn);
    }

    // open the server connection
    public function connect()
    {
        $this->conn = imap_open('{'.$this->server.'/notls}', $this->user, $this->pass);
        echo '... ';
    }

    // move the message to a new folder
    public function move($msg_index, $folder = 'INBOX.Processed')
    {
        // move on server
        imap_mail_move($this->conn, $msg_index, $folder);
        imap_expunge($this->conn);

        // re-read the inbox
        $this->inbox();
        echo '... ';
    }

    public function delete($msg_index)
    {
        // move on server
        imap_delete($this->conn, $msg_index);
        imap_expunge($this->conn);

        // re-read the inbox
        $this->inbox();
    }

    // get a specific message (1 = first email, 2 = second email, etc.)
    public function get($msg_index = null)
    {
        if (count($this->inbox) <= 0) {
            return array();
        } elseif (!is_null($msg_index) && isset($this->inbox[$msg_index])) {
            return $this->inbox[$msg_index];
        }

        return $this->inbox[0];
    }

    // read the inbox
    public function inbox()
    {
        $this->msg_cnt = imap_num_msg($this->conn);

        $in = array();
        for ($i = 1; $i <= $this->msg_cnt; ++$i) {
            $in[] = array(
                'index' => $i,
                'header' => imap_headerinfo($this->conn, $i),
                'body' => imap_body($this->conn, $i),
                'structure' => imap_fetchstructure($this->conn, $i),
            );
        }

        $this->inbox = $in;
    }
}

class email_invoice
{
    private $email_reader;
    private $run_count;

    public function cleanup_inbox()
    {
    }

    public function email_pull()
    {
        $this->email_reader = new Email_reader();

        // this method is run on a cronjob and should process all emails in the inbox
        while ($this->run_count < 100) {
            // get an email
            $email = $this->email_reader->get();
            ++$this->run_count;

            // if there are no emails, jump out
            if (count($email) <= 0) {
                break;
            }

            $attachments = array();

            // check for if attachment is in forwarded mail
            $sub = false;
            // print_r(create_part_array($email['structure']));
            if (isset($email['structure']->parts[1]->parts)) {
                // echo '<br />*** forwarded ***<br />';
                $sub = true;
                $email['structure']->parts = $email['structure']->parts[1]->parts;
            }

            if (isset($email['structure']->parts) && count($email['structure']->parts)) {
                // loop through all attachments
                for ($i = 0; $i < count($email['structure']->parts); ++$i) {
                    // set up an empty attachment
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => '',
                    );

                    // if this attachment has idfparameters, then proceed
                    if ($email['structure']->parts[$i]->ifdparameters) {
                        //echo 'ifdparameters<br />';
                        foreach ($email['structure']->parts[$i]->dparameters as $object) {
                            // if this attachment is a file, mark the attachment and filename
                            if (strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                            }
                        }
                    }

                    // if this attachment has ifparameters, then proceed as above
                    if ($email['structure']->parts[$i]->ifparameters) {
                        foreach ($email['structure']->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                            }
                        }
                    }

                    // if we found a valid attachment for this 'part' of the email, process the attachment
                    if ($attachments[$i]['is_attachment']) {
                        // get the content of the attachment
                        if ($sub) {
                            $attachments[$i]['attachment'] = imap_fetchbody($this->email_reader->conn, $email['index'], '2.2');
                        } else {
                            $attachments[$i]['attachment'] = imap_fetchbody($this->email_reader->conn, $email['index'], $i + 1);
                        }

                        // check if this is base64 encoding
                        if ($email['structure']->parts[$i]->encoding == 3) { // 3 = BASE64
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        }
                        // otherwise, check if this is "quoted-printable" format
                        elseif ($email['structure']->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
            }

            // get content from the email that I want to store
            $addr = $email['header']->from[0]->mailbox.'@'.$email['header']->from[0]->host;
            $sender = $email['header']->from[0]->mailbox;
            $text = (!empty($email['header']->subject) ? $email['header']->subject : '');

            $found_img = false;

            $data = array(
                'username' => $sender,
                'email' => $addr,
            );

            foreach ($attachments as $a) {
                echo '... ';

                if ($a['is_attachment'] == 1) {
                    // get information on the file
                    $finfo = pathinfo($a['filename']);

                    // if (preg_match('/(jpg|gif|png)/i', $finfo['extension'], $n)) {
                    if (preg_match('/(pdf)/i', $finfo['extension'], $n)) {
                        $found_img = true;
                        $this->_process_pdf($a['attachment'], $a['filename'], $data, $email);
                        break;
                    }
                }
            }

            // if there was no image, move the email to the Rejected folder on the server
            if (!$found_img) {
                $this->_process_pdf(null, null, $data, $email);
                continue;
            }
            // sleep(1);
        }
        // close the connection to the IMAP server
        $this->email_reader->close();
    }

    public function _process_pdf($file, $n, $data, $email)
    {
        global $tld, $protocol;
        // send to gini

        $status['extractions'] = false;
        if ($file) {
            $gini = new gini();
            $status = $gini->upload(null, $file);
        }
        $json = json_decode($status['extractions']);

        $uid = md5(uniqid(time()));

        $header = 'From: rechnung@'.$tld.' <rechnung@'.$tld.">\r\n";
        $header .= 'Reply-To: hallo@'.$tld."\r\n";
        $header .= 'X-Mailer: willzahlen/'.phpversion()."\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= 'Content-Type: multipart/mixed; boundary="'.$uid."\"\r\n\r\n";
        $header .= "This is a multi-part message in MIME format.\r\n";
        $header .= '--'.$uid."\r\n";
        $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $header .= $message."\r\n\r\n";

        if ($json->extractions && $json->extractions->docType->value != 'Other') {
            $subject = '€ Rechnung freigeben: '.$email['header']->subject;
            $nachricht = '<b>Hallo, die Rechnung ist fertig!</b><br /><br />&Uuml;berweisen: '.$protocol.'://'.$tld.'/rechnung/'.$status['document_id'].'<br /><br />';

            if (isset($json->extractions)) {
                if (isset($json->extractions->senderName)) {
                    $nachricht .= '<em>Empf&auml;nger:</em> '.$json->extractions->senderName->value.'<br />';
                }

                if (isset($json->extractions->iban)) {
                    $nachricht .= '<em>IBAN:</em> '.$json->extractions->iban->value.'<br />';
                }

                if (isset($json->extractions->bic)) {
                    $nachricht .= '<em>BIC:</em> '.$json->extractions->bic->value.'<br />';
                }

                if (isset($json->extractions->amountToPay)) {
                    $amount = str_replace(':EUR', ' EUR', $json->extractions->amountToPay->value);
                    $amount = str_replace('.', ',', $amount);

                    $nachricht .= '<em>Betrag:</em> '.$amount.'<br />';
                }

                if (isset($json->extractions->paymentReference)) {
                    $nachricht .= '<em>Referenz:</em> '.$json->extractions->paymentReference->value.'<br />';
                }

                $nachricht .= '<br /><br />';
            }

            $nachricht .= 'Fragen? Antworte auf diese Nachricht und wir helfen dir gerne weiter.<br /><br />
            Sch&ouml;nen Tag noch,<br />hallo@'.$tld;

            // move the email to Processed folder on the server
            $this->email_reader->move($email['index'], 'INBOX.Processed');
        } else {
            $subject = '€ '.$tld.' Fehler: '.$email['header']->subject;
            $nachricht = '<b>Hallo!</b><br/><br />
            In deiner E-Mail an uns wurden <b>keine Rechnungsdaten gefunden</b>.<br /><br />
            Sende eine <b>Rechnung</b> im <b>PDF Format</b> als <b>Anhang</b> an rechnung@'.$tld.'<br /><br />
            Hier ist eine <a href="'.$protocol.'://'.$tld.'/assets/testrechnung.pdf">Rechnung zum testen</a><br /><br />
            Fragen? Antworte auf diese Nachricht und wir helfen dir gerne weiter.<br /><br />
            Sch&ouml;nen Tag noch,<br />hallo@'.$tld;

            $this->email_reader->move($email['index'], 'INBOX.Rejected');
        }

        mail($data['username'].' <'.$data['email'].'>', $subject, $nachricht, $header, '-f hallo@'.$tld);
    }
}

$mailInvoice = new email_invoice();
$mailInvoice->email_pull();

function create_part_array($structure, $prefix = '')
{
    if (sizeof($structure->parts) > 0) {    // There some sub parts
        foreach ($structure->parts as $count => $part) {
            add_part_to_array($part, $prefix.($count + 1), $part_array);
        }
    } else {    // Email does not have a seperate mime attachment for text
        $part_array[] = array('part_number' => $prefix.'1', 'part_object' => $obj);
    }

    return $part_array;
}
// Sub function for create_part_array(). Only called by create_part_array() and itself. 
function add_part_to_array($obj, $partno, &$part_array)
{
    $part_array[] = array('part_number' => $partno, 'part_object' => $obj);
    if ($obj->type == 2) { // Check to see if the part is an attached email message, as in the RFC-822 type
        //print_r($obj);
        if (sizeof($obj->parts) > 0) {    // Check to see if the email has parts
            foreach ($obj->parts as $count => $part) {
                // Iterate here again to compensate for the broken way that imap_fetchbody() handles attachments
                if (sizeof($part->parts) > 0) {
                    foreach ($part->parts as $count2 => $part2) {
                        add_part_to_array($part2, $partno.'.'.($count2 + 1), $part_array);
                    }
                } else {    // Attached email does not have a seperate mime attachment for text
                    $part_array[] = array('part_number' => $partno.'.'.($count + 1), 'part_object' => $obj);
                }
            }
        } else {    // Not sure if this is possible
            $part_array[] = array('part_number' => $prefix.'.1', 'part_object' => $obj);
        }
    } else {    // If there are more sub-parts, expand them out.
        if (sizeof($obj->parts) > 0) {
            foreach ($obj->parts as $count => $p) {
                add_part_to_array($p, $partno.'.'.($count + 1), $part_array);
            }
        }
    }
}
