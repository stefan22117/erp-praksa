<?php
    /*
    if(empty($filename)) $filename = 'unknown'; 
    header('Content-Disposition: attachment; filename="'.$filename.'.pdf"');
    echo $content_for_layout;
    */
    if(empty($filename)) $filename = 'unknown'; 
    //header("Content-type: application/pdf"); 
    echo $content_for_layout; 
?>