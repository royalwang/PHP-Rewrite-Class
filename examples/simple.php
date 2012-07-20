<?php
include (DIRNAME(DIRNAME(__FILE__)).'/src/rewrite.class.php');
$rewrite = new rewrite('http://localhost/rewrite/examples/');
$_GET = $rewrite->get();
$_REWRITE= $rewrite->rewrite();

/**
*	PRINT GET VARS
*/
highlight_string( print_r( $_GET ,true ) );

/**
*	PRINT REWRITE VARS
*/
highlight_string( print_r( $_REWRITE,true ) );
?>