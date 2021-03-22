<?php
include_once "../connection.php";

$akcija =  $_POST['akcija'];

$sqlOkruzi = "SELECT okr_dssnaziv,okr_cdiokrug FROM okruzi WHERE okr_cdiokrug>0";
$okruzi = $conn->query($sqlOkruzi);

if($akcija == 'izmena'){

  $sqlMesto = "SELECT mes_dssnaziv, mes_cdiokrug, mes_dsspostanskibroj, mes_cdimesto FROM mesta WHERE mes_cdimesto =".$_POST['id']; 
  $podaci = $conn->query($sqlMesto);
  $podaciMesto = $podaci->fetch_assoc();

}

?>
<style>
    .custom{
        width:100%;
    }
</style>

  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <?php 

          if($akcija == 'novo'){
            $naslov = 'Dodavanje novog mesta';
          }
          if($akcija == 'izmena'){
            $naslov = "Izmeni mesto";
          }


          ?>
        <h5 class="modal-title" id="exampleModalLongTitle"><?=$naslov?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id='modalForm'>
        <label for="usr">Naziv mesta:</label>
        <?php
              if($akcija == 'novo'){
              $naziv = '';
            }
            if($akcija == 'izmena'){
              $naziv = $podaciMesto['mes_dssnaziv'];
            }
        ?>
        <input id ='modal-mesto' value = "<?=$naziv?>" name = "mesto" type="text" class="form-control">

        <label for="usr">Okrug:</label>
            <div class='d-block'>
                <select id='okrugSelect' name='okrug' class="mb-3">
                    <option value='-1' selected>izaberi okrug:</option>
                    <?php
                        
                        while ($row = $okruzi->fetch_assoc()) {

                            if($row['okr_cdiokrug']==$podaciMesto['mes_cdiokrug']){

                              ?>
                              <option value=<?=$row['okr_cdiokrug']?> selected><?=$row['okr_dssnaziv']?></option>
                              <?php
                            }


                        ?>
                    <option value=<?=$row['okr_cdiokrug']?>><?=$row['okr_dssnaziv']?></option>
                        <?php
                        }
                        ?>
                </select>
            </div>

        <label for="usr">Poštanski Broj:</label>
         <?php
              if($akcija == 'novo'){
              $pb = '';
            }
            if($akcija == 'izmena'){
              $pb = $podaciMesto['mes_dsspostanskibroj'];
            }
        ?>
        <input id ='modal-postanskibroj' value="<?=$pb?>"name = 'postanskiBroj' type="text" class="form-control">
        <input type="hidden" name="akcija" value="<?=$akcija?>" >
        <input type="hidden" name="idMesto" value="<?=$podaciMesto['mes_cdimesto']?>" >
        </form>
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
              <button id="sacuvaj" type="button" class="btn-footer btn btn-primary float-right" hidden="hidden" >Sačuvaj</button>
            <?php
            }
            ?>

            <div>
      </div>
    </div>
  </div>

