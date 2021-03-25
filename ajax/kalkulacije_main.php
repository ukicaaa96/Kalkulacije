<?php
include_once '../connection.php';
$akcija = 'test';
// if ($akcija=='novo') {
	
// }

// if ($akcija=='izmena') {
	
// }

// if ($akcija=='brisanje') {
	
// }
function datumFilter($datum)
{
	$novDatum = date("d.m.Y", strtotime($datum));
	return $novDatum;
}



if($akcija == 'test'){

	//$C_datumOd=	$_COOKIE['C_datum-od'];
	$C_datumOd= '01.01.2019';
	$C_datumDo=	$_COOKIE['C_datum-do'];

	$datumOd = date("Y-m-d", strtotime($C_datumOd));
	$datumDo = date("Y-m-d", strtotime($C_datumDo));

	$sql =
	"SELECT *
	FROM kalkulacijemain
	INNER JOIN kalkulacijedetail ON kalkulacijemain.kam_cdikalkulacijamain = kalkulacijedetail.kad_cdikalkulacijamain
	WHERE kam_datdatum BETWEEN '".$datumOd."' AND '".$datumDo."'";

	$provera = $conn->query($sql);

// Ako ne postoji detail kalkulacije ne joinujemo sa detail----------------------------------------------------------------------
	$html = "";
	if($provera->num_rows != 0){

		$sqlKalkulacije = 
		"SELECT *
		FROM kalkulacijemain
		WHERE kam_datdatum BETWEEN '".$datumOd."' AND '".$datumDo."'";

		$podaci = $conn->query($sqlKalkulacije);
		$i = 1;


		while ($row = $podaci->fetch_assoc()) {
		    $vratiLokaciju = "SELECT mag_dssnaziv FROM magacini WHERE mag_cdimagacin=". $row['kam_cdimagacin'];
		    $nazivMagacinArr = $conn->query($vratiLokaciju);
		    $nazivMagacin = $nazivMagacinArr->fetch_assoc()['mag_dssnaziv'];

		    $dat1 = datumFilter($row['kam_datdatum']);
		    $dat2 = datumFilter($row['kam_datvaluta']);

			$html .= "
			              		<tr>
                                    <td class='text-center'>".$i."</td>
                                    <td class='text-right'>".$row['kam_nuibroj']."</td>
                                    <td>".$dat1."</td>
                                    <td>".$dat2."</td>
                                    <td>".$row['kam_dssfaktura']."</td>
                                    <td>".$nazivMagacin."</td>
                                    <td class='nabavna-vrednost text-right'>0.00</td>
                                    <td class='prodajna-vrednost text-right'>0.00</td>
                                  
                                
                                    <td>

                                        <div class ='d-flex justify-content-center'>
								            <div class='custom col-sm-3 p-1'>
								                <div class='izmenaOkruga' data-id-drzava ='1' data-id='1' data-toggle='tooltip' data-placement='top' title='izmeni mesto'>
								                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
								                        <path
								                            d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'
								                        ></path>
								                        <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'></path>
								                    </svg>
								                </div>
								            </div>

								            <div class='custom col-sm-3 p-1'>
								                <div class='brisanjeOkruga' data-id='1' data-toggle='tooltip' data-placement='top' title='izbriši mesto'>
								                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-x-square-fill' viewBox='0 0 16 16'>
								                        <path
								                            d='M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z'/>
								                    </svg>
								                </div>
								            </div>

								        	<div class='custom col-sm-3 p-1'>
								                <div class='brisanjeOkruga' data-id='1' data-toggle='tooltip' data-placement='top' title='izbriši mesto'>
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

}




echo $html;
?>