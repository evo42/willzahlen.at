<?php
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

require 'vendor/autoload.php';

use Proxy\Factory;
use Proxy\Response\Filter\RemoveEncodingFilter;
use Symfony\Component\HttpFoundation\Request;


// Create a Symfony request based on the current browser request.
$request = Request::createFromGlobals();

// Forward the request and get the response.
$response = Factory::forward($request)->to($_REQUEST['url']);

// Output response to the browser.
// echo $response->getContent();
$response->send();
