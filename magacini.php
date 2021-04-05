<?php
include_once 'connection.php';


$sqlMagacini = 
"SELECT * FROM magacini as m
INNER JOIN lokacije as l 
ON  l.lok_cdilokacija = m.mag_cdilokacija
WHERE mag_cdimagacin > 0";

$sqlLokacije = "SELECT * FROM lokacije WHERE lok_cdilokacija > 0";

?>

<!doctype html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
                <a class="nav-item nav-link" href="http://localhost/ub_test/kalkulacije.php">Kalkulacije</a>
                <a class="nav-item nav-link" href="http://localhost/ub_test/magacini.php">Magacini</a>   
            </div>
        </div>
    </nav>

    <div class = "col-sm-10 container pt-3">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Petraga
                    </div>
                    <div class="card-body">
                    <!-- <form id = 'formaPretraga'> -->
                        <!-- <input type="hidden" name="akcija" value="pretraga"> -->
                        <div class="form-group d-flex ">
                            <div class = "col-sm-3">
                                <label for="usr">Naziv magacina:</label>
                                <input id = 'magacin-pretraga' name = 'magacin' type="text" class="form-control">
                            </div>

                            <div class = "col-sm-3">
                            <label for="usr">Lokacija:</label>
                                <div class='d-block'>
                                    
                                    <select class = 'form-control' id = 'lokacija-pretraga'  name='lokacija'>
                                        <option value='-1' selected>Izaberi lokaciju:</option>
                                        <?php
                                            $data = $conn->query($sqlLokacije);
                                            while ($row = $data->fetch_assoc()) {
                                        ?>

                                        <option value=<?=$row['lok_cdilokacija']?>><?=$row['lok_dssnaziv']?></option>
                                    <?php
                                            }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button id = 'pretraga' class="btn btn-primary">Pretraži</button>
                    <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>

        <div class = 'row'>
            <div class="col-sm-12 pt-3">
            <div class="card">
                    <div class="card-header">                   
                        <div class='float-left'>
                            <h4>Magacini:</h4>
                        </div>              
                        <div class='float-right'>       
                            <button id='novo' class = 'btn btn-primary float-right'>Novo+</button>
                        </div>                      
                    </div>
                    <div class="card-body">

                         <table id="tabela" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Magacin</th>
                                <th class="">Lokacija</th>
                                <th class="text-center">Akcija</th>
           
                             
                            </tr>
                        </thead>
                        
<!-- 
                        <tfoot>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Magacin</th>
                                <th class="">Lokacija</th>
                                <th class="text-center">Akcija</th>
                            </tr>
                        </tfoot> -->
                    </table>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id='magaciniModal' class="modal fade" tabindex="-1" role="dialog">

    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!--     <script src="select2.min.js"></script> -->
    <script src="bootbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script type="text/javascript" src="js.cookie.min.js"></script>
    <script type="text/javascript" src="jquery.cookie.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous"></script>
    <script>
    
  
        $( document ).ready(function() {

            $.cookie('naziv-magacin' , '-1')
            $.cookie('id-lokacija' , '-1')
//SELECT 2 --------------------------------------------------------
            $('#lokacija-pretraga').select2({
                theme : 'bootstrap'
            })

//CHANGE MAG -----------------------------------------------------------
            $('#magacin-pretraga').on('change keyup', function(){
                var naziv = $(this).val()
                $.cookie('naziv-magacin' , naziv)
            })
//CHANGE LOK -----------------------------------------------------------
            $('#lokacija-pretraga').on('change keyup', function(){
                var lokacija = $(this).val()
                $.cookie('id-lokacija' , lokacija)
            })
// PRETRAGA---------------------------------------------------------
            $('#pretraga').on('click', function(){

                $('#tabela').DataTable().ajax.reload()

            })

//DATA TABLES------------------------------------------------------------------

             $('#tabela').DataTable({
            language: {
                "lengthMenu": "Prikaz po stranici _MENU_ ",
                "emptyTable": "Nema rezultata",
                "info": "Strana _PAGE_ od _PAGES_",
                "paginate": {
                "next": "Sledeća",
                "previous": "Prethodna"
                }
            },
            lengthMenu: [ 5, 10, 15, 20, 25 ],
            searching: false,
            ordering: false,
            serverSide: true,
            ajax: {
              url: 'ajax/magacini_novo_izmena_brisanje.php',
              dataSrc: 'data',
              type: 'POST'
            },
              fnDrawCallback: function( oSettings ) {

                   $('[data-toggle="tooltip"]').tooltip()

                },

                    columns: [
            {data:'counter', className : 'text-center'},
            {data:'magacin'},
            {data:'lokacija'},
            {data:'akcijaClick'},
            ],
            initComplete: function( settings, json ) {
                $('[data-toggle="tooltip"]').tooltip()
            }
        });

// NOVO -------------------------------------------------------------------
            $('#novo').on('click', function(){

                $('#magaciniModal').load('./modals/magacini_modal.php',{'akcija':'novo'},function(){
                    $('#magaciniModal').modal('show')
                    $('#lokacije-modal').select2({
                        theme : 'bootstrap'
                    });
                })
            })

// IZMENA -----------------------------------------------------------------------

            $('body').on('click', '.izmenaMagacina', function(){

                var idMagacina = $(this).attr('data-id-magacin');
                var idLokacija = $(this).attr('data-id-lokacija');
                var nazivMagacina = $(this).parent().parent().parent().parent().find('.podatakMagacin').html()

                var str = {'akcija' : 'izmena' , 'idMagacina' : idMagacina , 'idLokacija' : idLokacija, 'nazivMagacina' : nazivMagacina}
                console.log(str);
                $('#magaciniModal').load('./modals/magacini_modal.php',str,function(){
                    $('#magaciniModal').modal('show')
                    $('#lokacije-modal').select2({
                        theme : 'bootstrap'
                    });
                })
                 $('body>.tooltip').remove();
                 $('#tabela').DataTable().ajax.reload(null,false);

            })
// BRISANJE -----------------------------------------------------------------------------------

             $('body').on('click', '.brisanjeMagacina', function(){

              
                //var nazivMagacina = $(this).parent().parent().parent().parent().find('.podatakMagacin').html()

                var idMagacina = $(this).attr('data-id-magacin');
                var str = {'akcija' : 'brisanje' , 'idMagacina' : idMagacina}


                $.ajax({
                    url: "./ajax/magacini_novo_izmena_brisanje.php",
                    method: "POST",
                    data: str
                    }).success(function(response) {

                        var json = JSON.parse(response)

                        console.log(json);

                        if (json['poruka'] == 'ok') {
                            console.log(json['akcija'])
                             $('body>.tooltip').remove();
                            $('#tabela').DataTable().ajax.reload(null,false);
                        }
                    });

            })

// SACUVAJ - MODAL-----------------------------------------------------------------------------------------------

            $('body').on('click', '#sacuvaj', function(e){
                e.preventDefault();
                var str = $('#modalForm').serialize();
                //console.log(str)

                $.ajax({
                    url: "./ajax/magacini_novo_izmena_brisanje.php",
                    method: "POST",
                    data: str
                    }).success(function(response) {
                        var json = JSON.parse(response)

                        if (json['poruka'] == 'ok') {

                            console.log(json['akcija'])
                        
                            $('#magaciniModal').modal('hide');
                            $('#tabela').DataTable().ajax.reload(null,false);
                        }
            })

})
          
});

    </script>
  </body>
</html>