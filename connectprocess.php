<?php
// set your timezone
date_default_timezone_set('America/New_York');

// pull in the packages managed by Composer
require_once("vendor/autoload.php");

$log = new \Monolog\Logger('PHRETSDemo');
$log->pushHandler(
	new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG)
);

// setup your configuration		
$config = new \PHRETS\Configuration;
$config->setLoginUrl('http://neren.rets.paragonrels.com/rets/fnisrets.aspx/NEREN/login?rets-version=rets/1.7.2');
$config->setUsername('619764idx');
$config->setPassword('l8Ml4YeAv3qFFamm');
$config->setRetsVersion('1.5');
//Value shown below are the defaults used when not overridden
$config->setOption('use_post_method', true);
$config->setOption('disable_follow_location', true);
//$config->setUserAgent('PHRETS/2.0');
//$config->setUserAgentPassword($rets_user_agent_password); // string password, if given
$config->setHttpAuthenticationMethod('digest'); // or 'basic' if required 

// get a session ready using the configuration
$rets = new \PHRETS\Session($config); //$session = new \PHRETS\Session($config);
//$rets->setLogger($log);

// This will make the first request to the RETS server and this step is required to finalize the sessions configuration
$rets_connect = $rets->Login();



#Get Server MetaData
$system = $rets->GetSystemMetadata();
//echo "Server Name: " . $system->getSystemDescription();


/******GRABBING RECORDS******/

/*
$timestamp_field = 'LIST_87';
$property_classes = ['A', 'B', 'C'];

foreach ($property_classes as $pc) {
    // generate the DMQL query
    $query = "({$timestamp_field}=2000-01-01T00:00:00+)";

    // make the request and get the results
    $results = $rets->Search('Property', $pc, $query);

}
*/

#BASIC SEARCH FOR 'ACTIVE' LISTINGS
$results = $rets->Search('Property', 'RE_1', "(L_UpdateDate=2018-01-01T00:00:00+),(L_State=NH),(L_Status=1_0)", array('QueryType' => 'DMQL2', 'Count' => 1, 'Format' => 'COMPACT-DECODED', 'Limit' => 2, 'StandardNames' => 0));

#SEARCH SPECIFIC LISTING

//$results = $rets->Search('Property', 'RE_1', "(L_ListingID=4852071)", array('QueryType' => 'DMQL2', 'Count' => 1, 'Format' => 'COMPACT-DECODED', 'Limit' => 1, 'StandardNames' => 0));


echo '<pre>';
//file_put_contents('data/Property_' . $pc . '.csv', $results->toCSV());

print_r($results->toArray()); //Print array of array
//exit:

$listings = $results->toArray();

foreach ($listings as $listing) {
    print_r($listing); // Print each listing as an array
    echo '<hr>';
}

	
#SAVE RESULTS IN A LOCAL FILE
/*  FILE_APPEND if file already exists, append the data to the file instead of overwriting it
file_put_contents('data/Property_' . $pc . '.csv', $results->toCSV(), LOCK_EX);
	
//file_put_contents('C:\xampp\htdocs\testsoftwareonly\results1.csv', $results->toCSV(), LOCK_EX);
*/

#PRINT RESULTS AS "Address: "
/* foreach ($results as $record) {
	if(empty($record['L_Address2'])) {
			echo "Address: " . $record['L_Address'] . ", " . $record['L_City'] . ", " . $record['L_State'] . " " . $record['L_Zip'] . " listed for " . "\$" . number_format($record['L_AskingPrice']) . "\n";	
	} else {
			$unit = str_replace(' ', '', $record['L_Address2']);
			echo "Address: " . $record['L_Address'] . " Unit " . $unit . ", " . $record['L_City'] . ", " . $record['L_State'] . " " . $record['L_Zip'] . " listed for " . "\$" . number_format($record['L_AskingPrice']) . "\n";	
	}
};

echo $results->getTotalResultsCount();
$log->info("Total Records: " . $results->count());
*/

#PRINT FIELDS AND RESPECTIVE DESCRIPTIONS
//		WORKS
/*
foreach ($results as $record) {
	$fields = $record->getFields();
};

foreach ($fields as $field) {
	if ($record->get($field) != "") {
	echo $field . " => " . $record->get($field) . "\n";
	}
}
*/

?>
