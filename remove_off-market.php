<?php

/*
	* Query the live RETS Feed for MLS # only
	* Compare MLS # from live RETS with SQL Database
	* Remove from Database any record with MLS # that's not in current/live RETS Feed
					
*/

include 'mysqli-connect.php';
include 'config.php';

    $resource = "Property";
	$class = "RE_1";
	$table_name = "rets_".strtolower($resource)."_".strtolower($class);
	$query = "(L_Status=1_0)";
	
	$rets_metadata = $rets->GetTableMetadata($resource, $class);
	
	$maxrows = true;
    $offset = 1;
    $limit = 250;
	
	$mls_array = array();
	
/* Query the current/live RETS Feed for all MLS#, then add records to an array. */
	while ($maxrows) {
		$results = $rets->Search(
		$resource,
		$class,
		$query,
		[
			'QueryType' => 'DMQL2',
			'Count' => 1, // count and records
			'Format' => 'COMPACT-DECODED',
			'Limit' => $limit, 
			'Offset' => $offset,
			'StandardNames' => 0, // give system names
			'Select' => 'L_DisplayID'
		]
		);
		
		if ($results->getReturnedResultsCount() > 0) {
			foreach ($results as $record) {
				$mls_num = $record->get('L_DisplayID');
				$mls_array[$mls_num] = TRUE;		
			}
			$offset = ($offset + $results->getReturnedResultsCount());
		}
		
		$maxrows = $results->isMaxRowsReached();
	}

	
	$sql = "SELECT `L_DisplayID` FROM " . $table_name; //Select from Active Listings table in Database
	
	$check = $conn->query($sql);
	
	if ($check->num_rows > 0) {
		while($row = $check->fetch_assoc()) { //fetch_assoc fetches each row as an associative array, where each $key in the array represents one of the column names
			$mls_num = $row['L_DisplayID'];
			if(!array_key_exists($mls_num, $mls_array)) {
				echo "#" . $mls_num . " is NOT active.</br>";
				$sql = "DELETE FROM  " . $table_name . " WHERE `L_DisplayID` = " . $mls_num;
	
				if ($conn->query($sql) === TRUE) {
					echo "Successfully deleted.</br>";
				}
				else {
					echo "Error updating table: " . $conn->error;
				}
			}
		}
	}
	
	echo "</br>" . $table_name . " updated to only active listings.";
 
	
?>