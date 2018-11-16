<?php
	
spl_autoload_register(function ($class_name) {
    include "../src/classes/" . $class_name . '.php';
});

?>