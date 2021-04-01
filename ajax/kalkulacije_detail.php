<?php
include_once "../connection.php";
$akcija = $_POST['akcija'];
$html = "";
if ($akcija == 'pretraga') {

	$idKalkulacije = $_POST['id'];

	$sql = "SELECT *
	FROM kalkulacijedetail AS k
	INNER JOIN artikli AS a ON a.art_cdiartikal = k.kad_cdiartikal
	WHERE k.kad_cdikalkulacijamain =" .$idKalkulacije;

	$podaci = $conn->query($sql);
	$i = 1;
	while ($row = $podaci->fetch_assoc()) {

		$artikal = 			$row['kad_cdiartikal'];
		$idDetalja = 		$row['kad_cdikalkulacijadetail'];
		$sifraArtikla = 	$row['art_dsssifra'];
		$nazivArtikla = 	$row['art_dssnaziv'];
		$kolicina = 		$row['kad_vlnkolicina'];
		$nabavnaCena = 		$row['kad_vlncenanab'];
		$rabat = 			$row['kad_vlnrabat'];
		$cenaSaPopustom = 	(int)$nabavnaCena - ((int)$rabat / 100) * (int)$nabavnaCena;
		$marza =			$row['kad_vlnmarza'];
		$cenaVp = 			((int)$marza/100)*(int)$cenaSaPopustom + (int)$cenaSaPopustom;

//////////////PORESKA STOPA///////////////////////////////////////////////////////////
		$poreskaId = $row['art_cdiporeskastopa'];
		$vratiPdv = "SELECT * FROM poreskestope WHERE pos_cdiporeskastopa = " . $poreskaId;
		$podaciPoreska = $conn->query($vratiPdv);

		while ($row=$podaciPoreska->fetch_assoc()) {
			$poreskaStopa = $row['pos_vlniznos'];
		}
//////////////////////////////////////////////////////////////////////////////////////////

		$cenaPdv = 			(int)$cenaVp+ ((int)$poreskaStopa/100)*(int)$cenaVp;

		$nabavnaVrednost = 	$cenaSaPopustom * (int)$kolicina;
		$vpIznos = 			$cenaVp * (int)$kolicina;
		$iznosPdv = 		$cenaPdv * (int)$kolicina;


		

		$html .= 
		'
            <tr>

                <td class="text-center">'.$i.'</td>
                <td class="kad-sifra">'.$sifraArtikla.'</td>
 				<td class="kad-artikal text-left" artikal-id="'.$artikal.'">'.$nazivArtikla.'</td>
				<td class="kad-kolicina text-right">'.number_format((float)$kolicina,2).'		</td>
				<td class="kad-nabavna text-right">'.number_format((float)$nabavnaCena,2).'		</td>
                <td class="kad-rabat text-right">'.number_format((float)$rabat,2).'			</td>
                <td class="kad-cenapopust text-right">'.number_format((float)$cenaSaPopustom,2).'</td>
                <td class="kad-marza text-right">'.number_format((float)$marza,2).'</td>
                <td class="kad-cenavp text-right">'.number_format((float)$cenaVp,2).'</td>
                <td class="kad-cenapdv text-right">'.number_format((float)$cenaPdv,2).'</td>
              	<td class="kad-nabavnavrednost text-right">'.number_format((float)$nabavnaVrednost,2).'</td>
                <td class="kad-vpiznos text-right">'.number_format((float)$vpIznos,2).'</td>
                <td class="kad-iznos text-right">'.number_format((float)$iznosPdv,2).'</td>
                <td class ="d-flex">
                    <div class="custom col-sm-6 p-1">
                    	<div class="izmenaDetalja float-right" data-id="'.$idDetalja.'" data-toggle="tooltip" data-placement="top" title="izmeni stavku">
                    		<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                 <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                            </svg>
                        </div>
                    </div>

					<div class="custom col-sm-6 p-1">
                        <div class="brisanjeDetalja" data-id="'.$idDetalja.'" data-toggle="tooltip" data-placement="top" title="izbriši stavku">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                            </svg>
                        </div>
                    </div>
                </td>
            </tr>
		';

		$i ++;

	    
	}
	
	if($html == ''){
		$arr = ['poruka' => 'jok'];
		echo json_encode($arr);

	}
	else{
		$arr = ['poruka'=>'ok', 'html' => $html];
		echo json_encode($arr);

	}
}

// ------------------------------KRAJ PRETRAGA-----------------------------------------------


// NOVO -----------------------------------------------------------------------------
if ($akcija == 'novo') {


	$sqlMax = "SELECT MAX(kad_cdikalkulacijadetail) maksimum from kalkulacijedetail";
	$podaciMax = $conn->query($sqlMax);

	//Podaci
	$maksimumStr = 	$podaciMax->fetch_assoc()['maksimum'];
	$maksimum =		(int)$maksimumStr + 1;
	$magacin = 		$_COOKIE['id-magacin'];
	$kam =			$_COOKIE['id-main'];
	$artikal = 		$_POST['artikal'];
	$kolicina =		$_POST['kolicina'];
	$nabavna =		$_POST['nabavna'];
	$rabat = 		$_POST['rabat'];
	$marza = 		$_POST['marza'];
	$vpcena = 		$_POST['modal-vpcena'];
	$mpcena = 		$_POST['modal-mpcena']; 


	$sqlKolicina = 'SELECT * FROM artikli INNER JOIN artiklixmagacini ON artikli.art_cdiartikal = artiklixmagacini.axm_cdiartikal
	WHERE artiklixmagacini.axm_cdiartikal = '.$artikal.' AND artiklixmagacini.axm_cdimagacin = '.$magacin;

	$podaciKolicina = $conn->query($sqlKolicina);

	while ($row = $podaciKolicina->fetch_assoc()) {
	    $kolicinaPre = 	$row['axm_vlnkolicina'];
	    $idKolicine = 	$row['axm_cdiartikalxmagacin'];
	}

	$novaKolicina = (int)$kolicina + (int)$kolicinaPre;

	$updateKolicina = "UPDATE artiklixmagacini SET axm_vlnkolicina = ".$novaKolicina." WHERE axm_cdiartikalxmagacin = ".$idKolicine.";";
	$conn->query($updateKolicina);

	if ($conn->affected_rows) {
		$sqlInsert = "
		INSERT INTO kalkulacijedetail 
		(kad_cdikalkulacijadetail, kad_cdikalkulacijamain,kad_cdiartikal,kad_vlnkolicina,
		kad_vlncenanab, kad_vlnrabat, kad_vlnmarza, kad_vlncenavp, kad_vlncenamp) VALUES 
		(".$maksimum.",".$kam.",".$artikal.",".$kolicina.",".$nabavna.",".$rabat.",".$marza.",".$vpcena.",".$mpcena.")";

		$conn->query($sqlInsert);

		if ($conn->affected_rows) {

			echo 'ok';
			
		}
	}
	else{

		echo "jok";
	}

}



if ($akcija == 'izmena') {

	$sifra = 			$_POST['sifra'];
    $grupa = 			$_POST['grupe'];
    $artikalId = 			$_POST['artikal'];
    $kolicina = 		$_POST['kolicina'];
    $lager =			$_POST['lager'];
    $nabavna = 			$_POST['nabavna'];
    $rabat = 			$_POST['rabat'];
    $cenaPopust = 		$_POST['cena-popust'];
    $nabavnaVrednost = 	$_POST['nabavna-vrednost'];
    $marza = 			$_POST['marza'];
    $vpCena = 			$_POST['modal-vpcena'];
    $mpCena = 			$_POST['modal-mpcena'];
    $porez = 			$_POST['porez'];
    $idStavke = 		$_POST['id-stavka'];

    $sqlUpdate = "
    UPDATE kalkulacijedetail
   	SET kad_cdiartikal = ".$artikalId.",
   	kad_vlnkolicina = ".$kolicina.",
   	kad_vlncenanab = ".$nabavna.",
   	kad_vlnrabat = ".$rabat.",
   	kad_vlnmarza = ".$marza.",
   	kad_vlncenavp = ".$vpCena.",
   	kad_vlncenamp = ".$mpCena."
   	WHERE kad_cdikalkulacijadetail = ".$idStavke."
    ";

   	$conn->query($sqlUpdate);

   	if($conn->affected_rows){
   		echo 'ok';
   	}
   	else {
   		echo 'jok';
   	}

}


if($akcija == 'brisanje'){

	$idStavke = $_POST['id'];

	$sqlDelete = "DELETE FROM kalkulacijedetail WHERE kad_cdikalkulacijadetail = ". $idStavke;

	$conn->query($sqlDelete);

	if ($conn->affected_rows) {

		echo 'ok';
	}

	else{

		echo 'jok';
	}
}

?>