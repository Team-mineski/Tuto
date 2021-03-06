<?php

include_once "Patient.class.php";
include_once "XrayForm.model.php";
include_once "Database.model.php";


session_start();
if ((!(isset($_SESSION["username"]))) || ($_SESSION["type"] != "xray_lab")) {
    header("Location: ../restricted/index");
    return;
}
$database = Database::getInstance();
$patient = unserialize($_SESSION['patient']);
$regNo = $patient->getRegNo();
$bht = $patient->getBedNo();
$date = $_SESSION['request_date'];

$xray_form = new XrayForm(
    $database,
    $date,
    $regNo,
    $bht,
    htmlentities($_POST['sign']),
    htmlentities($_POST['signature']),
    date('Y-m-d'),
    htmlentities($_POST['xno']),
    htmlentities($_POST['xroom']),
    htmlentities($_POST['films']),
    htmlentities($_POST['remark']),
    htmlentities($_POST['signrad']),
    implode(',', $_POST['region'])
);
$xray_form->writeToTable();
