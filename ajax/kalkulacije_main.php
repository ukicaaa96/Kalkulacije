<?php
include_once '../connection.php';
$akcija = $_POST['akcija'];

function datumUpdateFilter($datum, $bool){

		if ($bool == true) {
			$date = date("Y-m-d", strtotime($datum));
			$novDatum = $date." ".date("H:i:s");
			return $novDatum;
		}
		if($bool == false){
			$novDatum = date("Y-m-d H:i:s", strtotime($datum));
			return $novDatum;
		}
	}

function datumFilter($datum)
	{
		$novDatum = date("d.m.Y", strtotime($datum));
		return $novDatum;
	}


if($akcija == 'pretraga'){

	$querySearch = "
	SELECT * 
	FROM kalkulacijemain
	INNER JOIN 	kalkulacijedetail
	ON kam_cdikalkulacijamain = kad_cdikalkulacijadetail
	WHERE kam_datdatum BETWEEN '2019-10-17' AND '2021-03-26'
	AND kam_datvaluta BETWEEN '2019-10-17' AND '2021-03-26'
	AND kad_cdiartikal = 6
	AND kam_cdimagacin = 1
	AND kam_cdidobavljac = 1
	and kam_dssnapomena = 'napomena'";

	
	$parametriPretrage = '';


	if (isset($_POST['magacin-pretraga']) && $_POST['magacin-pretraga'] > 0) {
		$pretragaMagacin = " AND kam_cdimagacin LIKE'%". $_POST['magacin-pretraga']."%'";
	}
	else{
		$pretragaMagacin = "";
	}

	if (isset($_POST['dobavljac-pretraga']) && $_POST['dobavljac-pretraga'] > 0) {
		$pretragaDobavljac = " AND kam_cdidobavljac LIKE'%". $_POST['dobavljac-pretraga']."%'";
	}
	else{
		$pretragaDobavljac = "";
	}

	if(isset($_POST['artikal-pretraga']) && $_POST['artikal-pretraga'] > 0){
		$pretragaArtikal = " AND kad_cdiartikal = ". $_POST['artikal-pretraga'];
	}
	else{
		$pretragaArtikal = "";
	}

	if(isset($_POST['napomena']) && $_POST['napomena'] != ""){
		$pretragaNapomena = " AND kam_dssnapomena LIKE'%". $_POST['napomena']."%'";
	}
	else{
		$pretragaNapomena = "";
	}

		

	$parametriPretrage = $pretragaMagacin.$pretragaDobavljac.$pretragaNapomena;

	//echo $parametriPretrage;

	$sNabavne = 	0;
	$sProdajne = 	0;



	if($_POST['komanda'] == '0'){

		//$C_datumOd=	$_COOKIE['C_datum-od'];
		$C_datumOd= 	'26.03.2021';
		$C_datumDo=		$_COOKIE['C_datum-do'];

		$datumOd = date("Y-m-d", strtotime($C_datumOd));
		$datumDo = date("Y-m-d", strtotime($C_datumDo));

	}else{
		$datum1 = 	$_POST['datum-od'];
		$datum2 = 	$_POST['datum-do'];
		$datumOd = 	date("Y-m-d", strtotime($datum1));
		$datumDo = 	date("Y-m-d", strtotime($datum2));
	}

	$sql =
	"SELECT *
	FROM kalkulacijemain
	INNER JOIN kalkulacijedetail ON kalkulacijemain.kam_cdikalkulacijamain = kalkulacijedetail.kad_cdikalkulacijamain
	WHERE kam_datdatum BETWEEN '".$datumOd."' AND '".$datumDo."'". $parametriPretrage . $pretragaArtikal;

	$provera = $conn->query($sql);

// Ako ne postoji detail kalkulacije ne joinujemo sa detail----------------------------------------------------------------------
	$html = "";
	if($provera->num_rows != 0){

		$sqlKalkulacije = 
		"SELECT *
		FROM kalkulacijemain
		WHERE kam_datdatum BETWEEN '".$datumOd."' AND '".$datumDo."'" . $parametriPretrage;


		$podaci = $conn->query($sqlKalkulacije);
		$i = 1;

		if($podaci->num_rows != 0 ){

		while ($row = $podaci->fetch_assoc()) {
//----------------------------------VRACANJE NABAVNE-----------------------------------------------------
			$sql =
			"SELECT *
			FROM kalkulacijedetail
			left JOIN kalkulacijemain ON kalkulacijemain.kam_cdikalkulacijamain = kalkulacijedetail.kad_cdikalkulacijamain
			WHERE kalkulacijedetail.kad_cdikalkulacijamain = ".$row['kam_cdikalkulacijamain'];

			//echo $sql;
			$nabavneCene = $conn->query($sql);
			if($nabavneCene->num_rows > 0){
				$sumaNabavne = 0;
				while ($red = $nabavneCene->fetch_assoc()) {
				    $nab = (int)$red['kad_vlncenanab'];
				    $rabat = (int)$red['kad_vlnrabat'] / 100;

				    $cena = $nab - ($nab * $rabat);


				    $sumaNabavne += $cena * (int)$red['kad_vlnkolicina'];

				    $sNabavne += $cena * (int)$red['kad_vlnkolicina'];
				}
			}

			else{
				$sumaNabavne = 0;
			}


//---------------------------------------VRACANJE SA PDV-OM--------------------------------
			$sql = 
			"SELECT pos_vlniznos, kad_vlncenavp, kad_vlnkolicina
			FROM kalkulacijedetail
			inner join artikli
			on artikli.art_cdiartikal = kalkulacijedetail.kad_cdiartikal
			inner join poreskestope
			on artikli.art_cdiporeskastopa = poreskestope.pos_cdiporeskastopa
			where kad_cdikalkulacijamain = ".$row['kam_cdikalkulacijamain'];



			$cenePdv = $conn->query($sql);
			$_COOKIE['ccc'] = $cenePdv;
			if($cenePdv->num_rows > 0){

				$sumaUkupno = 0;

				while ($red = $cenePdv->fetch_assoc()) {

					$cena = (int)$red['kad_vlncenavp'];
					$porez = (int)$red['pos_vlniznos']/100;

					$cenaSaPdv = $cena * $porez + $cena;
					$sumaUkupno += $cenaSaPdv * (int)$red['kad_vlnkolicina'];

					$sProdajne += $cenaSaPdv * (int)$red['kad_vlnkolicina'];
				}

			}
			else{
				$sumaUkupno = 0;
			}



//--------------------------------------------------------------------------------------



		    $vratiLokaciju = "SELECT mag_dssnaziv FROM magacini WHERE mag_cdimagacin=". $row['kam_cdimagacin'];
		    $nazivMagacinArr = $conn->query($vratiLokaciju);
		    $nazivMagacin = $nazivMagacinArr->fetch_assoc()['mag_dssnaziv'];

		    $dat1 = datumFilter($row['kam_datdatum']);
		    $dat2 = datumFilter($row['kam_datvaluta']);

			$html .= "
			              		<tr class='main' id='".$row['kam_cdikalkulacijamain']."'>

                                    <td class='text-center'>".$i."</td>
                                    <td class='text-right'>".$row['kam_nuibroj']."</td>
                                    <td class ='datum-kalkulacije' value='".$dat1."'>".$dat1."</td>
                                    <td class ='datum-valute' value='".$dat2."'>".$dat2."</td>
                                    <td>".$row['kam_dssfaktura']."</td>
                                    <td>".$nazivMagacin."</td>
                                    <td class='nabavna-vrednost text-right'>".number_format((float)$sumaNabavne,2)."</td>
                                    <td class='prodajna-vrednost text-right'>".number_format((float)$sumaUkupno,2)."</td>

                                  
                                
                                    <td>

                                        <div class ='d-flex justify-content-center'>
								            <div class='custom col-sm-3 p-1'>
								                <div class='izmenaKalkulacije' data-id-kalkulacija ='".$row['kam_cdikalkulacijamain']."' data-id-magacin='".$row['kam_cdimagacin']."' data-toggle='tooltip' data-placement='top' title='izmeni kalkulaciju'>
								                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
								                        <path
								                            d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'
								                        ></path>
								                        <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'></path>
								                    </svg>
								                </div>
								            </div>

								            <div class='custom col-sm-3 p-1'>
								                <div class='brisanjeKalkulacije' data-id-kalkulacija='".$row['kam_cdikalkulacijamain']."' data-toggle='tooltip' data-placement='top' title='izbriši kalkulaciju'>
								                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-x-square-fill' viewBox='0 0 16 16'>
								                        <path
								                            d='M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z'/>
								                    </svg>
								                </div>
								            </div>

								        	<div class='custom col-sm-3 p-1'>
								                <div class='stampanje' data-id-kalkulacija='".$row['kam_cdikalkulacijamain']."' data-toggle='tooltip' data-placement='top' title='istampaj kalkulaciju'>
													<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-bootstrap-fill' viewBox='0 0 16 16'>
													  <path d='M6.375 7.125V4.658h1.78c.973 0 1.542.457 1.542 1.237 0 .802-.604 1.23-1.764 1.23H6.375zm0 3.762h1.898c1.184 0 1.81-.48 1.81-1.377 0-.885-.65-1.348-1.886-1.348H6.375v2.725z'/>
													  <path d='M4.002 0a4 4 0 0 0-4 4v8a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4V4a4 4 0 0 0-4-4h-8zm1.06 12V3.545h3.399c1.587 0 2.543.809 2.543 2.11 0 .884-.65 1.675-1.483 1.816v.1c1.143.117 1.904.931 1.904 2.033 0 1.488-1.084 2.396-2.888 2.396H5.062z'/>
													</svg>
										
								                </div>
								            </div>



								          </div>
								            </td>
								            </tr>
			";
			$i ++;
		}
	
	}

	$arr = [
	'html' => 			$html,
	'sumaNabavne' => 	number_format((float)$sNabavne,2),
	'sumaProdajne'=> 	number_format((float)$sProdajne,2)
	];

	echo json_encode($arr);
}
}


//--KRAJ POCETNE PRETRAGE---------------------------------------------------------------------------------------------------


//--izmena------------------------------------------------------------------------------------------------------------------

if ($akcija=='izmena') {

	function datumUpdateFilter($datum, $bool){

		if ($bool == true) {
			$date = date("Y-m-d", strtotime($datum));
			$novDatum = $date." ".date("H:i:s");
			return $novDatum;
		}
		if($bool == false){
			$novDatum = date("Y-m-d H:i:s", strtotime($datum));
			return $novDatum;
		}
	}

    $brojKalkulacije = 		$_POST['broj-kalkulacije'];
   	$datumKalkulacije = 	datumUpdateFilter($_POST['datum-kalkulacije'], true);
    $datumValute = 			datumUpdateFilter($_POST['datum-valute'], false); 
    $magacin = 				$_POST['magacin'];
    $dobavljac = 			$_POST['dobavljac'];
    $napomena = 			$_POST['napomena'];
    $faktura = 				$_POST['faktura'];
    $akcija = 				$_POST['akcija'];
    $id =					$_POST['id'];


    $updateSql = 
    "UPDATE kalkulacijemain
	SET 
	kam_datdatum ='".$datumKalkulacije."',
	kam_nuibroj ='".$brojKalkulacije."',
	kam_dssfaktura='".$faktura."',
	kam_cdimagacin='".$magacin."',
	kam_cdidobavljac='".$dobavljac."',
	kam_datvaluta='".$datumValute."'
	WHERE kam_cdikalkulacijamain=".$id;
					

	$conn->query($updateSql);

	if($conn->affected_rows){
		$arr = [
			'poruka' => 'ok',
			'akcija' => 'izmena'
		];

		echo json_encode($arr);

	}

	else{
		$arr = ['poruka'=>'jok'];
		echo json_encode($arr);
	}

}

if ($akcija=='brisanje') {

	$id = $_POST['id'];

	$sqlDelete = "DELETE FROM kalkulacijemain WHERE kam_cdikalkulacijamain = ". $id;
	$conn->query($sqlDelete);

	if ($conn->affected_rows) {

		$sqlDeleteDetail = "DELETE FROM kalkulacijedetail WHERE kad_cdikalkulacijamain = ". $id;
		$conn->query($sqlDeleteDetail);

		if($conn->affected_rows){

			echo 'ok';
		}
		else{

			echo 'jok';
		}
	}
	else{

		echo 'jok';
	}
}

if ($akcija=='novo') {

	$maxSql = "SELECT MAX(kam_cdikalkulacijamain) maksimum FROM kalkulacijemain";
	$maxId = $conn->query($maxSql);
	$maksimum = $maxId->fetch_assoc()['maksimum'];

	$id = (int)$maksimum+1;
    $brojKalkulacije = 		$_POST['broj-kalkulacije'];
   	$datumKalkulacije = 	datumUpdateFilter($_POST['datum-kalkulacije'], true);
    $datumValute = 			datumUpdateFilter($_POST['datum-valute'], false); 
    $magacin = 				$_POST['magacin'];
    $dobavljac = 			$_POST['dobavljac'];
    $napomena = 			$_POST['napomena'];
    $faktura = 				$_POST['faktura'];
    $akcija = 				$_POST['akcija'];

    $sqlNovo = 
    "INSERT INTO kalkulacijemain 
    (kam_cdikalkulacijamain , kam_datdatum, kam_nuibroj, kam_dssfaktura, 
    kam_dssnapomena, kam_cdimagacin, kam_cdidobavljac, kam_datvaluta)
    VALUES ('".$id."','".$datumKalkulacije."','".$brojKalkulacije."','".$faktura."','".$napomena."','".$magacin."','".$dobavljac."','".$datumValute."')
    ";

    $conn->query($sqlNovo);

    if ($conn->affected_rows) {

    	$arr = [
    		'poruka' => 'ok',
    		'akcija' => 'novo'
    	];

    	echo json_encode($arr);
    	
    }

    else {
    	$arr = [
    		'poruka' => 'jok',
    		'akcija' => 'novo'
    	];

    	echo json_encode($arr);
    }
}
?>