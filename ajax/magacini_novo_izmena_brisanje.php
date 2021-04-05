<?php
include_once '../connection.php';


if (isset($_POST['akcija'])) {
	$akcija = $_POST['akcija'];
}
else{
	$akcija = '';
}

// NOVO ----------------------------------------------------------------------------
if ($akcija == 'novo') {

	$nazivMagacina = 	$_POST['magacin'];
	$idLokacija = 		$_POST['lokacija'];
	// vrati max 
	$vratiMax = "SELECT MAX(mag_cdimagacin) maksimum from magacini";
	$podaciMax = $conn->query($vratiMax);
	while ($row = $podaciMax->fetch_assoc()) {

		$maxStr = 		$row['maksimum'];
		$magacinId = 	(int)$maxStr + 1;
	    
	}
	//-------

	$sqlInsert = "INSERT INTO magacini 
	(mag_cdimagacin, mag_dssnaziv, mag_cdilokacija,mag_nuitip ,mag_oplprimarnimagacin, mag_opltermalnistampa)
	VALUES 
	(".$magacinId." , '".$nazivMagacina."' , ".$idLokacija." , 0,0,0) ";

	$conn->query($sqlInsert);

	if ($conn->affected_rows) {

		$arr = ['poruka' => 'ok' , 'akcija' => 'unosenje magacina'];
		echo json_encode($arr);
	}

	else{

		$arr = ['poruka' => 'jok' , 'akcija' => 'nije proslo unosenje magacina'];
		echo json_encode($arr);
	}
	
}

//IZMENA --------------------------------------------------------------------------------------------------------------------------------------------

elseif ($akcija == "izmena") {

	//print_r($_POST);

	// magacin: Testdsad
	// lokacija: 1
	// akcija: izmena
	// id-magacin: 5

	$naziv = $_POST['magacin'];
	$lokacija = $_POST['lokacija'];
	$idMagacin = $_POST['id-magacin'];


	$sqlUpdate = "
	UPDATE magacini 
	SET 
	mag_dssnaziv = '".$naziv."',
	mag_cdilokacija = ".$lokacija."
	WHERE 
	mag_cdimagacin = ".$idMagacin."
	";

	$conn->query($sqlUpdate);

	if ($conn->affected_rows) {
		$arr = ['poruka' => 'ok' , 'akcija' => "izmena magacina"];
		echo json_encode($arr);
	}
	else {
		$arr = ['poruka' => 'jok' , 'akcija' => "neuspesna izmena magacina"];
		echo json_encode($arr);
	}
	
}

elseif ($akcija == 'brisanje') {

	$idMagacina = $_POST['idMagacina'];

	$sqlDelete = "DELETE FROM magacini WHERE mag_cdimagacin = ". $idMagacina;

	$conn->query($sqlDelete);

	if ($conn->affected_rows) {
		$arr = ['poruka' => 'ok' , 'akcija' => "brisanje magacina"];
		echo json_encode($arr);
	}
	else {
		$arr = ['poruka' => 'jok' , 'akcija' => "neuspesno brisanje magacina"];
		echo json_encode($arr);
	}

	
}

else{

	if($_COOKIE['naziv-magacin'] != '-1') {
		$magacinPretraga = " AND m.mag_dssnaziv LIKE '%".$_COOKIE['naziv-magacin']."%'";
	}else{
		$magacinPretraga = "";
	}

	if($_COOKIE['id-lokacija'] != '-1'){
		$lokacijaPretraga = ' AND mag_cdilokacija = '. $_COOKIE['id-lokacija'];
	}else{
		$lokacijaPretraga = '';
	}

	    $sql=  
	            "SELECT * 
	            FROM magacini as m
	            INNER JOIN lokacije as l 
	            ON m.mag_cdilokacija = l.lok_cdilokacija 
	            WHERE 
	            m.mag_cdimagacin > 0 ".$magacinPretraga . $lokacijaPretraga;


	    $akcijaClick = '';


	    $start =    (int)$_POST['start'];
	    $len =      (int)$_POST['length'];
	    $draw =     $_POST['draw']; 
	    $end =      $start+$len;

	    if($draw == ""){
	        $draw = 1;
	    } 

	    $podaci =   [];   
	    $brojac =   1;
	    $i =        1;
	    $data =     $conn->query($sql);

	    while ($row = $data->fetch_assoc()) {
	        if($start<$i && $end>=$i)
	        {

	            $idMagacin =      	$row["mag_cdimagacin"];
	            $redniBroj =    	"<span class='counter'>".$i."</span>";
	            $nazivMagacina =	"<span class='podatakMagacin'>".$row["mag_dssnaziv"]."</span>";
	            $nazivLokacije =    "<span class='podatakLokacija'>".$row['lok_dssnaziv']."</span>";                 
	            $akcijaClick .= 
	            '
	            <div class ="d-flex">
	            <div class="custom col-sm-6 p-1">
	                <div class="izmenaMagacina float-right" data-id-magacin ="'.$row['mag_cdimagacin'].'" data-id-lokacija="'.$row['lok_cdilokacija'].'" data-toggle="tooltip" data-placement="top" title="izmeni magacin">
	                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
	                        <path
	                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
	                        ></path>
	                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
	                    </svg>
	                </div>
	            </div>

	            <div class="custom col-sm-6 p-1">
	                <div class="brisanjeMagacina" data-id-magacin="'.$row['mag_cdimagacin'].'" data-toggle="tooltip" data-placement="top" title="izbriši magacin">
	                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
	                        <path
	                            d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"
	                        />
	                    </svg>
	                </div>
	            </div>
	            </div>

	            ';

	                $myArr = [
	                'counter'=>         $redniBroj,
	                'magacin' =>        $nazivMagacina,
	                'lokacija' =>       $nazivLokacije,
	                'akcijaClick' =>    $akcijaClick
	            ];  

	            $akcijaClick = '';


	            array_push($podaci, $myArr);          
	            $brojac += 1;
	        }
	        $i+= 1;
	    }



	    $mesta_niz["draw"]=             $draw;
	    $mesta_niz["recordsTotal"] =    $i-1;
	    $mesta_niz["recordsFiltered"] = $i-1;

	    echo json_encode([
	        'data'=>                $podaci,
	        'draw'=>                $draw,
	        'recordsTotal'=>        $i-1,
	        'recordsFiltered' =>    $i-1
	    ]);

}
?>
