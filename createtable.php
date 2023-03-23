<?php

/*
Tables:

active_listings = all active records for use on client sites

sold_listings = same but sold data

photos = all active and sold photos

listings_raw = everything from the rets server

photos_raw = everything from the rets server
*/
include 'config.php';
include 'mysqli-connect.php';

$system = $rets->GetSystemMetadata();

$resources = $system->getResources();

// array of available property types
$classes = $rets->GetClassesMetadata('Property');

// manually setting resouce and class (class = Property Type)
$resource = "Property";
$classes = ['RE_1', 'LD_2', 'RN_7'];

foreach ($classes as $class){
$table_name = "rets_".strtolower($resource)."_".strtolower($class);
$rets_metadata = $rets->GetTableMetadata($resource,$class);
//foreach ($rets_metadata as $field) {
  //      echo "+ Field {$field['SystemName']} is a {$field['DataType']} type\n";
//}


$sql = create_table_sql_from_metadata($table_name, $rets_metadata, "L_ListingID");
//echo $sql;

if ($conn->query($sql) === TRUE) {
  echo "Table {$table_name} created successfully!";
} else {
  echo "Error creating table: " . $conn->error;
}

mysqli_close($conn);
}

function create_table_sql_from_metadata($table_name, $rets_metadata, $key_field, $field_prefix = "") {
	$sql_query = "CREATE TABLE IF NOT EXISTS ".$table_name." (\n";
	foreach ($rets_metadata as $field) {
		$cleaned_comment = addslashes($field->getLongName());
		if ($field->getSystemName() == "L_ListingID") {
			$sql_make = "\t`L_ListingID` INT(10) NOT NULL AUTO_INCREMENT ";
		} else {
		$sql_make = "\t`" . $field_prefix . $field->getSystemName()."` ";
		if ($field->getInterpretation() == "LookupMulti") {
			$sql_make .= "TEXT";
		} elseif ($field->getInterpretation() == "Lookup") {
			$sql_make .= "VARCHAR(50)";
		} elseif ($field->getDataType() == "Int" || $field->getDataType() == "Small" || $field->getDataType() == "Tiny") {
			$sql_make .= "INT(".$field->getMaximumLength().")";
		} elseif ($field->getDataType() == "Long") {
			$sql_make .= "BIGINT(".$field->getMaximumLength().")";
		} elseif ($field->getDataType() == "DateTime") {
			$sql_make .= "DATETIME default '0000-00-00 00:00:00' NOT NULL";
		} elseif ($field->getDataType() == "Character" && $field->getMaximumLength() <= 255) {
			$sql_make .= "VARCHAR(".$field->getMaximumLength().")";
		} elseif ($field->getDataType() == "Character" && $field->getMaximumLength() > 255) {
			$sql_make .= "TEXT";
		} elseif ($field->getDataType() == "Decimal") {
			$pre_point = ($field->getMaximumLength() - $field->getPrecision());
			$post_point = !empty($field->getPrecision()) ? $field->getPrecision() : 0;
			$sql_make .= "DECIMAL({$field->getMaximumLength()},{$post_point})";
		} elseif ($field->getDataType() == "Boolean") {
			$sql_make .= "CHAR(1)";
		} elseif ($field->getDataType() == "Date") {
			$sql_make .= "DATE default '0000-00-00' NOT NULL";
		} elseif ($field->getDataType() == "Time") {
			$sql_make .= "TIME default '00:00:00' NOT NULL";
		} else {
			$sql_make .= "VARCHAR(100)";
		}
	}
		$sql_make .=  " COMMENT '".$cleaned_comment."',\n";
		$sql_query .= $sql_make;
	}
	$sql_query .=  "PRIMARY KEY(`".$field_prefix.$key_field."`) )";
	return $sql_query;
}



?>