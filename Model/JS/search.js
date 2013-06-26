function chkcontrol(j) {
    var total = 0;
    var elements = document.getElementsByName( "root_file[]");
    for (var i = 0 ; i < elements.length ; i++){
        if ( elements[i].checked ){
            total = total +1;}
            if ( total > 2 ){
                alert( "Please Select only two" ); 
                elements[i].checked = false ;
                return false;
            }
    }
}

