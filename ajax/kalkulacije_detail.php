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

		$nabavnaVrednost = 	$cenaSaPopustom;
		$vpIznos = 			$cenaVp;
		$iznosPdv = 		$cenaPdv;


		

		$html .= 
		'
            <tr>

                <td class="text-center">'.$i.'</td>
                <td class="">'.$sifraArtikla.'</td>
 				<td class="text-left">'.$nazivArtikla.'</td>
				<td class="text-right">'.number_format((int)$kolicina, 2, '.', ',').'		</td>
				<td class="text-right">'.number_format((int)$nabavnaCena, 2, '.', ',').'		</td>
                <td class="text-right">'.number_format((int)$rabat, 2, '.', ',').'%			</td>
                <td class="text-right">'.number_format((int)$cenaSaPopustom, 2, '.', ',').'</td>
                <td class="text-right">'.number_format((int)$marza, 2, '.', ',').'%</td>
                <td class="text-right">'.number_format((int)$cenaVp, 2, '.', ',').'</td>
                <td class="text-right">'.number_format((int)$cenaPdv, 2, '.', ',').'</td>
              	<td class="text-right">'.number_format((int)$nabavnaVrednost, 2, '.', ',').'</td>
                <td class="text-right">'.number_format((int)$vpIznos, 2, '.', ',').'</td>
                <td class="text-right">'.number_format((int)$iznosPdv, 2, '.', ',').'</td>
                <td class ="d-flex">
                    <div class="custom col-sm-6 p-1">
                    	<div class="izmenaDetalja float-right" data-id="'.$idDetalja.'"  data-toggle="tooltip" data-placement="top" title="izmeni stavku">
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

?>