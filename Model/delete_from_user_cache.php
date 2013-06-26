<?php
    $dir_name = $_GET['dir_name'];
    $delete_lp = fopen('../PlotComparisons/'.$dir_name.'.lck', "w");
    $retries = 0;
    while ( !flock( $delete_lp, LOCK_EX | LOCK_NB ) ) {        //acquire an exclusive lock
     
        $retries++;
        if ( $retries >= 5 ) {
            echo "Couln't obtain file lock - Please reload";
            flock( $delete_lp, LOCK_UN );
            fclose( $delete_lp );
            die;
        } 
    }
    system( 'rm -rf ../PlotComparisons/'.$dir_name );
    flock( $delete_lp, LOCK_UN );
    fclose( $delete_lp );
    header( 'Location: /cmg-compare-validation-runs/index.php');
    die();
?>
