<?php
    if(isset($_GET["f"])){
        $f = $_GET["f"];
        header('Content-Disposition: attachment; filename='.urlencode($f));
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Description: File Transfer');
        header('Content-Length: ' . filesize($f));
        echo file_get_contents($f);
    }
    exit;
?>
