<?php
	
	/**
	* @param $config : an array that contain the information of the connection
	* @return : the link of the connexion
	*/
	function getPDOlink($config){
		try{
			//data source name
			$dsn = 'mysql:dbname='.$config['database'].';
					host='.$config['host'].';
					port='.$config['port'].';
					charset=utf8';
			return new PDO($dsn, $config['username'] , $config['password']);		
		}
		catch (PDOException $Exception){
			exit ('BDD Error Connection');
		}
	}

