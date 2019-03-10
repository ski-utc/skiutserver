<?php 
	try {
		$db = new PDO('mysql:host=sql.mde.utc;dbname=skiutc','skiutc','ZjDSbrYI');
	} catch(Exception $e) {
		die('Error: '.$e->getMessage());
	}
?>