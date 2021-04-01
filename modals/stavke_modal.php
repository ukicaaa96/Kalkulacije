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
    $grupaId = $row['art_cdigrupaartikla'];

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
  .skriven{
    display: none !important;
  }
  #add{
    background-color: #92a8d1;
  }

  .fa{
    color: white;
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
    <div class="modal-body p-0">
<!--POCETAK FORME ---------------------------------------------------------------------------------------------------------- -->
    <form id='modalForm'>
      <div class='d-block'>
<!-- RED 1 ----------------------------------------------------------------------------------------------------------- -->
        <div class="d-flex pt-3 pb-2">  

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
          <div class="upis-grupe"> 
            <label for="usr">Grupa</label>
               <select id='modal-grupe' name='grupe'>

                <?php if($akcija == 'novo'){ ?>
                  <option selected value="-1">Izaberite grupu</option>
                <?php while ($row = $podaciGrupe->fetch_assoc()) { ?>
                    <option value=<?=$row['gra_cdigrupaartikla']?>><?=$row['gra_dssnaziv']?></option>  
                  <?php } ?>
                <?php } ?>
<!-- ---------------------------------------------------------------------------------------------------------------------- -->
                <?php if ($akcija == 'izmena') { ?>
                  <?php while ($row = $podaciGrupe->fetch_assoc()) { ?>
                    <?php if ($row['gra_cdigrupaartikla'] == $grupaId) { ?>
                      <option selected value=<?=$row['gra_cdigrupaartikla']?>><?=$row['gra_dssnaziv']?></option>  
                    <?php } ?>
                    <?php if ($row['gra_cdigrupaartikla'] != $grupaId) { ?>
                      <option value=<?=$row['gra_cdigrupaartikla']?>><?=$row['gra_dssnaziv']?></option>  
                    <?php } ?>
                  <?php } ?>
                <?php } ?>

                </select>
              </div>
          </div>

          <div class='col-sm-3'> 
            <label for="usr">Artikli</label>
            <div class="d-flex upis-artikla">
            <div class="col-sm-11 pr-2">
               <select class="selektovan-artikal" id='modal-artikli' name='artikal'>
                <?php
                if ($akcija != 'izmena') {
                ?>

                <option selected value="-1">Izaberi artikal</option>

                <?php
                }
                ?>
                    <?php
                      while ($row = $podaciArtikli->fetch_assoc()) {
                      if ($row['art_cdiartikal'] == $idArtikla) {
                        $_COOKIE['id-artikal-modal'] = $idArtikla;
                      ?>
                      <option selected value=<?=$row['art_cdiartikal']?>><?=$row['art_dssnaziv']?></option>

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
              <button id='dodaj-artikal' type="button" class="btn btn-primary my-auto h2" data-toggle="collapse" data-target="#demo">+</button>
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






<!-- ADDD -->


        <div id="add" class="skriven collapse d-flex pb-3">  
          <div class='col-sm-3'> 
            <label for="usr">Naziv</label>    
            <input id ='naziv-artikla-add' value='' name = "naziv-artikla-add"  class="form-control">
          </div>

          <div class='col-sm-3'> 
            <label for="usr">Grupa</label>
            <input id ='grupa-artikla-add' value='' name = "grupa-artikla-add"  class="form-control">
          </div>

          <div class='col-sm-3'>
            <label class='' for="usr">Poreske stope</label>        
            <input id ='poreska-stopa-add' value = "" name = "poreska-stopa-add" class="form-control"> 
          </div>

           <div class="col-md-3 d-flex align-items-end ">  
              <div class="form-group m-0">
                <a class="btn btn-danger " title="Odustani" id="bu-artikal-close" ><i class="fa fa-close "></i></a>
                <a class="btn btn-success " title="Sačuvaj artikal" id="bu-artikal-save" ><i class="fa fa-check "></i></a>
              </div>
            </div>
        </div>



<!-- ADDD KRAJ -->






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
          <input id='grupa-art' type="hidden" name="grupa-art" value="<?= $idStavke ?>" >

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

    function postaviNule(bool = true){

      if (bool == false) {
        $('#modal-sifra').val(0)
        $('#porez-input').val(0)
      }

      $('#modal-mpcena').val(0)
      $('#modal-vpcena').val(0)
      $('#modal-modal-marza').val(0)
      $('#modal-nabavna-vrednost').val(0)
      $('#modal-nabavna').val(0)
      $('#modal-rabat').val(0)
      $('#modal-marza').val(0)
      $('#modal-kolicina').val(1)
      $('#modal-lager').val(0)
      $('#modal-cena-popust').val(0)

    }


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
                console.log(json);
                if(json['poruka'] == 'ok'){
                  postaviNule(false)
                  $('.upis-artikla').children().remove()
                  $('.upis-artikla').replaceWith(json['artikli'])
                  $('.upis-grupe').replaceWith(json['grupe'])
                  $('#modal-lager').val(json['lager'])
                  $('#porez-input').val(json['porez']);

                  $('#modal-artikli').select2({
                    theme:'bootstrap'
                  })

                  $('#modal-grupe').select2({
                    theme:'bootstrap'
                  })
                }
                else{
                  console.log('nstr');
                }

        })
    });


    $('#modal-grupe').on('change', function(){

      postaviNule(false)

        var idGrupe = $(this).val()

        str = {'id' : idGrupe}
        //console.log(idGrupe);
        $.ajax({
            url: "./ajax/artikli_po_grupi.php",
            method: "POST",
            data: str
            }).success(function(response) {

              var json = JSON.parse(response);
              console.log('mg' + json)
              $('.upis-artikla').replaceWith(json['artikli'])
              $('#modal-lager').val(0)
              $('#porez-input').val(0)

              $('#modal-artikli').select2({
                  theme:'bootstrap'
              })

            })
      })



    $('#dodaj-artikal').on('click', function(){

      var element = $('#add')
      
      if (element.hasClass('skriven')) {
        element.removeClass('skriven')
      }
      else{
        element.addClass('skriven')
      }


    })








  })
</script>


