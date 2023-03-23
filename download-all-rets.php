<?php
// set your timezone
date_default_timezone_set('America/New_York');
include 'config.php';
include 'mysqli-connect.php';



$system = $rets->GetSystemMetadata();
//echo "Server Name: " . $system->getSystemDescription();


/******GRABBING RECORDS******/


$timestamp_field = 'L_UpdateDate';
$resource = 'Property';
$property_classes = ['RE_1']; //, 'LD_2', 'RN_7'];
$offset = 1;
$tablename = 'rets_property_re_1';

foreach ($property_classes as $pc) {
    $query = "({$timestamp_field}=2010-01-01T00:00:00+),(L_Status=1_0)"; // L_Status: 1_0 = Active, 2_0 = Closed, 3_0 = Pending, 3_1 = Active Under Contract, 4_0 = Expired, 4_1 = Terminated, 4_2 = To Be Deleted, 4_3 = Delete, 5_0 = Withdrawn, 5_1 = Inactive Office, 6_0 = Leased
		
    $results = $rets->Search(
		'Property', 
		$pc, 
		$query,
		[
			'QueryType' => 'DMQL2',
			'Count' => 1, // count and records
			'Format' => 'COMPACT-DECODED',
			'Limit' => 30, 
			//'Offset' => $offset,
			'StandardNames' => 0, // give system names
			//'Select' => 'L_DisplayID, L_Status'
		]
	);
	
	$listings = $results->toArray(); // [0] => ('SystemName' => 'Desc', 'Systemname' =>.......)

	foreach ($listings as $listing) {
		
		$valuesd = array_map(array($conn, 'real_escape_string'), array_values($listing));
		$escaped_values = implode(", ", array_keys($listing));	//$field_names);
		$valuestring = implode("', '", $valuesd );
		
		$conn->query("INSERT IGNORE INTO $tablename ($escaped_values) VALUES ('$valuestring')");
	
		unset($valuesd);
		$valuesd = array();

		/*
		$prep = array();
		$keys = implode(", ", array_keys($listing));	//$field_names);
		foreach($listing as $key => $value ) {
			$prep[':'.$key] = $value;
		};
		$valuestring2 = implode(', ', array_keys($prep));
		$stmt = mysqli_prepare($conn, "INSERT INTO $tablename ($keys) VALUES ($valuestring2)");
		$sth->execute(array_values($listing));
		*/



	};

}


	$sql2 = "SELECT * FROM $tablename";
	$check2 = $conn->query($sql2);

	if ($check2->num_rows > 0) {
		echo "Returned rows are " . mysqli_num_rows($check2);
	} else {
		echo "No rows added!";
	}





/*
echo $results->getTotalResultsCount();
$log->info("Total Records: " . $results->count());
*/

?>
