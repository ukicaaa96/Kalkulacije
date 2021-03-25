<?php

$akcija = $_POST['akcija'];
$datumDanas = date('d.m.Y');
?>

<!-- MODAL ------------------------------------------------------------------------------------------------------------------------- -->
<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
  <div class="modal-content">
<!-- MODAL HEADER----------------------------------------------------------------------------------------------------------------- -->
    <div class="modal-header">
      <?php 
        if($akcija == 'novo'){
          $naslov = 'Dodavanje kalkulacije';
        }
        if($akcija == 'izmena'){
          $naslov = "Izmena kalkulacije";
        }
        ?>
        <h5 class="modal-title" id="exampleModalLongTitle"><?=$naslov?></h5>
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
            <input id ='modal-okrug' value = "1" name = "okrug" type="number"  class="form-control">
          </div>

          <div class='col-sm-4'> 
            <label for="usr">Datum</label>
            <input id ='modal-datum' value = "<?= $datumDanas ?>" name = "okrug" type="text" class="form-control modal-datum">
          </div>

          <div class='col-sm-4'> 
            <label for="usr">Datum Valuta</label>
            <input id ='modal-datum-valuta' value = "<?=$datumDanas?>" name = "okrug" type="text" class="form-control modal-datum">
          </div>
        </div>
<!-- RED 2 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex">  
          <div class='col-sm-4'> 
            <label for="usr">Magacin/Lokacija</label>   
            <select id="modal-lokacija">
              <option>Magacin 1</option>
              <option>Magacin 2</option>
              <option>Magacin 3</option>
            </select>

          </div>
    
          <div class='col-sm-4'> 
            <label for="usr">Faktura</label>
            <input id ='modal-faktura' value = "" name = "okrug" type="text" class="form-control">
          </div>

          <div class='col-sm-4'> 
          <div class="form-group">
            <label for="usr">Dobavljac</label>
             <select id="modal-dobavljac">
                <option>Dobavljac 1</option>
                <option>Dobavljac 2</option>
                <option>Dobavljac 3</option>
            </select>
          </div>
          </div>
        </div>

<!--RED 3 - NAPOMENA-------------------------------------------------------------------------------------------------------- -->
        <div class='flex'>
          <div class="col-sm-12">
            <div class="form-group">
                <label for="message-text" class="col-form-label">Napomena</label>
                <textarea class="form-control" id="message-text"></textarea>
            </div>
          </div>
        </div>
      </div>
<!-- KRAJ FORME---------------------------------------------------------------------------------------------------------- -->
        <input type="hidden" name="akcija" value="<?=$akcija?>" >
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



