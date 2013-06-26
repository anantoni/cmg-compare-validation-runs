<?php
    system( 'rm -rf ../PlotComparisons/'.$_SERVER['ADFS_LOGIN'] );
    header( 'Location: /cmg-compare-validation-runs/index.php');
    die();
?>
