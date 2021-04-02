<?php

include_once '../connection.php';

//naziv-artikla-add
//grupa-artikla-add
//poreska-stopa-add


$naziv = 			$_POST['naziv-artikla-add'];
$grupa = 			$_POST['grupa-artikla-add'];
$poreskaStopa = 	$_POST['poreska-stopa-add'];


$maxArtikal = "SELECT max(art_cdiartikal) maksimum FROM artikli";
$podaciArtikal = $conn->query($maxArtikal);

while ($row = $podaciArtikal->fetch_assoc()) {

	$idArtikla = (int)$row['maksimum'] + 1;
    
}

$insertArtikli = 
"INSERT INTO artikli(art_dssnaziv, art_cdiartikal, art_cdigrupaartikla,
art_cdiporeskastopa,art_cdijedinicamere,art_cdidobavljac,art_dssproizvodjac,
art_dssnapomena, art_oplaktivan, art_nuibodovi, art_dssbarcode, art_dsssifra) 
VALUES ('".$naziv."' , ".$idArtikla.", ".$grupa.", ".$poreskaStopa.", 0,0,'','',1,0,'','')";

$conn->query($insertArtikli);

if ($conn->affected_rows) {

// MAX ID AXM ////////////////////////////////////////////////////////////////////////////

	$maxAXM = "SELECT max(axm_cdiartikalxmagacin) maksimum FROM artiklixmagacini";
	$podaciAXM = $conn->query($maxAXM);

	while ($row = $podaciAXM->fetch_assoc()) {

		$idAXM = (int)$row['maksimum'] + 1;
	
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////

	$provera = 0;

	$idArtXMag = $idAXM;

	$sql = "SELECT * FROM magacini where mag_cdimagacin > 0 ";
	$podaciMagacin = $conn->query($sql);

	while ($row=$podaciMagacin->fetch_assoc()) {

		$idMagacin = $row['mag_cdimagacin'];

		$insertAXM = 
		"INSERT INTO artiklixmagacini (axm_cdiartikalxmagacin, axm_cdiartikal,
		axm_cdimagacin, axm_vlnkolicina, axm_vlncenanabavna,axm_vlncenaprodajna,axm_vlnporeskastopa)
		VALUES (".$idArtXMag.", ".$idArtikla." , ".$idMagacin.", 0,0,0,0)";

	    $conn->query($insertAXM);

	    if ($conn->affected_rows) {
	    	$idArtXMag += 1;
	    	$provera += 0;
	    }
	    else{
	    	$provera += 1;
	    }
	}

//Ako su uneti svi redovi	    
  	if ($provera == 0) {
  		$arr = ['poruka' => 'ok'];
  		echo json_encode($arr);
  	 } 
  	 else{
  	 	$arr = ['poruka' => 'jok'];
  		echo json_encode($arr);
  	 }

}

else{
	$arr = ['poruka' => 'jok'];
	 echo json_encode($arr);
}






?>