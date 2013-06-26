<?php
$files = $_GET['root_file'];
if ( count( $files ) == 2 ) {
    $validation_run_library_path = $_SERVER['DOCUMENT_ROOT'].'/cmg-compare-validation-runs/ValidationRuns/';
    $keys = array_keys($files);
    $root_file_1 = $validation_run_library_path.$files[$keys[0]];
    $root_file_2 = $validation_run_library_path.$files[$keys[1]];            
    $out_dir = md5( $root_file_1.$root_file_2);

    if ( is_dir( "../PlotComparisons/".$out_dir ) ) {
        $read_lp = fopen( '../PlotComparisons/'.$out_dir.'.lck', "w");
        $retries = 0;
        while ( !flock( $read_lp, LOCK_SH | LOCK_NB ) ) {   // acquire a shared lock
            $retries++;
            usleep(round(rand(0, 100)*1000));
            if ( $retries >= 5 ) {
                fclose( $read_lp );
                echo 'Could not obtain file lock - Please reload';
                die;
            } 
            
        }
        if ( is_dir( "../PlotComparisons/$out_dir" ) ) {
            header( 'Location: /cmg-compare-validation-runs/PlotComparisons/'.$out_dir.'/comparison_results.php');
            flock( $read_lp, LOCK_UN );
            fclose( $read_lp );
            die();
        }
        else {
            flock( $read_lp, LOCK_UN );
            fclose( $read_lp ); 
        }
    }
    
    $fp1 = popen( './clean_cache_script.sh ', 'r' );                      //popen will execute shellscipt.sh
    if ( $fp1 ) {
        while(!feof($fp1)) {  
            $dir_to_delete = fgets( $fp1 );
            if ( $dir_to_delete != '' ) {
                $delete_lp = fopen('../PlotComparisons/'.$out_dir.'.lck', "w");
                $retries = 0;
                while ( !flock($delete_lp, LOCK_EX | LOCK_NB ) ) { // acquire an exclusive lock
                    $retries++;
                    usleep(round(rand(0, 100)*1000));
                    if ( $retries >= 5 ) {
                        fclose( $delete_lp );
                        echo 'Could not obtain file lock please reload';
                        die;
                    } 
                    
                }
                system( 'rm -rf ../PlotComparisons/'.$dir_to_delete );
                pclose( $fp1 );
                flock( $delete_lp, LOCK_UN );
                fclose( $delete_lp );
            }
        }
        
    }
    else 
        echo 'Cache size check failed<br>';
    /*
    * This is where the main script is run
    * It checks available cache space and user cache space and cleans the
    * user directory in case the cache is full and the user cache is above the allowed limit
    * then runs cmsenv, creates the user directory if it doesn't exist and 
    * calls fileComparator.py
    */
    $write_lp = fopen( '../PlotComparisons/'.$out_dir.'.lck', "w");
    $retries = 0;
    while ( !flock( $write_lp, LOCK_EX | LOCK_NB ) ) {   // acquire an exclusive lock
        $retries++;
        usleep(round(rand(2000, 5000)*1000));
        if ( $retries >= 5 ) {
            fclose( $write_lp );
            system( 'rm -rf ../PlotComparisons/'.$out_dir );
            echo 'Could not obtain file lock - Please reload';
            die;
        } 
    }
    $fp2 = popen( './shellscript2.sh '.$root_file_1.' '.$root_file_2.' '.$out_dir.' '.$files[$keys[0]].' '.$files[$keys[1]], 'r' );                      //popen will execute shellscipt.sh
    if ( $fp2 ) {
        while(!feof($fp2))
            fgets( $fp2 );
        pclose( $fp2 );
    }
    else 
        echo 'File Comparator execution Failed';
    
    header( 'Location: /cmg-compare-validation-runs/PlotComparisons/'.$out_dir.'/comparison_results.php');
    //flock( $write_lp, LOCK_UN );
    //fclose( $write_lp );
    die();

}
else {
    include( '../View/header/header.php' );
    echo '<div id=\'errorSection\'> <span id=\'errorMessage\'> ERROR - You need to select 2 root files for comparison </span> </div>';
    include( '../View/footer/footer.php' );
}
?>
