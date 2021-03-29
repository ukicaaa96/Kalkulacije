<?php

include_once '../connection.php';

$artikal = $_POST['idArtikla'];
$magacin = $_POST['idMagacina'];

$sql = 
"SELECT * 
FROM artikli 
INNER JOIN artiklixmagacini 
ON artikli.art_cdiartikal = artiklixmagacini.axm_cdiartikal
where axm_cdimagacin = ".$magacin." and art_cdiartikal = ".$artikal;

$podaci = $conn->query($sql);

while ($row = $podaci->fetch_assoc()) {

	$naziv = $row['art_dssnaziv'];
	$sifra = $row['art_dsssifra'];
	$lager = $row['axm_vlnkolicina'];

}

$sqlPorez = "SELECT * 
FROM poreskestope 
inner join artikli
on art_cdiporeskastopa = pos_cdiporeskastopa
WHERE art_cdiartikal = " . $artikal;


$podaciPorez = $conn->query($sqlPorez);
while ($row = $podaciPorez->fetch_assoc()) {

	$porez = $row['pos_vlniznos'];
	$_COOKIE['porez'] = $porez;
    
}

$arr=[
	'poruka'=>'ok',
	'naziv' => $naziv,
	'sifra' => $sifra,
	'lager' => $lager,
	'porez' => $porez
];

echo json_encode($arr);



?>
