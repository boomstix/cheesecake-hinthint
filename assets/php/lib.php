<?php

// Simple sql update using sql param string
/*
$sql_str = "
UPDATE `register` SET
`first_name` = :first_name,
`last_name` = :last_name
WHERE id = :register_id
;"

$sql_params = array(
	':first_name' => $first_name,
	':last_name' => $last_name,
	':register_id' => $register_id
);
*/
function mysql_execute($sql_str, $sql_params) {

	$data = null;
	
	// var_dump($sql_str);
	// var_dump($sql_params);

	try {

		$conn = new PDO("mysql:host=".$GLOBALS['db_host'].";dbname=".$GLOBALS['db_schema'], $GLOBALS['db_username'], $GLOBALS['db_password']);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		if (!$conn) {
			$GLOBALS['db_ex_msg'] = "Could not open connection to the database";
			$GLOBALS['db_err'] = true;
		
		}
		else {
	
				$stmt = $conn->prepare($sql_str);
				if (!$stmt->execute($sql_params))
				{
					$GLOBALS['db_err'] = true;
					$GLOBALS['db_ex_msg'] = 'Update statement failed';
				}

		}
		$conn = null;
	}
	catch (PDOException $e) {
		$GLOBALS['db_err'] = true;
		$GLOBALS['db_ex_msg'] = $e->getMessage();
		$conn = null;
	}
	
	return $data;
}


function mysql_select_rows($sql_str, $sql_params = null) {

	$data = null;

	// var_dump($sql_str);
	// var_dump($sql_params);
	
	try {

		$conn = new PDO("mysql:host=".$GLOBALS['db_host'].";dbname=".$GLOBALS['db_schema'], $GLOBALS['db_username'], $GLOBALS['db_password']);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		if (!$conn) {
			$GLOBALS['db_ex_msg'] = "Could not open connection to the database";
			$GLOBALS['db_err'] = true;
		
		}
		else {
	
			$stmt = $conn->prepare($sql_str);
			$stmt->setFetchMode(PDO::FETCH_BOTH);
		
			if (!$stmt->execute($sql_params))
			{
				$GLOBALS['db_err'] = true;
				$GLOBALS['db_ex_msg'] = 'Select statement failed';
			}
			else {
				$data = $stmt->fetchall();
			}
		
		}
		$conn = null;
	}
	catch (PDOException $e) {
		$GLOBALS['db_err'] = true;
		$GLOBALS['db_ex_msg'] = $e->getMessage();
		$conn = null;
	}
	catch (Exception $e) {
		$GLOBALS['db_err'] = true;
		$GLOBALS['db_ex_msg'] = $e->getMessage();
		$conn = null;
	}
	
	return $data;
}

?>