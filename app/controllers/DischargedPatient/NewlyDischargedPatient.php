<?php
include_once '../../classes/Patient.php';
include_once '../../views/HeaderAndFooter/Discharged.php';
include_once '../../models/DatabaseConnection/Database.php';
include '../../views/layouts/docmenu.php';

if (!(isset($_SESSION))) {
  session_start();
  if ((!(isset($_SESSION["username"]))) || ($_SESSION["type"] != "Doctor")) {
    header("Location: ../../../restricted/index");
    return;
  }
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../../../bootstrap/css/bootstrap.min.css" integrity="" crossorigin="anonymous">
  <link rel="stylesheet" href="../../../css/navNsideStyles.css">
  <link rel="stylesheet" href="../../../css/mainStyles.css">
  <title></title>
</head>

<body>
  <?php
  if (isset($_SESSION["Patient"])) {
    $patient = $_SESSION["Patient"];
  }

  $medical = Database::getInstance();
  $patientID = $_SESSION["PatientID"];

  $regNo = $patient->getRegNo();
  $name = $patient->getName();
  $age = $patient->getAge();
  $gender = $patient->getGender();
  $address = $patient->getAddress();
  $dob = $patient->getDOB();
  $diagnosis = $patient->getDiagnosis();
  $contact = $patient->getContact();
  $bedNo = $patient->getBedNo();
  $patient->goNext();

  $diagnosis = $medical->getDiagnosisID($diagnosis);
  $medical->enterData(
    "dischargedPatients",
    array(
      'RegNo', 'FullName', 'Gender', 'FullAddress',
      'DateOfBirth', 'DiagnosisID', 'BedNo', 'ContactNo', 'DischargedDate'
    ),
    array($regNo, $name, $gender, $address, $dob, $diagnosis['DiagnosisID'], $bedNo, $contact, date('Y-m-d'))
  );

  $medical->deleteData("patients", $patientID, "PatientID");
  $medical->moveData("history", "dischargedhistory", "RegNo", $regNo);
  $medical->deleteData("history", $regNo, "RegNo");
  $medical->deleteData("patients", $patientID, "RegNo");
  $medical->deleteData("xray_request_table", $regNo, "RegNo");
  $medical->deleteData("specimen_exam_request_table", $regNo, "RegNo");
  $medical->deleteData("microbio_request_table", $regNo, "RegNo");
  $medical->deleteData("ecg_request_table", $regNo, "RegNo");
  $medical->deleteData("biochemical_request_table", $regNo, "RegNo");

  $patient->displayUI();
  ?>