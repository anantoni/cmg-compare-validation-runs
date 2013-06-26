<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Connection {
    private $conn;
    
    public function connect() {
        $this->conn = oci_connect ( "cmgbookkeepingtest", "DaiEnkaiEntei2012", '(DESCRIPTION=(ADDRESS= (PROTOCOL=TCP) (HOST=dbsrvg3305.cern.ch) (PORT=10121) )(LOAD_BALANCE=on)(ENABLE=BROKEN)
        ( CONNECT_DATA=(SERVER=DEDICATED)(SID=DEVDB11) (FAILOVER_MODE = (TYPE = SELECT)(METHOD = BASIC)(RETRIES = 200)(DELAY = 15))))' );
    
        return $this->conn;
    }


    public function search( $file_owner, $path_name, $validation_run_id, $component_dir, $analyzer_name, $root_file_name ) {
        $search_arguments = array();
        
        if ( $file_owner != "" )
            $search_arguments["file_owner"] = '%'.$file_owner.'%';
        else
            $search_arguments["file_owner"] = '%';

        if ( $path_name != "" )
            $search_arguments["path_name"] = '%'.$path_name.'%';
        else 
            $search_arguments["path_name"] = '%';
        
        if ( $validation_run_id != "" )
            $search_arguments["validation_run_id"] = '%'.$validation_run_id.'%';
        else
            $search_arguments["validation_run_id"] = '%';

        if ( $component_dir != "" )
            $search_arguments["component_dir"] = '%'.$component_dir.'%';
        else
            $search_arguments["component_dir"] = '%';

        if ( $analyzer_name != "" )
            $search_arguments["analyzer_name"] = '%'.$analyzer_name.'%';
        else
            $search_arguments["analyzer_name"] = '%';
        
        if ( $root_file_name != "" )
            $search_arguments["root_file_name"] = '%'.$root_file_name.'%';
        else
            $search_arguments["root_file_name"] = '%';

        $stmt = oci_parse( $this->conn, 'SELECT file_owner, path_name, validation_run_id, dir_name, analyzer_name, root_file_name
                                         FROM validation_root_files_details,
                                         ( SELECT dataset_id, file_owner, path_name FROM dataset_details 
                                           WHERE file_owner LIKE :file_owner
                                           AND path_name LIKE :path_name ) temp 
                                         WHERE temp.dataset_id = validation_root_files_details.dataset_id 
                                         AND validation_run_id LIKE :validation_run_id
                                         AND dir_name LIKE :dir_name 
                                         AND analyzer_name LIKE :analyzer_name
                                         AND root_file_name LIKE :root_file_name' );
        
        oci_bind_by_name($stmt, ':file_owner', $search_arguments["file_owner"] );
        oci_bind_by_name($stmt, ':path_name', $search_arguments["path_name"] );
        oci_bind_by_name($stmt, ':validation_run_id', $search_arguments["validation_run_id"] );
        oci_bind_by_name($stmt, ':dir_name', $search_arguments["component_dir"] );
        oci_bind_by_name($stmt, ':analyzer_name', $search_arguments["analyzer_name"] );
        oci_bind_by_name($stmt, ':root_file_name', $search_arguments["root_file_name"] );
        
        
        if ( !$stmt ) {
            $oerr = OCIError($this->conn); 
            echo "Fetch Code 1:".$oerr["message"]; 
            exit; 
        }
        
        $result = oci_execute( $stmt );
        if ( !$result ) {
            $oerr = OCIError($stmt); 
            echo "Fetch Code 1:".$oerr["message"]; 
            exit; 
        }
        
        // Fetch the results of the query
        echo "<div id='searchResultsTitle'> Search results </div>";
        echo "<form id='rootFileSelection' name='root_file_selection' method='get' action='../Model/compare2.php'>";
        echo "<table id='searchResultsTable'>";
        echo "<tr><th> File Owner </th><th> Path Name </th><th> Val. Run ID</th> <th> Component Dir.</th> <th> Analyzer</th> <th> Root File</th> <th> Compare </th></tr>";
        
        $counter = 1;
        while ($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
            echo "<tr class='searchResultsTableRow'>";
            $target_root_file = "";
            foreach ($row as $item) {
                echo "<td class='searchResultsTableCell'>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>";
                $target_root_file .= trim( $item, '/' ).'/';
                
            }
            echo "<td class='searchResultsTableCell'><input type='checkbox' name='root_file[]' onclick='chkcontrol(".($counter-1).")'; value ='".trim( $target_root_file, '/' ) ."'></td>";
            echo "</tr>";
            $counter++;
        }
        echo "</table> <div id='compareButtonSection'> <input id='compareButton' type='submit' value='Compare'> </div> </form>";        
    
    }
    
}

?>
