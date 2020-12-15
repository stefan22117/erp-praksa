<?php
// Your View
foreach($controllers as $controller => $methods) {
	foreach($methods as $method){
		echo $controller."/".$method. "<br/>";	
	}	
}
?>