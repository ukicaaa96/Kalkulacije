<?php
include_once '../connection.php';

$idGrupe = $_POST['id'];

$sqlArtikli = "SELECT * FROM artikli WHERE art_cdigrupaartikla = ".$idGrupe;

$podaciArtikli = $conn->query($sqlArtikli);

$htmlArtikli = "";
$htmlArtikli .= '
            <div class="d-flex upis-artikla">
            <div class="col-sm-11 pr-2">
            <select class="selektovan-artikal" id="modal-artikli" name="artikal"> 
            <option selected value="-1">Izaberite artikal</option>';

while ($row = $podaciArtikli->fetch_assoc()) {

	$htmlArtikli .= '<option value="'.$row['art_cdiartikal'].'">'.$row['art_dssnaziv'].'</option>';
}

$htmlArtikli .= ' </select>';


$arr = [
	'poruka'=> 		'ok',
	'artikli' =>	$htmlArtikli	
];

echo json_encode($arr);



?>