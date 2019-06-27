<?php 
    echo "<script>alert('Bạn không có quyền thực hiện chức năng này');</script>"; 
    header( 'Location: http://' . $_SERVER['HTTP_HOST'] . '/backend/admin' );die;  
    