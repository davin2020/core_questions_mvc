<?php

namespace App;

// updated for CoreQuestions
class DBConnector
{
	public function __invoke() 
	{
		//host needs to be 127 instead of localhost, but why? - need to adjust this for own local db setup or abstract to config file
		$db = new \PDO('mysql:host=127.0.0.1;dbname=corelifedb','root', 'password');
		$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
		// below line  makes PDO give error messages if it has issues trying to update the db, otherwise it would fail silently & we wouldnt know
		$db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		return $db;
	}
}