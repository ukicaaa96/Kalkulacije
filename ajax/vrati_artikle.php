<?php
include_once '../connection.php';

$artikal = preg_replace("/[^A-Za-z0-9?!.()\-\/\+\_\ ]/",'',$_POST['q']);

$artikli ='SELECT art_cdiartikal, art_dssnaziv, art_dsssifra, art_dssbarcode
	FROM artikli
	WHERE art_cdiartikal > 0
	AND (
	art_dssnaziv LIKE "%'.$artikal.'%" OR art_dssbarcode LIKE  "%'.$artikal.'%" 
	OR art_dsssifra LIKE "%'.$artikal.'%")
	AND art_oplaktivan = 1
	ORDER BY art_dssnaziv ASC, art_cdiartikal ASC';


$podaci = $conn->query($artikli);

$finalArr = [];

if($podaci->num_rows){
	while ($row = $podaci->fetch_assoc()) {
		$arr = [
			'id'	=> $row['art_cdiartikal'],
			'text'	=> $row['art_dssnaziv']
		];

		array_push($finalArr, $arr);
	    
	}
	echo json_encode(['res'=>$finalArr],JSON_UNESCAPED_UNICODE);
}

else{
	echo json_encode(['res'=>[]],JSON_UNESCAPED_UNICODE);
}

?>
