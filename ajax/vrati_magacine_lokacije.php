<?php
include_once '../connection.php';

$sqlLokacije = "SELECT lok_dssnaziv, lok_cdilokacija FROM lokacije";
$podaciLokacije = $conn->query($sqlLokacije);

?>

<select>

<!-- Lokacije -->
<?php
while ($row = $podaciLokacije->fetch_assoc()) {

    
	$idLokacije = 		'l'.$row['lok_cdilokacija'];
	$nazivLokacije = 	$row['lok_dssnaziv'];


?>
<option value="<?=$idLokacije?>"><?=$nazivLokacije?></option>
<?php
	}
?>
<!-- KRAJ LOKACIJE -->

<?php

	$sqlMagacini = "SELECT mag_dssnaziv, mag_cdimagacin FROM magacini";
	$podaciMagacini = $conn->query($sqlMagacini);

	while ($row = $podaciMagacini->fetch_assoc()) {
		$idMagacina = 'm'.$row['mag_cdimagacin'];
		$nazivMagacina = $row['mag_dssnaziv'];
?>

	<option value="<?=$idMagacina?>"><?=$nazivMagacina?></option>
<?php

}

?>



</select>