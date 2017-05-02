<?php
	try
	{
	 $dsn = "mysql:host=localhost;dbname=inventory";
	 $db = new PDO ($dsn, "webuser", "webpass");
	 $db->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	 // print ("Connected\n");
	}
	catch (PDOException $e)
	{
	 print ("Cannot connect to server\n");
	 print ("Error code: " . $e->getCode () . "\n");
	 print ("Error message: " . $e->getMessage () . "\n");
	}
	?>