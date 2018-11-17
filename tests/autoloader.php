<?php
	
spl_autoload_register(function ($class_name) {
	$file = 'mock/' . $class_name . '.php';
	if (file_exists($file)) {
	    include $file;	
	} else {
		$file = '../src/classes/' . $class_name . '.php';
		if (file_exists($file)) {
		    include $file;	
		}
	}
});

?>