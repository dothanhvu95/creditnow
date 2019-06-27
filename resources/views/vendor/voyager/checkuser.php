<?php 
	if(!Auth::user()){
	    $url = 'http://' . $_SERVER['HTTP_HOST'] . '/backend/admin/login';
	    header( 'Location: '.$url );die;    
	} 
                