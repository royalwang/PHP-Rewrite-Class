Rewrite class for friendly urls v1.0!
------------
Small, powerful and agile tool for friendly urls using PHP5.
An excellent aid to create your own framework.


Example #1: Basic
------------
    *PHP FILE*
```php
    <?php
    	include (DIRNAME(DIRNAME(__FILE__)).'/src/rewrite.class.php');
		$rewrite = new Rewrite('http://localhost/rewrite/examples/');
		$_GET = $rewrite->getMethod();
		$_REWRITE= $rewrite->segments();
    ?>
```


Example #2: SERVER
------------
    *PHP FILE*
```php
    <?php
    	include (DIRNAME(DIRNAME(__FILE__)).'/src/rewrite.class.php');
		$rewrite = new Rewrite();
		$_SERVER = $rewrite->requestVarSimulator();
    ?>
```


Example #3: Create URL
------------
    *PHP FILE*
```php
    <?php
    	include (DIRNAME(DIRNAME(__FILE__)).'/src/rewrite.class.php');
		$rewrite = new Rewrite();
		$_SERVER = $rewrite->createUrl("/home/www/var/index.html");
    ?>
```


Example #4: Get var
------------
    *PHP FILE*
```php
    <?php
    	include (DIRNAME(DIRNAME(__FILE__)).'/src/rewrite.class.php');
		$rewrite = new Rewrite();
		$_SERVER = $rewrite->get("token",session_id());
    ?>
```
