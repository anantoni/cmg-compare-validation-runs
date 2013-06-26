<?php
include '../View/header/header.php';
include '../Controller/cmgdbApi.php';
echo "<script src='JS/search.js'/></script>";
$file_owner = $_GET['file_owner'];
$path_name = $_GET['path_name'];
$validation_run_id = $_GET['validation_run_id'];
$component_dir = $_GET['component_dir'];
$analyzer_name = $_GET['analyzer_name'];
$root_file_name = $_GET['root_file_name'];
$connectionObject = new Connection();
$connectionObject->connect();
$connectionObject->search( $file_owner, $path_name, $validation_run_id, $component_dir, $analyzer_name, $root_file_name );
include '../View/footer/footer.php';
?>
