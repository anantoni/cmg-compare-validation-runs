<?php
$files = $_GET['root_file'];
if ( count( $files ) == 2 ) {
    $validation_run_library_path = $_SERVER['DOCUMENT_ROOT'].'/cmg-compare-validation-runs/ValidationRuns/';
    $keys = array_keys($files);
    $root_file_1 = $validation_run_library_path.$files[$keys[0]];
    $root_file_2 = $validation_run_library_path.$files[$keys[1]];            
    $out_dir = md5( $root_file_1.$root_file_2);

    if ( is_dir( '../PlotComparisons/'.$_SERVER['ADFS_LOGIN'].'/'.$out_dir ) ) {
        header( 'Location: /cmg-compare-validation-runs/PlotComparisons/'.$_SERVER['ADFS_LOGIN'].'/'.$out_dir.'/comparison_results.php');
        die();
    }
    /*
     * This is where the main script is run
     * It checks available cache space and user cache space and cleans the
     * user directory in case the cache is full and the user cache is above the allowed limit
     * then runs cmsenv, creates the user directory if it doesn't exist and 
     * calls fileComparator.py
     */
    $fp = popen( './shellscript.sh '.$_SERVER['ADFS_LOGIN'].' '.$root_file_1.' '.$root_file_2.' '.$out_dir.' '.$files[$keys[0]].' '.$files[$keys[1]], 'r' );                      //popen will execute shellscipt.sh
    if ( $fp ) 
        while(!feof($fp))  
            echo fgets( $fp ).'<br>';
    else 
        echo 'File Comparator execution Failed';
    header( 'Location: /cmg-compare-validation-runs/PlotComparisons/'.$_SERVER['ADFS_LOGIN'].'/'.$out_dir.'/comparison_results.php');
    die();

}
else {
    include( '../View/header/header.php' );
    echo '<div id=\'errorSection\'> <span id=\'errorMessage\'> ERROR - You need to select 2 root files for comparison </span> </div>';
    include( '../View/footer/footer.php' );
}
?>
