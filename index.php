<?php include 'View/header/header.php' ?>
<div id='content'>
    <div id='searchSection'>
        <p><b> Search validation run root files </b></p>
        <form action='Model/search.php' method='get'>
            <table id='searchTable'>
                <tr><td>File owner:</td><td><input type='text' value='' name='file_owner'></td></tr>
                <tr><td>Dataset path name:</td><td><input type='text' value='' name='path_name'></td></tr>
                <tr><td>Validation Run ID:</td><td><input type='text' value='' name='validation_run_id'></td></tr>
                <tr><td>Component Directory:</td><td><input type='text' value='' name='component_dir'></td></tr>
                <tr><td>Analyzer:</td> <td><input type='text' value='' name='analyzer_name'>  </td></tr>
                <tr><td>Root file Name:</td> <td><input type='text' value='' name='root_file_name'> </td></tr>
            </table>
            <div id='searchButtonSection'><input type='submit' value='Search'></div>
        </form>
    </div>
    <div id='welcomeSection'>
        Welcome <?php print_r($_SERVER['ADFS_LOGIN']); ?>
    </div>
</div>
<?php include 'View/footer/footer.php' ?>
        
