<?php
include_once '../connection.php';
$sifra = $_POST['sifra'];

$sql = "
SELECT * FROM artikli 
INNER JOIN grupeartikla
ON artikli.art_cdigrupaartikla = grupeartikla.gra_cdigrupaartikla
INNER JOIN poreskestope
ON artikli.art_cdiporeskastopa = poreskestope.pos_cdiporeskastopa
WHERE art_dsssifra =  ". $sifra;

$podaciArtikla = $conn->query($sql);

try{
	if ($podaciArtikla->num_rows > 0) {

		while ($row = $podaciArtikla->fetch_assoc()) {

			$idArtikla = 	$row['art_cdiartikal'];
			$nazivArtikla=	$row['art_dssnaziv'];
			$idGrupe=		$row['gra_cdigrupaartikla'];
			$nazivGrupe=	$row['gra_dssnaziv'];
			$porez= 		$row['pos_vlniznos'];
		

			$arr = [
				'poruka' 		=> 'ok',
				'artikal-id' 	=> $idArtikla,
				'naziv-artikla'	=> $nazivArtikla,
				'grupa-id'		=> $idGrupe,
				'naziv-grupe'	=> $nazivGrupe,
				'porez'			=> $porez
			];

			echo json_encode($arr);
		    
		}
		
	}

	else{
		$arr = ['poruka' => 'jok'];
		echo json_encode($arr);
	}
}


catch(Exception $e){
	$arr = ['poruka' => 'jok'];
	echo json_encode($arr);
}









?>