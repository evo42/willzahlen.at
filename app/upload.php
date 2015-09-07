<?php

require_once 'config.php';
require_once 'gini.php';

$path = '/rechnung/';
$allowedFileTypes = array('pdf', 'png','jpg','gif','jpeg');

if (!empty($_FILES)) {
    $tempFile = $_FILES['file']['tmp_name'];

    if (!checkFileType(basename($_FILES['file']['name']), $allowedFileTypes)) {
        $A['status'] = 0;
        $A['answer'] = 'file extension not supported';

        header('Content-type: application/json');
        echo json_encode($A);
        die();
    }

    $gini = new gini();
    $status = $gini->upload($tempFile);

    header('Content-type: application/json');
    echo json_encode(array('url' => $path.$status['document_id']));
    die();
}

// does uploaded file has allowed extension?
function checkFileType($filename, $allowedFileTypes)
{
    $ext = getExtension($filename);

    return in_array($ext, $allowedFileTypes);
}

// get the file extension
function getExtension($filename)
{
    $extension = substr($filename, strrpos($filename, '.') + 1);

    return $extension;
}
