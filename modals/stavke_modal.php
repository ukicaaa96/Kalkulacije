<?php

include_once "../connection.php";

$akcija = $_POST['akcija'];
$datumDanas = date('d.m.Y');

$sqlArtikli = "SELECT * FROM artikli WHERE art_cdiartikal > 0";
$podaciArtikli = $conn->query($sqlArtikli);

$sqlGrupe = "SELECT * FROM grupeartikla WHERE gra_cdigrupaartikla > 0";
$podaciGrupe = $conn->query($sqlGrupe);

if ($akcija == 'izmena') {


$cenaPdv =          (float)$_POST["cenaPdv"];
$cenaPopust=        (float)$_POST["cenaPopust"];
$cenaVp =           (float)$_POST["cenaVp"];
$idStavke =         (float)$_POST["id"];
$iznosFinal =       (float)$_POST["iznos"];
$kolicina =         (float)$_POST["kolicina"];
$marza =            (float)$_POST["marza"]; 
$nabavna =          (float)$_POST["nabavna"];   
$nabVrednost =      (float)$_POST["nabavnaVrednost"];
$rabat =            (float)$_POST["rabat"];   
$sifra =            $_POST["sifra"];

$sqlArtikalId = "SELECT kad_cdiartikal FROM kalkulacijedetail WHERE kad_cdikalkulacijadetail = ". $idStavke;
$podaciArtId = $conn->query($sqlArtikalId);

$idArtikla = $podaciArtId->fetch_assoc()['kad_cdiartikal'];

  $sqlKolicina = 'SELECT * FROM artikli INNER JOIN artiklixmagacini ON artikli.art_cdiartikal = artiklixmagacini.axm_cdiartikal
  WHERE artiklixmagacini.axm_cdiartikal = '.$idArtikla.' AND artiklixmagacini.axm_cdimagacin = '.$_COOKIE['id-magacin'];

  $podaciKolicina = $conn->query($sqlKolicina);

  while ($row = $podaciKolicina->fetch_assoc()) {
      $lager =  $row['axm_vlnkolicina'];

  }
//poreska
    $sql = "SELECT * FROM artikli where art_cdiartikal =". $idArtikla;
    $podaciPoreska = $conn->query($sql);
    while ($row = $podaciPoreska->fetch_assoc()) {

    $poreskaId = $row['art_cdiporeskastopa'];

    }
 
    $vratiPdv = "SELECT * FROM poreskestope WHERE pos_cdiporeskastopa = " . $poreskaId;
    $podaciPoreska = $conn->query($vratiPdv);

    while ($row=$podaciPoreska->fetch_assoc()) {
      $poreskaStopa = $row['pos_vlniznos'];
    }
}

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
            <?php
              if ($akcija == 'izmena') {
            ?>
              <input id ='modal-sifra' value = "<?= $sifra ?>" name = "sifra" type="number"  class="form-control">
            <?php 
              }
              else{
                ?>
              <input id ='modal-sifra' value='' name = "sifra" type="number"  class="form-control">
              <?php
                }
              ?>
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
               <select class="selektovan-artikal" id='modal-artikli' name='artikal'>
                        <?php
                          while ($row = $podaciArtikli->fetch_assoc()) {
                          if ($row['art_cdiartikal'] == $idArtikla) {
                            $_COOKIE['id-artikal-modal'] = $idArtikla;
                          ?>
                          <option> selected value=<?=$row['art_cdiartikal']?>><?=$row['art_dssnaziv']?></option>

                          <?php
                          }
                          else{   
                          ?>
                          <option value=<?=$row['art_cdiartikal']?>><?=$row['art_dssnaziv']?></option>
                          <?php
                          }
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
            <?php
              if ($akcija == 'izmena') {
            ?>
              <input id ='modal-kolicina' value = "<?= $kolicina ?>" name = "kolicina"  class="form-control">
            <?php 
              }
              else{
                ?>
              <input id ='modal-kolicina' value='' name = "kolicina"  class="form-control">
              <?php
                }
              ?>
          </div>
        </div>

<!-- RED 2 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex pt-4">  
          <div class='col-sm-3'> 
            <label for="usr">Kolicina Lagera</label>





            <?php
              if ($akcija == 'izmena') {
            ?>
              <input id ='modal-lager' value = "<?= $lager ?>" name = "lager"  class="form-control" readonly>
            <?php 
              }
              else{
                ?>
              <input id ='modal-lager' value='' name = "lager"   class="form-control" readonly>
              <?php
                }
              ?>






          </div>

          <div class='col-sm-3'> 
            <label for="usr">Nabavna cena</label>





            <?php
              if ($akcija == 'izmena') {
            ?>
              <input id ='modal-nabavna' value = "<?= $nabavna ?>" name = "nabavna"  class="form-control">
            <?php 
              }
              else{
                ?>
              <input id ='modal-nabavna' value='' name = "nabavna"  class="form-control">
              <?php
                }
              ?>






          </div>

          <div class='col-sm-2'>
            <label class='' for="usr">Rabat</label>
            <div class="input-group d-flex" >
                

            <?php
              if ($akcija == 'izmena') {
            ?>
              <input id ='modal-rabat' value = "<?= $rabat ?>" name = "rabat" class="form-control">
            <?php 
              }
              else{
                ?>
              <input id ='modal-rabat' value='' name = "rabat" class="form-control">
              <?php
                }
              ?>




              <span class="input-group-text rounded-right">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-percent rounded-right" viewBox="0 0 16 16">
                  <path d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0zM4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                </svg>
              </span>    
            </div> 
          </div>

          <div class='col-sm-2'> 
            <label for="usr">Cena sa popustom</label>
           

              <?php
              if ($akcija == 'izmena') {
            ?>
              <input id ='modal-cena-popust' value = "<?= $cenaPopust ?>" name = "cena-popust"  class="form-control" readonly>
            <?php 
              }
              else{
                ?>
              <input id ='modal-cena-popust' value = "" name = "cena-popust" class="form-control" readonly>
              <?php
                }
              ?>


          </div>
          <div class='col-sm-2'> 
            <label for="usr">Nabavna vrednost</label>



              <?php
              if ($akcija == 'izmena') {
            ?>
              <input id ='modal-nabavna-vrednost' value = "<?= $nabVrednost ?>" name = "nabavna-vrednost" type="number"  class="form-control" readonly>
            <?php 
              }
              else{
                ?>
              <input id ='modal-nabavna-vrednost' value = "" name = "nabavna-vrednost" type="number"  class="form-control" readonly>
              <?php
                }
              ?>



          </div>
        </div>

<!-- RED 3 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex pt-4"> 

          <div class='col-sm-4'>
            <label class='' for="usr">Marza</label>
            <div class="input-group d-flex" >




              <?php
                if ($akcija == 'izmena') {
              ?>
              <input id ='modal-marza' value = "<?= $marza ?>" name = "marza"  class="form-control">
            <?php 
              }
              else{
                ?>
              <input id ='modal-marza' value = "" name = "marza"  class="form-control">
              <?php
                }
              ?>





              <span class="input-group-text rounded-right">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-percent rounded-right" viewBox="0 0 16 16">
                  <path d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0zM4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                </svg>
              </span>    
            </div> 
          </div>
       
    
          <div class='col-sm-4'> 
            <label for="usr">VP cena</label>
         





              <?php
                if ($akcija == 'izmena') {
              ?>
              <input id ='modal-vpcena' value = "<?= $cenaVp ?>" name = "modal-vpcena" type="number"  class="form-control" readonly>
            <?php 
              }
              else{
                ?>
              <input id ='modal-vpcena' value='' name = "modal-vpcena" type="number"  class="form-control" readonly>
              <?php
                }
              ?>






          </div>

          <div class='col-sm-4'> 
            <label for="usr">MP cena</label>
          

              <?php
                if ($akcija == 'izmena') {
              ?>
              <input id ='modal-mpcena' value = "<?= $iznosFinal ?>" name = "modal-mpcena" class="form-control">
            <?php 
              }
              else{
                ?>
              <input id ='modal-mpcena' value='' name = "modal-mpcena"  class="form-control">
              <?php
                }
              ?>

          </div>
        </div>

<!-- KRAJ FORME---------------------------------------------------------------------------------------------------------- -->
        <input type="hidden" name="akcija" value="<?= $akcija ?>" >
        <?php
        if ($akcija == 'izmena') {
        ?>
          <input type="hidden" name="id-artikla" value="<?= $idArtikla ?>" >
          <input id='ps' type="hidden" name="porez" value="<?= $poreskaStopa ?>" >
          <input id='stavka-detail' type="hidden" name="id-stavka" value="<?= $idStavke ?>" >
        <?php
        }
        ?>
        <input type="hidden" name="akcija" value="<?= $akcija ?>" >
      </form>
<!-- MODAL FOOTER---------------------------------------------------------------------------------------- -->
      </div>
      <br>
        <div class="modal-footer">
            <div class= 'custom' style="width: 100%">
            <button type="button" class="btn-footer btn btn-secondary" data-dismiss="modal">Odustani</button>
            <button id="snimi" type="button" class="btn-footer btn btn-primary float-right">Snimi</button>
          <div>
      </div>
    </div>
  </div>


<script>
  
  $( document ).ready(function() {
//Ucitavanje
    var poreska = $('#ps').val();
    $('#porez-input').val(poreska);


//Provera sifre artikla ---------------------------------------------------------------------
    $("#modal-sifra").focusout(function(){

        var sifraArtikla = $(this).val()
        var str = {sifra : sifraArtikla}
        $.ajax({
            url: "./ajax/provera_sifre_artikla.php",
            method: "POST",
            data: str
            }).success(function(response) {

                var json = JSON.parse(response)
                
                if(json['poruka'] == 'ok'){

                  console.log("str");
                }
                else{
                  console.log('nstr');
                }

        })
    });






  })
</script>


