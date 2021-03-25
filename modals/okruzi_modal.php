
<?php
include_once "../connection.php";

$akcija = $_POST['akcija'];

if($akcija == 'izmena'){
  $idOkruga = $_POST['idOkruga'];
  $idDrzave = $_POST['idDrzave'];


  $sqlOkrug = 'SELECT * FROM okruzi WHERE okr_cdiokrug = '. $idOkruga;
  $podaci = $conn->query($sqlOkrug);
  $okrug = $podaci->fetch_assoc();

}

if($akcija == 'novo'){
  $idDrzave = -1;
  $proveriMax = "SELECT MAX(okr_cdiokrug) maksimum from okruzi";
  $maxId = $conn->query($proveriMax);
  $idOkruga = (int)$maxId->fetch_assoc()['maksimum'];
  $idOkruga += 1;
}

$sqlDrzave = 'SELECT * FROM drzave';
$drzave = $conn->query($sqlDrzave);

?>

  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">


        <?php 
          if($akcija == 'novo'){
            $naslov = 'Dodavanje novog okruga';
          }
          if($akcija == 'izmena'){
            $naslov = "Izmeni okrug";
          }
          ?>


        <h5 class="modal-title" id="exampleModalLongTitle"><?=$naslov?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
  <!-- ---------------------------------------------------------------------------------------- -->
      <form id='modalForm'>
          <label for="usr">Naziv okruga:</label>
          <?php
                if($akcija == 'novo'){
                $naziv = '';
              }
              if($akcija == 'izmena'){
                $naziv = $okrug['okr_dssnaziv'];
              }
          ?>
          <input id ='modal-okrug' value = "<?=$naziv?>" name = "okrug" type="text" class="form-control">

  <!-- ---------------------------------------------------------------------------------------- -->

         <label for="usr">Oznaka:</label>
          <?php
                if($akcija == 'novo'){
                $oznaka = '';
              }
              if($akcija == 'izmena'){
                $oznaka = $okrug['okr_dssoznaka'];
              }
          ?>
          <input id ='modal-okrug' value = "<?=$oznaka?>" name = "oznaka" type="text" class="form-control">

  <!-- ---------------------------------------------------------------------------------------- -->

         <label for="usr">Drzava:</label>
            <div class='d-block'>
                <select id='drzavaSelect' name='drzava' class="mb-3">
                    <option value='-1' selected>izaberi drzavu:</option>
                    <?php
                        
                        while ($row = $drzave->fetch_assoc()) {

                            if($idDrzave==$row['drz_cdidrzava']){

                              ?>
                              <option value=<?=$row['drz_cdidrzava']?> selected><?=$row['drz_dssnaziv']?></option>
                              <?php
                            }

                        ?>
                    <option value=<?=$row['drz_cdidrzava']?>><?=$row['drz_dssnaziv']?></option>
                        <?php
                        }
                        ?>
                </select>
            </div>



        <input type="hidden" name="akcija" value="<?=$akcija?>" >
        <input type="hidden" name="id-okrug" value="<?=$idOkruga?>" >
        </form>

<!-- ---------------------------------------------------------------------------------------- -->
      </div>
      <div class="modal-footer">
            <div class= 'custom'>
            <button type="button" class="btn-footer btn btn-secondary" data-dismiss="modal">Izađi</button>
            
            <?php
            if($akcija =='izmena'){
            ?>
              <button id="sacuvaj" type="button" class="btn-footer btn btn-primary float-right">Sačuvaj</button>
            <?php
            }
            ?>

            <?php
            if ($akcija == 'novo'){
            ?>
              <button id="sacuvaj" type="button" class="btn-footer btn btn-primary float-right">Sačuvaj</button>
            <?php
            }
            ?>

            <div>
      </div>
    </div>
  </div>

