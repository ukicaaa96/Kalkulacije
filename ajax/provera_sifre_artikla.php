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

if ($podaciArtikla->num_rows > 0) {

	while ($row = $podaciArtikla->fetch_assoc()) {

		$idArtikla = 	$row['art_cdiartikal'];
		$nazivArtikla=	$row['art_dssnaziv'];
		$idGrupe=		$row['gra_cdigrupaartikla'];
		$nazivGrupe=	$row['gra_dssnaziv'];
		$porez= 		$row['pos_vlniznos'];
	
	}

//ARTIKLI ------------------------------------------------------------------------------------------------

		$htmlArtikli = "";

		$sqlArtikli = "SELECT * FROM artikli where art_cdigrupaartikla = ".$idGrupe;
		$podaciArtikli = $conn->query($sqlArtikli);

		$htmlArtikli .= '<select class="selektovan-artikal" id="modal-artikli" name="artikal">';
		while ($row = $podaciArtikli->fetch_assoc()) {

			if ($row['art_cdiartikal'] == $idArtikla) {

				$htmlArtikli .= '<option selected value="'.$row['art_cdiartikal'].'">'.$row['art_dssnaziv'].'</option>';
			}
			else{

				$htmlArtikli .= '<option value="'.$row['art_cdiartikal'].'">'.$row['art_dssnaziv'].'</option>';
			}

		}

		$htmlArtikli .= ' </select>';

//GRUPE-------------------------------------------------------------------------------------------------------------------

	   	$htmlGrupe = "";

		$sqlGrupe = "SELECT * FROM grupeartikla";
		$podaciGrupe = $conn->query($sqlGrupe);

		$htmlGrupe .= '<label for="usr">Grupa</label> <select class="selektovana-grupa" id="modal-grupe" name="grupa">';
		while ($row = $podaciGrupe->fetch_assoc()) {

			if ($row['gra_cdigrupaartikla'] == $idGrupe) {

				$htmlGrupe .= '<option selected value="'.$row['gra_cdigrupaartikla'].'">'.$row['gra_dssnaziv'].'</option>';
			}
			else{

				$htmlGrupe .= '<option value="'.$row['gra_cdigrupaartikla'].'">'.$row['gra_dssnaziv'].'</option>';
			}

		}

		$htmlGrupe .= ' </select>'; 

//POREZ---------------------------------------------------------------------------------------------------------------------

		$idMagacin = $_COOKIE['id-magacin'];

		$sqlPorez = 
		"SELECT * FROM artikli as a
		INNER JOIN poreskestope as p
		ON p.pos_cdiporeskastopa = a.art_cdiporeskastopa
		INNER JOIN artiklixmagacini as am 
		on am.axm_cdiartikal = a.art_cdiartikal
		where am.axm_cdimagacin = ".$idMagacin." and a.art_cdiartikal = ". $idArtikla;

		$podaciPorez = $conn->query($sqlPorez);

		while ($row = $podaciPorez->fetch_assoc()) {

			$porez = $row['pos_vlniznos'];
			$lager = $row['axm_vlnkolicina'];


		}

	$arr = [
		'poruka' => 	'ok',
		'artikli' => 	$htmlArtikli,
		'grupe' => 		$htmlGrupe,
		'porez' => 		$porez,
		'lager' =>		$lager
	];

	echo json_encode($arr);
		
}
else{
		$arr = ['poruka' => 'jok'];
		echo json_encode($arr);
	
}










?>