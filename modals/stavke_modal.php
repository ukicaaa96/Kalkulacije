<?php
include_once "../connection.php";
$akcija = $_POST['akcija'];
$datumDanas = date('d.m.Y');

$sqlArtikli = "SELECT * FROM artikli WHERE art_cdiartikal > 0";
$podaciArtikli = $conn->query($sqlArtikli);

$sqlGrupe = "SELECT * FROM grupeartikla WHERE gra_cdigrupaartikla > 0";
$podaciGrupe = $conn->query($sqlGrupe);















?>
<style>
  
  .hover-action:hover{
    background-color: #ffffff;
  }

  .dis{
    background-color: #efeff5;
  }
</style>


<!-- MODAL ------------------------------------------------------------------------------------------------------------------------- -->
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content">
<!-- MODAL HEADER----------------------------------------------------------------------------------------------------------------- -->
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Dodaj stavku</h5>
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
          <div class='col-sm-3'> 
            <label for="usr">Sifra</label>
            <input id ='modal-sifra' value = "1" name = "sifra" type="number"  class="form-control">
          </div>

          <div class='col-sm-3'> 
            <label for="usr">Grupa</label>
               <select id='modal-grupe' name='grupe'>
                    <?php
                        while ($row = $podaciGrupe->fetch_assoc()) {
                    ?>
                          <option value=<?=$row['gra_cdigrupaartikla']?>><?=$row['gra_dssnaziv']?></option>
                    <?php
                        }
                    ?>
                </select>
          </div>

          <div class='col-sm-3'> 
            <label for="usr">Artikli</label>
            <div class="d-flex">
            <div class="col-sm-11 pr-2">
               <select id='modal-artikli' name='magacin'>
                    <?php
                        while ($row = $podaciArtikli->fetch_assoc()) {
                    ?>
                          <option value=<?=$row['art_cdiartikal']?>><?=$row['art_dssnaziv']?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <div class="col-sm-1 pl-1">
              <div>
              <button class="btn btn-primary my-auto h2">+</button>
              </div>
            </div>
          </div>
         </div>

          <div class='col-sm-3'> 
            <label for="usr">Kolicina</label>
            <input id ='modal-kolicina' value = "" name = "okrug" type="text" class="form-control modal-datum">
          </div>
        </div>

<!-- RED 2 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex pt-4">  
          <div class='col-sm-3'> 
            <label for="usr">Kolicina Lagera</label>
            <input id ='modal-lager' value = "" name = "okrug" type="number"  class="form-control dis" disabled>
          </div>

          <div class='col-sm-3'> 
            <label for="usr">Nabavna cena</label>
            <input id ='modal-nabavna' value = "" name = "okrug" type="text" class="form-control modal-datum">
          </div>

          <div class='col-sm-2'>
            <label class='' for="usr">Rabat</label>
            <div class="input-group d-flex" >
                <input id ='modal-rabat' value = "" name = "okrug" type="text" class="form-control modal-datum">
              <span class="input-group-text rounded-right">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-percent rounded-right" viewBox="0 0 16 16">
                  <path d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0zM4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                </svg>
              </span>    
            </div> 
          </div>

          <div class='col-sm-2'> 
            <label for="usr">Cena sa popustom</label>
            <input id ='modal-cena-popust' value = "" name = "okrug" type="text" class="form-control modal-datum dis" disabled>
          </div>
          <div class='col-sm-2'> 
            <label for="usr">Nabavna vrednost</label>
            <input id ='modal-nabavna-vrednost' value = "" name = "okrug" type="text" class="form-control modal-datum dis" disabled>
          </div>
        </div>

<!-- RED 3 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex pt-4"> 

          <div class='col-sm-4'>
            <label class='' for="usr">Marza</label>
            <div class="input-group d-flex" >
                <input id ='modal-marza' value = "" name = "okrug" type="text" class="form-control modal-datum">
              <span class="input-group-text rounded-right">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-percent rounded-right" viewBox="0 0 16 16">
                  <path d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0zM4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                </svg>
              </span>    
            </div> 
          </div>
       
    
          <div class='col-sm-4'> 
            <label for="usr">VP cena</label>
            <input id ='modal-vpcena' value = "" name = "okrug" type="text" class="form-control dis" disabled>
          </div>

          <div class='col-sm-4'> 
            <label for="usr">MP cena</label>
            <input id ='modal-mpcena' value = "" name = "okrug" type="text" class="form-control">
          </div>
        </div>

<!-- KRAJ FORME---------------------------------------------------------------------------------------------------------- -->
        <input type="hidden" name="akcija" value="<?=$akcija?>" >
      </form>
<!-- MODAL FOOTER---------------------------------------------------------------------------------------- -->
      </div>
      <br>
        <div class="modal-footer">
            <div class= 'custom' style="width: 100%">
            <button type="button" class="btn-footer btn btn-secondary" data-dismiss="modal">Izađi</button>
            <button id="sacuvaj" type="button" class="btn-footer btn btn-primary float-right">Sačuvaj</button>
          <div>
      </div>
    </div>
  </div>



