<?php

include 'connectprocess.php';

/*
 * The returned value from a $rets->GetObject() call is a \Illuminate\Support\Collection object 
 * which allows many common array-like features as well as some helper methods.
 */
/*
$objects = $rets->GetObject($rets_resource, $object_type, $object_keys);

// grab the first object of the set
$objects->first();

// grab the last object of the set
$objects->last();

// throw out everything but the first 10 objects
$objects = $objects->slice(0, 10);
*/

/*
 * Each object within that collection is a \PHRETS\Models\BaseObject object with it's own set of helper methods:
 */
 /*
 $objects = $rets->GetObject( see above documentation );
foreach ($objects as $object) {
    // does this represent some kind of error
    $object->isError();
    $object->getError(); // returns a \PHRETS\Models\RETSError

    // get the record ID associated with this object
    $object->getContentId();

    // get the sequence number of this object relative to the others with the same ContentId
    $object->getObjectId();

    // get the object's Content-Type value
    $object->getContentType();

    // get the description of the object
    $object->getContentDescription();

    // get the sub-description of the object
    $object->getContentSubDescription();

    // get the object's binary data
    $object->getContent();

    // get the size of the object's data
    $object->getSize();

    // does this object represent the primary object in the set
    $object->isPreferred();

    // when requesting URLs, access the URL given back
    $object->getLocation();

    // use the given URL and make it look like the RETS server gave the object directly
    $object->setContent(file_get_contents($object->getLocation()));
}
*/

foreach ($results as $record) {
	
		$objects = $rets->GetObject('Property', 'Photo', $record['L_ListingID'], '*', 1);
		//var_dump ($objects);
		$fileEx = str_replace(" ", "-", $record['L_Address']);
		if (!is_dir("C:\\xampp\\htdocs\\testsoftwareonly\\images\\{$fileEx}\\")) {
			mkdir("C:\\xampp\\htdocs\\testsoftwareonly\\images\\{$fileEx}\\");
		} else {
			return;
		};
				
	//PUT IMAGES INTO FILE
	foreach ($objects as $object) {
	
		$opts = array(
			'http'=>array(
			'method'=>"GET",
			'header'=>"Accept-language: en\r\n" . "Cookie: foo=bar\r\n"
		)
		);

		$context = stream_context_create($opts);
		$data = $object->getLocation();

		// Open the file using the HTTP headers set above //
		$content = file_get_contents('http:'.$data, false, $context);
		$listing = $object->getContentId();
		$photoid = $object->getObjectId();
		
		file_put_contents("C:\\xampp\\htdocs\\testsoftwareonly\\images\\{$fileEx}\\".$fileEx.'-photo-'.$photoid.'.jpg', $content, LOCK_EX);
 };

};

/*
*************************

	*DISPLAY IMAGE
	
	
$fileimage = 'C:\xampp\htdocs\testsoftwareonly\images\image1.jpg';
$type = 'image/jpeg';
header('Content-Type:'.$type);
header('Content-Length: ' . filesize($fileimage));
readfile($fileimage);


*************************

	*Pulls all photos for 12345


$photos = $rets->GetObject("Property", "Photo", "12345");
foreach ($photos as $photo) {
        $listing = $photo['Content-ID'];
        $number = $photo['Object-ID'];

        if ($photo['Success'] == true) {
                file_put_contents("image-{$listing}-{$number}.jpg", $photo['Data']);
        }
        else {
                echo "({$listing}-{$number}): {$photo['ReplyCode']} = {$photo['ReplyText']}\n";
        }
}


*************************

	*Pulls all photo URLs for 12345


$photos = $rets->GetObject("Property", "Photo", "12345", "*", 1);
foreach ($photos as $photo) {
        $listing = $photo['Content-ID'];
        $number = $photo['Object-ID'];

        if ($photo['Success'] == true) {
                echo "{$listing}'s #{$number} photo is at {$photo['Location']}\n";
        }
        else {
                echo "({$listing}-{$number}): {$photo['ReplyCode']} = {$photo['ReplyText']}\n";
        }
}

*************************

	*Pull URL for 12345's #2 photo


$photos = $rets->GetObject("Property", "Photo", "12345", "2", 1);
foreach ($photos as $photo) {
        $listing = $photo['Content-ID'];
        $number = $photo['Object-ID'];

        if ($photo['Success'] == true) {
                echo "{$listing}'s #{$number} photo is at {$photo['Location']}\n";
        }
        else {
                echo "({$listing}-{$number}): {$photo['ReplyCode']} = {$photo['ReplyText']}\n";
        }
}

*************************

	*Pull URLs for 12345, 12346 and 12347's 3rd, 4th and 5th photos each


$photos = $rets->GetObject("Property", "Photo", "12345,12346,12347", "3,4,5", 1);
foreach ($photos as $photo) {
        $listing = $photo['Content-ID'];
        $number = $photo['Object-ID'];

        if ($photo['Success'] == true) {
                echo "{$listing}'s #{$number} photo is at {$photo['Location']}\n";
        }
        else {
                echo "({$listing}-{$number}): {$photo['ReplyCode']} = {$photo['ReplyText']}\n";
        }
}

*************************

	*Pulls all photos for 12345, encodes image data, and outputs to browser

$photos = $rets->GetObject("Property", "Photo", "12345", "*", 0);
foreach ($photos as $photo) {
        $listing = $photo['Content-ID'];
        $number = $photo['Object-ID'];

    if ($photo['Success'] == true) {
        $contentType = $photo['Content-Type'];
        $base64 = base64_encode($photo['Data']); 
		echo "<img src='data:{$contentType};base64,{$base64}' />";
    }
    else {
        echo "({$listing}-{$number}): {$photo['ReplyCode']} = {$photo['ReplyText']}\n";
    }
}
*******/


$rets->Disconnect();

?>