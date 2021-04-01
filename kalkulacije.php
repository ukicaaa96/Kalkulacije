<?php
include_once 'connection.php';
$datumDanas = date('d.m.Y');

$danas = strtotime('now');
$sedamDana = 60*60*24*7;
$pre7dana = $danas - $sedamDana;

$pre7Dana = date('d.m.Y', $pre7dana);



$sqlMagacini = "SELECT mag_dssnaziv, mag_cdimagacin FROM magacini ORDER BY mag_dssnaziv ASC;";
$podaciMagacini = $conn->query($sqlMagacini);

$sqlDobavljaci = "SELECT dob_dssnaziv, dob_cdidobavljac FROM dobavljaci ORDER BY dob_dssnaziv ASC;";
$podaciDobavljaci = $conn->query($sqlDobavljaci);


?>

<!doctype html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--     <link rel='stylesheet' href='select2.css'> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" integrity="sha512-kq3FES+RuuGoBW3a9R2ELYKRywUEQv0wvPTItv3DSGqjpbNtGWVdvT8qwdKkqvPzT93jp8tSF4+oN4IeTEIlQA==" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Hello, world!</title>
    <style>
        .select2-container{
            width:100% !important;
        }

        .select2-dropdown {
          z-index: 10051 !important;
        }

        .modal-dialog{
            max-width: 70%;
        }

        .test{
            height: 350px !important;
            overflow: scroll !important;
        }

        .selektovan{
            background-color:rgba(0, 255, 0, 0.4) !important;
            border-color : black;
        }
</style>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-center">
        <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
            <div class="navbar-nav justify-content-center" >
                <a class="nav-item nav-link" href="http://localhost/ub_test/">Pocetna</a>
                <a class="nav-item nav-link" href="http://localhost/ub_test/mesta.php">Mesta</a>
                <a class="nav-item nav-link" href="http://localhost/ub_test/okruzi.php">Okruzi</a>
                <a class="nav-item nav-link" href="#">Drzave</a>
                <a class="nav-item nav-link" href="http://localhost/ub_test/kalkulacije.php">KALKULACIJE</a>
            </div>
        </div>
    </nav>

<div class = "col-sm-10 container pt-3">
<!-- PRETRAGA------------------------------------------------------------------------------------------------------------- -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Petraga
                    </div>
                    <div class="card-body ">
                    <form method="POST" id = 'formaPretraga'>
                        <input type="hidden" name="akcija" value="pretraga">
                         <input type="hidden" name="komanda" value="1">
                         <input id="porez-input" value="" type="hidden" name="porez">
                        <div class="d-block">
                            <div class="form-group d-flex ">
                                <div class = "col-sm-2">
                                    <label for="usr">Datum od</label>
                                    <input id='datum-od' value="<?=$pre7Dana?>" data-provide="datepicker" name = 'datum-od' type="text" class="form-control datum">
                                </div>

                                <div class = "col-sm-2">
                                    <label for="usr">Datum do</label>
                                    <input id='datum-do' value="<?= $datumDanas ?>"data-provide="datepicker" name = 'datum-do' type="text" class="form-control datum">
                                </div>

                                <div class = "col-sm-2">
                                    <label for="usr">Magacini/Lokacije</label>
                                       <select id="magacin-pretraga" name='magacin-pretraga' class="form-control">
                                            <option value="-1" selected>Izaberite</option>
                                            <?php
                                                while ($row = $podaciMagacini->fetch_assoc()) {
                                                    if($row['mag_cdimagacin'] != '0'){
                                                        $idMagacina = $row['mag_cdimagacin'];
                                                        $nazivMagacina = $row['mag_dssnaziv'];
                                            ?>
                                                <option value="<?=$idMagacina?>"><?=$nazivMagacina?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                </div>

                                <div class = "col-sm-2">
                                    <label for="usr">Dobavljac</label>
                                       <select id="dobavljac-pretraga" name="dobavljac-pretraga" class="form-control">
                                            <option value="-1" selected>Izaberite</option>
                                            <?php
                                                while ($row = $podaciDobavljaci->fetch_assoc()) {
                                                    if ($row['dob_cdidobavljac'] != '0') {
                                                        $idDobavljaca = $row['dob_cdidobavljac'];
                                                        $nazivDobavljaca = $row['dob_dssnaziv'];
                                            ?>
                                                <option value="<?=$idDobavljaca?>"><?=$nazivDobavljaca?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                </div>

                                <div class = "col-sm-2">
                                    <label for="usr">Naziv artikla</label>
                                    <select name="artikal-pretraga" id="artikli-pretraga" class="form-control"></select>
                                </div>

                                <div class = "col-sm-2">
                                    <label for="usr">Napomena</label>
                                    <input placeholder="Napomena" id='napomena' name = 'napomena' type="text" class="form-control">
                                </div>
                            </div>  
                        <div class=" col-sm-3 form-group d-flex ">                                 
                            <button id = 'pretraga' class=" pl-3 pr-3 btn btn-primary">Pretraži</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- KALKULACIJE--------------------------------------------------------------------------------------- -->
    <div class = 'row overflow-scroll'>
        <div class="col-sm-12 pt-3">
            <div class="card">
                <div class="card-header">                   
                    <div class='float-left'>
                        <h4>Spisak kalkulacija:</h4>
                    </div>              
                    <div class='float-right'>       
                        <button id='novo-kalkulacija' class = 'btn btn-primary float-right'>Novo+</button>
                    </div>                      
                </div>
                <div class="card-body">
                    <div class="test">
                        <table id="example"  class="table table-striped table-bordered table-hover">
                            <thead>
                                <p class='nabavna-vrednost'hidden>0</p>
                                <p class='prodajna-vrednost'hidden>0</p>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-right">Broj</th>
                                    <th class="">Datum</th>
                                    <th class="">Datum valuta</th>
                                    <th class="">Faktura</th>
                                    <th class="">Magacin/Lokacija</th>
                                    <th class="text-right">Nabavna vrednost</th>
                                    <th class="text-right">Podajna vrednost</th>
                                    <th class="text-center">Akcija</th>
                                </tr>
                            </thead>
                        
                            <tbody class="t-main" id="kalkulacija-main">


                            <!-- KALKUACIJA MAIN -->


                            </tbody>
                         
                            <tfoot>
                                <tr>
                                    <th class=" border-0 text-center"></th>
                                    <th class=" border-0 "></th>
                                    <th class=" border-0 "></th>
                                    <th class=" border-0 "></th>
                                    <th class=" border-0 "></th>
                                    <th class=" border-0 "></th>
                                    <th id='t-n'class="text-right">0.00</th>
                                    <th id='t-p'class="text-right">0.00</th>
                                    <th class=" border-0  text-center"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="d-flex card-footer text-righ flex-row-reverse">

                    <h5 class = 'd-flex pl-1 pr-1' id='sum-prodajna'>Sum prodajne:<p id="p-v"><p></h5><h5>/</h5>
                    <h5 class = 'd-flex pl-0 pr-1' id='sum-nabavna'>Sum nabavne:<p id="n-v"><p></h5>
                    
                </div>
            </div>
        </div>
    </div>

<!-- STAVKE--------------------------------------------------------------------------------------------------- -->
    <div class = 'row'>
        <div class="col-sm-12 pt-3">
            <div class="card">
                <div class="card-header">                   
                    <div class='float-left'>
                        <h4>Stavke:</h4>
                    </div>              
                    <div class='float-right'>       
                        <button id='novo-stavka' class = 'btn btn-primary float-right'>Novo+</button>
                    </div>                      
                </div>
                <div class="card-body">
                    <div class=sm-col-12>
                        <table id="example"  class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Sifra</th>
                                    <th class="">Artikli</th>
                                    <th class="text-right">Kolicina</th>
                                    <th class="text-right">Nabavna cena</th>
                                    <th class="text-right">Rabat</th>
                                    <th class="text-right">Cena sa popustom</th>
                                    <th class="text-right">Marza</th>
                                    <th class="text-right">VP cena</th>
                                    <th class="text-right">Cena sa PDV</th>
                                    <th class="text-right">Nabavna vrednost</th>
                                    <th class="text-right">VP iznos</th>
                                    <th class="text-right">Iznos sa PDV</th>
                                    <th class="text-center">Novo</th>
                                </tr>
                            </thead>

                            <tbody id="kalkulacija-detail" >
                                <!-- KALKULACIJE DETAILS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- MODAL --------------------------------------------------------------------------------------------------- -->
    <div id='kalkulacijeModal' class="modal fade modal-xl" tabindex="-1" role="dialog"></div>

<!-- Include scripts--------------------------------------------------------------------------------------------------- -->

    <script src="jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="bootbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js.cookie.min.js"></script>
    <script type="text/javascript" src="jquery.cookie.min.js"></script>
<!-- ----------------------------------------------------------------------------------------------------------------------------- -->
    <script>

    
  
    $( document ).ready(function() {
//***Ucitavanje --------------------------------------------------------------------------------------------
        $.cookie('dobavljac-pretraga-id', '-1');
        $.cookie('magacin-pretraga-id', '-1');
        $.cookie('napomena-pretraga', '-1');
        $.cookie('artikal-pretraga-id', '-1');
        $.cookie('id-magacin', '-1');

        var formatiranje = new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 2 })


         function postaviNule(bool = true){

          if (bool = false) {
            $('#modal-sifra').val("")
            $('#porez-input').val(0)
            $('#modal-lager').val(0)
          }

          $('#modal-mpcena').val(0)
          $('#modal-vpcena').val(0)
          $('#modal-nabavna-vrednost').val(0)
          $('#modal-nabavna').val(0)
          $('#modal-rabat').val(0)
          $('#modal-marza').val(0)
          $('#modal-kolicina').val(1)
          $('#modal-cena-popust').val(0)

        }



        function ucitajKalkulacije(idMain = -1){

        var id = idMain;
        console.log('id '+id)
        $.cookie('C_datum-od', $("#datum-od").val())
        $.cookie('C_datum-do', $("#datum-do").val())

        var str = {akcija : 'pretraga', komanda : '0'}

        $.ajax({
                url: "./ajax/kalkulacije_main.php",
                method: "POST",
                data: str
                }).success(function(response) {
                  
                    var json = JSON.parse(response)
                    $('#kalkulacija-main').html(json['html']);

                    $('#n-v').text(json['sumaNabavne'])
                    $('#t-n').text(json['sumaNabavne'])
                    $('#p-v').text(json['sumaProdajne'])
                    $('#t-p').text(json['sumaProdajne'])
                    $('[data-toggle="tooltip"]').tooltip()
                    
                    $( ".main" ).each(function() {
                    if($(this).attr("id") == id){
        
                        $(this).addClass('selektovan');
                    }
                    else{
                        $(this).removeClass("selektovan")
                    }
                    
                    });
                });
            }
          
            ucitajKalkulacije()


//***Racunanje vrednosti ------------------------------------------------------------------------------------------

    $('.datum').datepicker({
      format: 'dd.mm.yyyy'
    });

//***Dodavanje kalkulacije ------------------------------------------------------------------------------------------

    $('#novo-kalkulacija').on('click', function(e){
        $('#kalkulacijeModal').load('./modals/kalkulacije_modal.php',{'akcija':'novo'},function(){
            $('.modal-datum').datepicker({
              format: 'dd.mm.yyyy'
            });
            $('#modal-dobavljac').select2({
                theme:'bootstrap'
            });
            $('#modal-lokacija').select2({
                theme:'bootstrap'
            });
            $('#kalkulacijeModal').modal('show')
        })
    })

//***Vracanje stavki---------------------------------------------------------------------------------------------------

    $('body').on('click','.main',function(e){


        var idKam = $(this).attr("id");
        var idMag = $(this).find(".izmenaKalkulacije").attr('data-id-magacin');

        $.cookie("id-main", idKam);
        $.cookie("id-magacin", idMag)

        var str = {akcija:'pretraga', id : idKam}


        $( ".main" ).each(function() {
            if($(this).attr("id") == idKam){

                $(this).addClass('selektovan');
            }
            else{

                $(this).removeClass("selektovan")
            }
        });
         $.ajax({
            url: "./ajax/kalkulacije_detail.php",
            method: "POST",
            data: str
            }).success(function(response) {
             

                var json = JSON.parse(response)
                if(json['poruka'] == "jok"){

                    $('#kalkulacija-detail').html("");
                    $('[data-toggle="tooltip"]').tooltip()

                }
                else{

                    $('#kalkulacija-detail').html(json['html']);
                    $('[data-toggle="tooltip"]').tooltip()
                }
            });
        })


    //*** Dodavanje stavke -----------------------------START-------------------------------------------------------------

    $('#novo-stavka').on('click', function(e){

        if ($.cookie('id-magacin') != '-1' && $.cookie('id-main') != '-1') {
            $('#kalkulacijeModal').load('./modals/stavke_modal.php',{'akcija':'novo'},function(){
                $('#kalkulacijeModal').modal('show')

                $('#modal-artikli').select2({
                    theme:'bootstrap'
                });


                $('#modal-grupe').select2({
                    theme:'bootstrap'
                });
            })
    }
    else{
        alert('izaberite kalkulaciju')
    }
    })


//*** Na promenu artikla ---------------------------------------------------------------------------------------

    $('body').on('change keyup','#modal-artikli', function(){
        
        var idArt = $(this).val()
        var idMag = $.cookie('id-magacin')

        var str = {akcija:"popuni", idArtikla : idArt, idMagacina: idMag}
        $.ajax({
            
            url: "./ajax/magacini_artikli.php",
            method: "POST",
            data: str
            }).success(function(response) {
               
                var json = JSON.parse(response)
                console.log(json);
                $('#modal-lager').val(json['lager'])
                $('#modal-sifra').val(json['sifra'])
                $('#porez-input').val(json['porez'])

                postaviNule()

               
            });
    });


//*** Na promenu nabavne---------------------------------------------------------------------------------------------

    $('body').on('change keyup','#modal-nabavna', function(e){

        var nabavnaCena = parseInt($(this).val())
        var porez = parseInt($('#porez-input').val())
        var kolicina = parseInt($('#modal-kolicina').val())

        var rabat = parseInt($('#modal-rabat').val())/100
        if(isNaN(rabat)){
            rabat = 0
        }

        
        
        var mpCena = (nabavnaCena*(porez/100)) + nabavnaCena
        var cenaPopust = nabavnaCena - (nabavnaCena * rabat)
        var nabavnaVrednost = cenaPopust * kolicina

        if (mpCena == NaN || mpCena <0 || isNaN(mpCena)) {

            $('#modal-mpcena').val(0)
        }
        else{

            $('#modal-mpcena').val(mpCena)
        
        }

        if (cenaPopust == NaN || cenaPopust <0 || isNaN(cenaPopust)) {

            $('#modal-cena-popust').val(0)
        }
        else{

            $('#modal-cena-popust').val(cenaPopust)
        
        }
        


        if (nabavnaVrednost == NaN || nabavnaVrednost <0 || isNaN(nabavnaVrednost)) {

            $('#modal-nabavna-vrednost').val(0)
        }
        else{

            $('#modal-nabavna-vrednost').val(nabavnaVrednost)
            $('#modal-vpcena').val(nabavnaVrednost)
        
        }
    })



    $('body').on('change keyup','#modal-kolicina', function(e){

        var kolicina = parseInt($(this).val())
        var cenaPopust= parseInt($('#modal-cena-popust').val())


        if(kolicina>0){
            var nabavnaNovo = kolicina * cenaPopust
            $('#modal-nabavna-vrednost').val(nabavnaNovo)
        }
        else{
            if (isNaN(kolicina)) {
                $('#modal-nabavna-vrednost').val(0)
            }else{

            $('#modal-nabavna-vrednost').val(cenaPopust.toFixed(2))
            }
        }
    })

//*** Na promenu rabata-----------------------------------------------------------------------------------------------

    $('body').on('change keyup','#modal-rabat', function(e){
        var rabat = parseInt($(this).val())/100
        var nabavna = parseInt($('#modal-nabavna').val())
        var kolicina = parseInt($('#modal-kolicina').val())

        var cena = nabavna - (rabat * nabavna)

        if(isNaN(cena)){
            $('#modal-cena-popust').val(0)
            $('#modal-nabavna-vrednost').val(0)
            $('#modal-vpcena').val(0)
        }
        else{
            $('#modal-cena-popust').val(cena.toFixed(2))
            $('#modal-nabavna-vrednost').val((cena*kolicina).toFixed(2))
            $('#modal-vpcena').val(cena.toFixed(2))
    }

    })

//*** Na promenu marze-----------------------------------------------------------------------------------------------

    $('body').on('change keyup','#modal-marza', function(e){

        var marza = parseInt($(this).val())/100
        var vpcena = parseInt($('#modal-nabavna-vrednost').val())
        var nabavna = parseInt($('#modal-nabavna').val())
        var porez = parseInt($('#porez-input').val())/100

        var mpcena = nabavna + nabavna*porez

        console.log(marza)

        var vpcenaFinal = vpcena + (marza*vpcena)
        var mpcenaFinal = mpcena + (marza*mpcena)

        if (isNaN(vpcenaFinal)) {
            $('#modal-vpcena').val($('#modal-cena-popust').val())
        }
        else{
            $('#modal-vpcena').val(vpcenaFinal.toFixed(2))
        }
        if (isNaN(mpcenaFinal)){
            var nab = parseInt($('#modal-nabavna').val())
            var porez = parseInt($('#porez-input').val())/100
            $('#modal-mpcena').val(nab + porez* nab)
        }
        else{
            $('#modal-mpcena').val("")
            $('#modal-mpcena').val(mpcenaFinal.toFixed(2))
        }
    })
                

//***--------------------------------------END----------------------------------------------------------------

//***Artikli pretraga select2 ------------------------------------------------------------------------------------------

    $('#artikli-pretraga').select2({
        theme: 'bootstrap',
        language: {
        inputTooShort: function(args) {

            total = args.minimum-args.input.length
            if(total == 3){
            return "Unesite minimum 3 karaktera" ;
              }
              if(total == 2){
                return "Unesite jos "+ total + " karaktera" ;
              }
              if(total ==1){
                return "Unesite jos "+ total + " karakter" ;
              }
        },

        noResults: function() {
            return "Ni jedan artikal nije pronadjen";
        },

        searching: function() {
            return "Pretraga je u toku...";
        },


      },
      placeholder: 'Artikli',
      minimumInputLength: 3,
      allowClear: true,
      ajax: {
        url: `ajax/vrati_artikle.php`,
        dataType: 'json',
        type: 'post',
        delay: 100,
        processResults: function (data) {
          //console.log(data);
          return {
            results: data.res
          };
        },
        cache: true
      }
    });
    $('#artikli-pretraga').css('font-family','Times New Roman').css('font-weight','bold').css('visibility','hidden');

//***PRETRAGA ------------------------------------------------------------------------------------------

    $("#pretraga").on("click",function(e){
        $('#kalkulacija-detail').html("")
        e.preventDefault()
        $.cookie('C_datum-od', $("#datum-od").val())
        $.cookie('C_datum-do', $("#datum-do").val())
        var str = $("#formaPretraga").serialize()
    
        $.ajax({
            url: "./ajax/kalkulacije_main.php",
            method: "POST",
            data: str
            }).success(function(response) {
                if(response == ""){
                    $('#kalkulacija-main').html("");

                    $('#n-v').text("")
                    $('#t-n').text("")
                    $('#p-v').text("")
                    $('#t-p').text("")
                }
                else{

                    var json = JSON.parse(response)
                    $('#kalkulacija-main').html(json['html']);

                    $('#n-v').text(json['sumaNabavne'])
                    $('#t-n').text(json['sumaNabavne'])
                    $('#p-v').text(json['sumaProdajne'])
                    $('#t-p').text(json['sumaProdajne'])

                    $('[data-toggle="tooltip"]').tooltip()
                }
            });
        
    })

//***IZMENA KAM------------------------------------------------------------------------------------------------

    $('body').on('click','.izmenaKalkulacije', function(e){
        var idKalkulacije = $(this).attr("data-id-kalkulacija");
        var idMagacina = $(this).attr("data-id-magacin");
        var datumKalkulacije = $('#'+idKalkulacije+' .datum-kalkulacije').html()
        var datumValute = $('#'+idKalkulacije+' .datum-valute').html()

        $('#kalkulacijeModal').load('./modals/kalkulacije_modal.php',{'akcija':'izmena' , 'idKalkulacije' : idKalkulacije,
        'idMagacina':idMagacina, 'datumKalkulacije' : datumKalkulacije, 'datumValute' : datumValute},function(){
            $('#kalkulacijeModal').modal('show')
            $('#modal-lokacija').select2({
                theme:'bootstrap'
            });
            $('#modal-dobavljac').select2({
                  theme:'bootstrap'
            })
        })
    })

//***BRISANJE KAM------------------------------------------------------------------------------------------------

    $('body').on('click','.brisanjeKalkulacije', function(e){
        
        var idKalkulacije = $(this).attr("data-id-kalkulacija");

        bootbox.confirm({
                message: "Da li ste sigurni da zelite da izbrisete kalkulaciju?",
                buttons: {
                    confirm: {
                        label: 'Da',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Ne',
                        className: 'btn-danger'
                    }
                },

                callback: function (result) {
                    
                if(result){

                    var str = {
                    akcija: 'brisanje',
                    id: idKalkulacije 
                }

                //console.log(str)
                $.ajax({
                    url: "./ajax/kalkulacije_main.php",
                    method: "POST",
                    data: str
                    }).success(function(response) {
                        if(response=='ok'){
                            //console.log(response)
                            $("#kalkulacija-detail").html("")
                            ucitajKalkulacije()
                            $('[data-toggle="tooltip"]').tooltip()                                       
                        }      
                    });
                }
            }
        });
    })


//***MODAL KAM SACUVAJ  -------------------------------------------------------------------

    $('body').on('click','#sacuvaj',function(e){

        e.preventDefault();
        var str = $('#modalForm').serialize();
    
        $.ajax({
            url: "./ajax/kalkulacije_main.php",
            method: "POST",
            data: str
            }).success(function(response) {
                var json = JSON.parse(response)
                var akcija = json['akcija'];

                // AKO JE IZMENA ---------------------------

                if(akcija == "izmena"){
                    if(json['prouka'] == 'jok'){
                        alert("Nije uspelo!")
                    } else {
                        var akcija = json['akcija'];
            
                        ucitajKalkulacije()
                        console.log('izmenio')

                        $('#kalkulacijeModal').modal('hide');
                        $('[data-toggle="tooltip"]').tooltip()
                    } 
                }

                // AKO JE NOVO --------------------------------

                if(akcija == "novo"){
                    if(json['prouka'] == 'jok'){
                        alert("Nije uspela izmena!")
                    } else {
                        var akcija = json['akcija'];
                        
                        ucitajKalkulacije()
                        console.log('dodao novo')

                        $('#kalkulacijeModal').modal('hide');
                        $('[data-toggle="tooltip"]').tooltip()
                    } 
                }

            });
        });

//*** DODAVANJE STAVKE --------------------------------------------------------------

     $('body').on('click','#snimi',function(e){

        var str = $('#modalForm').serialize();
        
        $.ajax({
            url: "./ajax/kalkulacije_detail.php",
            method: "POST",
            data: str
            }).success(function(response) {

                console.log(response)

                if(response == 'ok'){
                     var idKam = $.cookie('id-main')
                     var str = {akcija:'pretraga', id : idKam}

                     $.ajax({
                        url: "./ajax/kalkulacije_detail.php",
                        method: "POST",
                        data: str
                        }).success(function(response) {
             
                        var json = JSON.parse(response)
                        if(json['poruka'] == "jok"){

                            $('#kalkulacija-detail').html("");
                            $('[data-toggle="tooltip"]').tooltip()

                        }
                        else{
                  
                            ucitajKalkulacije(idKam)
                            $('#kalkulacija-detail').html(json['html']);
                            $('[data-toggle="tooltip"]').tooltip()
                            $('#kalkulacijeModal').modal('hide');
                    
                        }
                    });
                }
            })
        })

//***IZMENA SAVKE --------------------------------------------------------------------------------------------------

    $('body').on('click','.izmenaDetalja', function(e){

           
             var main = $(this).parent().parent().parent()

             var idStavke =         $(this).attr("data-id");
             var sifra =            main.find('.kad-sifra').html()
             var kolicina =         main.find('.kad-kolicina').html()
             var nabavna =          main.find('.kad-nabavna').html().replace(',','')
             var rabat =            main.find('.kad-rabat').html()
             var cenaPopust =       main.find('.kad-cenapopust').html().replace(',','')
             var marza =            main.find('.kad-marza').html()
             var cenaVp =           main.find('.kad-cenavp').html().replace(',','')
             var cenaPdv =          main.find('.kad-cenapdv').html().replace(',','')
             var nabavnaVrednost =  main.find('.kad-vpiznos').html().replace(',','')
             var iznos =            main.find('.kad-iznos').html().replace(',','')
             var idArtikal =        $('.kad-artikal').attr('artikal-id')


             var str = {
                'akcija':'izmena', 
                'id': idStavke, 
                'sifra':sifra, 
                'kolicina':kolicina, 
                'nabavna':nabavna, 
                'rabat':rabat,
                'cenaPopust':cenaPopust,
                'marza': marza,
                'cenaVp':cenaVp,
                'cenaPdv':cenaPdv,
                'nabavnaVrednost': nabavnaVrednost,
                'iznos': iznos,
                'artikal-id' : idArtikal
            }

            $('#kalkulacijeModal').load('./modals/stavke_modal.php',str,function(){
                $('#kalkulacijeModal').modal('show')

                $('#modal-grupe').select2({
                    theme : 'bootstrap'
                })
                $('#modal-artikli').select2({
                    theme : 'bootstrap'
                })
               })
    })
 
//***BRISANJE STAVKE---------------------------------------------------------------------------------------------------------
    $('body').on('click','.brisanjeDetalja', function(e){

         var idStavke = $(this).attr("data-id");

          bootbox.confirm({
            message: "Da li ste sigurni da zelite da izbrisete stavku?",
            buttons: {
                confirm: {
                    label: 'Da',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Ne',
                    className: 'btn-danger'
                }
            },

            callback: function (result) {
                
            if(result){

                var str = {akcija: 'brisanje',id: idStavke }


                $.ajax({
                    url: "./ajax/kalkulacije_detail.php",
                    method: "POST",
                    data: str
                    }).success(function(response) {

                        if(response == 'ok'){
                            var idKam = $.cookie('id-main')
                            var str = {akcija:'pretraga', id : idKam}

                            $.ajax({
                                url: "./ajax/kalkulacije_detail.php",
                                method: "POST",
                                data: str
                                }).success(function(response) {
             
                                    var json = JSON.parse(response)
                                    if(json['poruka'] == "jok"){

                                        $('#kalkulacija-detail').html("");
                                        $('[data-toggle="tooltip"]').tooltip()

                                    }
                                    else{
                                        
                                        ucitajKalkulacije(idKam)
                                        $('#kalkulacija-detail').html(json['html']);
                                        $('[data-toggle="tooltip"]').tooltip()
                                        $('#kalkulacijeModal').modal('hide');

                                    }
                                })
                            }
                        })
                    }
                }
            })
        })

//***POSTAVLJANJE PARAMETRA ZA PRETRAGU

    $('#magacin-pretraga').on('change keyup', function(){
        var idMag = $(this).val()
        $.cookie('magacin-pretraga-id', idMag)

    })

    $('#dobavljac-pretraga').on('change keyup', function(){
        var idDob = $(this).val()
        $.cookie('dobavljac-pretraga-id', idDob)

    })

    $('#napomena').on('change keyup', function(){
        var nap = $(this).val()
        $.cookie('napomena-pretraga', nap)

    })

    $('#artikli-pretraga').on('change keyup', function(){
        var art = $(this).val()
        if(art == 'null' || art==null){
            $.cookie('artikal-pretraga-id', '-1')
        }
        else{
            $.cookie('artikal-pretraga-id', art)
        }

    })

//PROVERA SIFRE ARTIKLA U MODALU-----------------------------------------------------



    // $("#modal-sifra").focusout(function(){
    //     var sifraArtikla = $(this).val()
    //     str = {sifra : sifraArtikla}
    //     $.ajax({
    //         url: "./ajax/provera_sifre_artikla.php",
    //         method: "POST",
    //         data: str
    //         }).success(function(response) {

    //             console.log(response);

    //         })
          

    // });




        });
    </script>
  </body>
</html>