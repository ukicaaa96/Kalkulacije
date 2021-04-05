<?php
include_once "../connection.php";
$akcija = $_POST['akcija'];

if ($akcija == 'izmena') {

	$idLokacije = 			$_POST['idLokacija'];
	$idMagacina =			$_POST['idMagacina'];
	$nazivMagacina = 		$_POST['nazivMagacina'];
}

else{

	$idLokacije = '-1';
	$idMagacina = '';
	$nazivMagacina = '';
}


$sqlLokacije = "SELECT * FROM lokacije WHERE lok_cdilokacija > 0";
$podaciLokacija = $conn->query($sqlLokacije);
?>





  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Magacini</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
  <!-- ---------------------------------------------------------------------------------------- -->
      <form id='modalForm'>
          <label for="usr">Naziv magacina:</label>

          <?php if ($akcija == 'izmena') { ?>

          	 <input id ='magacin-modal' value = "<?= $nazivMagacina ?>" name = "magacin" type="text" class="form-control">

          <?php } else { ?>

		  	<input id ='magacin-modal' value = "" name = "magacin" type="text" class="form-control">

          <?php } ?>


  <!-- ---------------------------------------------------------------------------------------- -->

         <label for="usr">Lokacija:</label>
            <div class='d-block'>
                <select id='lokacije-modal' name='lokacija' class="mb-3 form-control">
                    <option value='-1' selected>izaberi lokaciju:</option>
                    <?php while($row = $podaciLokacija->fetch_assoc()){ ?>
                    	<?php if ($idLokacije == $row['lok_cdilokacija']) { ?>
                    		<option value='<?= $row['lok_cdilokacija'] ?>' selected><?=$row['lok_dssnaziv']?></option>
                    	<?php } else {?>
                    		<option value='<?= $row['lok_cdilokacija'] ?>'><?=$row['lok_dssnaziv']?></option>
                    	<?php } ?>
                    <?php } ?>
                </select>
            </div>

        <input type="hidden" name="akcija" value="<?=$akcija?>" >

        <?php if ($akcija == 'izmena') { ?>
        	 <input type="hidden" name="id-magacin" value="<?=$idMagacina?>" >
        <?php } ?>

        </form>

<!-- ---------------------------------------------------------------------------------------- -->
      </div>
      <div class="modal-footer">
            <div class= 'custom'>
            	<button type="button" class="btn-footer btn btn-secondary" data-dismiss="modal">Izađi</button>
            	<button id="sacuvaj" type="button" class="btn-footer btn btn-primary float-right">Sačuvaj</button>
            <div>
      </div>
    </div>
  </div>




