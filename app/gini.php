<?php

class gini
{
    public $foo = 'bar';

    public function auth()
    {
        global $gini_user, $gini_pass, $gini_id, $gini_secret;

        $cmd = "curl -X POST --data-urlencode 'username=$gini_user' --data-urlencode 'password=$gini_pass' -H 'Content-Type: application/x-www-form-urlencoded' -H 'Accept: application/json' -u '$gini_id:$gini_secret' 'https://user.gini.net/oauth/token?grant_type=password'";

        $output = shell_exec($cmd);
        $json = json_decode($output);

        if (isset($json->access_token)) {
            $_SESSION['gini']['access_token'] = $json->access_token;
            $_SESSION['gini']['valid_until'] = time() + $json->expires_in;
        } else {
            $_SESSION['gini'] = array();
        }
    }

    public function upload($file = false, $file_data = false)
    {
        if (!isset($_SESSION['gini']['access_token']) ||
            (isset($_SESSION['gini']['valid_until']) && $_SESSION['gini']['valid_until'] < (time() - 100))) {
            $this->auth();
        }

        $url = 'https://api.gini.net/documents';

        $optional_headers = array(
                        'Authorization: Bearer '.$_SESSION['gini']['access_token'],
                        'Accept: application/vnd.gini.v1+json', );

        if ($file) {
            $data = file_get_contents($file);
        } else {
            $data = $file_data;
        }

        $params = array('http' => array(
                    'method' => 'POST',
                    'content' => $data,
                  ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            return false;
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            return false;
        }

        $hd = stream_get_meta_data($fp);

        foreach ($hd['wrapper_data'] as $value) {
            if (strpos($value, 'Location:') === 0) {
                $status_url = trim(str_replace('Location: ', '', $value));
                $document_id = trim(str_replace('Location: https://api.gini.net/documents/', '', $value));
            }
        }

        sleep(1);

        $optional_headers = array(
                        'Authorization: Bearer '.$_SESSION['gini']['access_token'],
                        'Accept: application/vnd.gini.v1+json', );

        $params = array('http' => array(
                    'method' => 'GET',
                  ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($status_url.'/extractions', 'rb', false, $ctx);
        if (!$fp) {
            return false;
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            return false;
        }
        $extractions = $response;

        $dir = str_replace('/app', '/rechnung', __DIR__).'/'.$document_id;
        mkdir($dir, 0755, true);

        file_put_contents($dir.'/index.php', file_get_contents(__DIR__.'/tpl.php'));

        file_put_contents($dir.'/data.json', $response);
        $ext = json_decode($response);

        $fp = @fopen($status_url.'', 'rb', false, $ctx);
        if (!$fp) {
            return false;
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            return false;
        }

        $doc = json_decode($response);

        $img = '';
        $img_url = $doc->pages[0]->images->{'750x900'};
        $optional_headers = array(
                        'Authorization: Bearer '.$_SESSION['gini']['access_token'],
                        'Accept: image/*', );

        $params = array('http' => array(
                    'method' => 'GET',
                  ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);

        $fp = @fopen($img_url.'', 'rb', false, $ctx);
        if (!$fp) {
            print_r("Problem with $url, $php_errormsg, ".print_r($_SESSION['gini'], true));

            return false;
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            print_r("Problem reading data from $url, $php_errormsg");

            return false;
        }

        file_put_contents($dir.'/invoice.jpg', $response);

        if (!empty($status_url)) {
            return array('document_id' => $document_id, 'document_url' => $status_url, 'access_token' => $_SESSION['gini']['access_token'], 'img' => $img, 'extractions' => $extractions);
        } else {
            return false;
        }
    }
}
