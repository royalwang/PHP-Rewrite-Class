Rewrite Class
------------


Example #1:
------------
    *PHP FILE*
```php
    <?php
    	include (DIRNAME(DIRNAME(__FILE__)).'/src/rewrite.class.php');
		$rewrite = new rewrite('http://localhost/rewrite/examples/');
		$_GET = $rewrite->get();
		$_REWRITE= $rewrite->rewrite();
    ?>
```