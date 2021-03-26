<?php
include_once "../connection.php";
$akcija = $_POST['akcija'];
$datumDanas = date('d.m.Y');

?>

<?php
if($akcija == 'novo'){

$vratiMagacine = "SELECT * FROM magacini";
$magacini = $conn->query($vratiMagacine);

$vratiDobavljace = "SELECT * FROM dobavljaci where dob_cdidobavljac > 0";
$dobavljaci = $conn->query($vratiDobavljace);

?>
<!-- MODAL ------------------------------------------------------------------------------------------------------------------------- -->
<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
  <div class="modal-content">
<!-- MODAL HEADER----------------------------------------------------------------------------------------------------------------- -->
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Dodavanje nove kalkulacije</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
<!--POCETAK FORME ---------------------------------------------------------------------------------------------------------- -->
    <form id='modalForm'>
      <div class='d-block'>
<!-- RED 1 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex">  
          <div class='col-sm-4'> 
            <label for="usr">Broj</label>
            <input type="text" name="komanda" value="1">
            <input id ='modal-okrug' value = "1" name = "broj-kalkulacije" type="number"  class="form-control">
          </div>

          <div class='col-sm-4'> 
            <label for="usr">Datum</label>
            <input id ='modal-datum' value = "<?= $datumDanas ?>" name = "datum-kalkulacije" type="text" class="form-control modal-datum">
          </div>

          <div class='col-sm-4'> 
            <label for="usr">Datum Valuta</label>
            <input id ='modal-datum-valuta' value = "<?=$datumDanas?>" name = "datum-valute" type="text" class="form-control modal-datum">
          </div>
        </div>
<!-- RED 2 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex">  
          <div class='col-sm-4'> 
            <label for="usr">Magacin/Lokacija</label>   
             <select id='modal-lokacija' name='magacin'>
                    <?php
                        while ($row = $magacini->fetch_assoc()) {
                    ?>
                          <option value=<?=$row['mag_cdimagacin']?>><?=$row['mag_dssnaziv']?></option>
                    <?php
                        }
                    ?>
                </select>

          </div>
    
          <div class='col-sm-4'> 
            <label for="usr">Faktura</label>
            <input id ='modal-faktura' value = "" name = "faktura" type="text" class="form-control">
          </div>

          <div class='col-sm-4'> 
          <div class="form-group">
            <label for="usr">Dobavljac</label>
                <select id='modal-dobavljac' name='dobavljac'>
                    <?php
                        while ($row = $dobavljaci->fetch_assoc()) {
                    ?>
                            <option value=<?=$row['dob_cdidobavljac']?>><?=$row['dob_dssnaziv']?></option>
                    <?php
                        }
                    ?>
                </select>
          </div>
          </div>
        </div>

<!--RED 3 - NAPOMENA-------------------------------------------------------------------------------------------------------- -->
        <div class='flex'>
          <div class="col-sm-12">
            <div class="form-group">
                <label for="message-text" class="col-form-label">Napomena</label>
                <textarea class="form-control" id="message-text" name="napomena"></textarea>
            </div>
          </div>
        </div>
      </div>
<!-- KRAJ FORME---------------------------------------------------------------------------------------------------------- -->
        <input type="hidden" name="akcija" value="novo" >
      </form>
<!-- MODAL FOOTER---------------------------------------------------------------------------------------- -->
      </div>
        <div class="modal-footer">
            <div class= 'custom' style="width: 100%">
            <button type="button" class="btn-footer btn btn-secondary" data-dismiss="modal">Izađi</button>
            <button id="sacuvaj" type="button" class="btn-footer btn btn-primary float-right">Sačuvaj</button>
          <div>
      </div>
    </div>
  </div>

<?php
}
?>
<!-------NOVO KRAJ------------------------------------------------------------------------------------------------ -->








<!--IZMENA ------------------------------------------------------------------------------------------------------------------------------ -->

<?php
if($akcija == 'izmena'){

  $idKalkulacije = $_POST['idKalkulacije'];
  $idMagacina = $_POST['idMagacina'];
  $datumKalkulacije = $_POST['datumKalkulacije'];
  $datumValute = $_POST['datumValute'];

//--Podaci kalkulacije-------------------------------------------------------------------------------------------------------------------
  $podaciSql = "SELECT kam_cdidobavljac, kam_nuibroj, kam_dssfaktura,kam_dssnapomena 
  FROM kalkulacijemain WHERE kam_cdikalkulacijamain = ". $idKalkulacije;

  $podaci = $conn->query($podaciSql)->fetch_assoc();

  $idDobavljaca = $podaci['kam_cdidobavljac'];
  $brojKalkulacije = $podaci['kam_nuibroj'];
  $faktura = $podaci['kam_dssfaktura'];
  $napomena = $podaci['kam_dssnapomena'];
//---Svi dobavljaci------------------------------------------------------------------------------------------------------------------------------------
  $vratiSveDobavljace = "SELECT * FROM dobavljaci WHERE dob_cdidobavljac > 0 ";
  $dobavljaci = $conn->query($vratiSveDobavljace);

//---Svi magacini------------------------------------------------------------------------------------------------------------------------------------
  $vratiMagacine = "SELECT * FROM magacini";
  $magacini = $conn->query($vratiMagacine);

//---------------------------------------------------------------------------------------------------------------------------------------

?>

<!-- MODAL ------------------------------------------------------------------------------------------------------------------------- -->
<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
  <div class="modal-content">
<!-- MODAL HEADER----------------------------------------------------------------------------------------------------------------- -->
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Izmena kalkulacije</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
<!--POCETAK FORME ---------------------------------------------------------------------------------------------------------- -->
    <form id='modalForm'>
      <div class='d-block'>
<!-- RED 1 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex">  
          <div class='col-sm-4'> 
            <input type="text" value = "<?= $idKalkulacije ?>"name="id" hidden>
            <label for="usr">Broj</label>
            <input id ='modal-okrug' value = "<?= $brojKalkulacije ?>" name = "broj-kalkulacije" type="number"  class="form-control">
          </div>

          <div class='col-sm-4'> 
            <label for="usr">Datum</label>
            <input id ='modal-datum' value = "<?= $datumKalkulacije ?>" name = "datum-kalkulacije" type="text" class="form-control modal-datum">
          </div>

          <div class='col-sm-4'> 
            <label for="usr">Datum Valuta</label>
            <input id ='modal-datum-valuta' value = "<?=$datumValute?>" name = "datum-valute" type="text" class="form-control modal-datum">
          </div>
        </div>
<!-- RED 2 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex">  
          <div class='col-sm-4'> 
            <label for="usr">Magacin/Lokacija</label>   
                <select id='modal-lokacija' name='magacin'>
                    <?php
                        while ($row = $magacini->fetch_assoc()) {
                            if($row['mag_cdimagacin']==$idMagacina){
                    ?>
                              <option value=<?=$row['mag_cdimagacin']?> selected><?=$row['mag_dssnaziv']?></option>
                    <?php
                            }
                    ?>
                            <option value=<?=$row['mag_cdimagacin']?>><?=$row['mag_dssnaziv']?></option>
                    <?php
                        }
                    ?>
                </select>

          </div>
    
          <div class='col-sm-4'> 
            <label for="usr">Faktura</label>
            <input id ='modal-faktura' value = "<?= $faktura ?>" name = "faktura" type="text" class="form-control">
          </div>

          <div class='col-sm-4'> 
          <div class="form-group">
            <label for="usr">Dobavljac</label>
               <select id='modal-dobavljac' name='dobavljac'>
                    <?php
                        while ($row = $dobavljaci->fetch_assoc()) {
                            if($row['dob_cdidobavljac']==$idDobavljaca){
                    ?>
                              <option value=<?=$row['dob_cdidobavljac']?> selected><?=$row['dob_dssnaziv']?></option>

                    <?php
                            continue;
                            }
                    ?>
                            <option value=<?=$row['dob_cdidobavljac']?>><?=$row['dob_dssnaziv']?></option>
                    <?php
                        }
                    ?>
                </select>
          </div>
          </div>
        </div>

<!--RED 3 - NAPOMENA-------------------------------------------------------------------------------------------------------- -->
        <div class='flex'>
          <div class="col-sm-12">
            <div class="form-group">
                <label for="message-text" class="col-form-label">Napomena</label>
                <textarea class="form-control" id="message-text" name="napomena"><?=$napomena?></textarea>
            </div>
          </div>
        </div>
      </div>
<!-- KRAJ FORME---------------------------------------------------------------------------------------------------------- -->
        <input type="hidden" name="akcija" value="izmena" >
      </form>
<!-- MODAL FOOTER---------------------------------------------------------------------------------------- -->
      </div>
        <div class="modal-footer">
            <div class= 'custom' style="width: 100%">
            <button type="button" class="btn-footer btn btn-secondary" data-dismiss="modal">Izađi</button>
            <button id="sacuvaj" type="button" class="btn-footer btn btn-primary float-right">Sačuvaj</button>
          <div>
      </div>
    </div>
  </div>


<?php
}
?>
<!-- IZMENA KRAJ------------------------------------------------------------------------------------------------------------------------------ -->

