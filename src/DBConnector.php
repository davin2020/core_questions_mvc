<?php

namespace App;

// updated for CoreQuestions
class DBConnector
{
	public function __invoke($container) 
	{

		// abstracted actual db connection details to settings.php file
		$dbSettings = $container->get('settings')['dbDetails'];

		$dbConnectionString = 'mysql:host=' . $dbSettings['dbServer']
			. ';dbname=' . $dbSettings['dbName'];

		$db = new \PDO($dbConnectionString, $dbSettings['dbUsername'], $dbSettings['dbPassword']);

		$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

		// below line makes PDO give error messages if it has issues trying to update the db, otherwise it would fail silently & we wouldnt know
		$db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		return $db;
	}
}