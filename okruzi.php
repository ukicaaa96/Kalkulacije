<?php
include_once 'connection.php';

$sql = "SELECT drzave.drz_dssnaziv, mesta.mes_dssnaziv, okruzi.okr_dssnaziv, mesta.mes_dsspostanskibroj, mesta.mes_cdimesto
        FROM mesta
        INNER JOIN okruzi
        ON mesta.mes_cdiokrug = okruzi.okr_cdiokrug
        INNER JOIN drzave
        ON okruzi.okr_cdidrzava = drzave.drz_cdidrzava";


$sqlOkruzi = "SELECT okr_dssnaziv,okr_cdiokrug FROM okruzi WHERE okr_cdiokrug>0";
$sqlDrzave = "SELECT drz_dssnaziv, drz_cdidrzava FROM drzave WHERE drz_cdidrzava>0";
?>

<!doctype html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel='stylesheet' href='select2.css'>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <title>Hello, world!</title>
    <style>
        .select2-container{
            width:100% !important;
        }
    </style>
  </head>
  <body>

    <div class = "container pt-3">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        Petraga
                    </div>
                    <div class="card-body">
                    <form id = 'formaPretraga'>
                        <input type="hidden" name="akcija" value="pretraga">
                        <div class="form-group d-flex ">
                            <div class = "col-sm-6 p-0">
                                <label for="usr">Naziv okruga:</label>
                                <input name = 'mesto' type="text" class="form-control">
                            </div>
                        </div>           
                        <button id = 'pretraga' class="btn btn-primary">Pretraži</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- KRAJ PRETRAGA -->

        <div class = 'row'>
            <div class="col-sm-12 pt-3">
            <div class="card">
                    <div class="card-header">                   
                        <div class='float-left'>
                            <h4>Okruzi:</h4>
                        </div>              
                        <div class='float-right'>       
                            <button id='novo' class = 'btn btn-primary float-right'>Novo+</button>
                        </div>                      
                    </div>
                    <div class="card-body">
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="">Okrug</th>
                                <th class="">Oznaka</th>
                                <th class="">Drzava</th>
                                <th class="text-center">Akcija</th>
           
                             
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Okrug</th>
                                <th>Oznaka</th>
                                <th>Drzava</th>
                                <th>Akcija</th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id='okruziModal' class="modal fade" tabindex="-1" role="dialog">

    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="select2.min.js"></script>
    <script src="bootbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>

    <script>
    
  
$( document ).ready(function() {

        $('#example').DataTable({
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
              url: 'ajax/test.php',
              dataSrc: 'data',
              type: 'POST'
            },
              fnDrawCallback: function( oSettings ) {
                   $('[data-toggle="tooltip"]').tooltip()
                },

                    columns: [
            {data:'counter'},
            {data:'okrug'},
            {data:'oznaka'},
            {data:'drzava'},
            {data:'akcijaClick'},
            ],
            initComplete: function( settings, json ) {
                $('[data-toggle="tooltip"]').tooltip()
            }
        });



         
            $('body').on('click','.brisanjeOkruga', function(e){

                var idOkruga = $(this).attr("data-id");

                bootbox.confirm({
                    message: "Da li ste sigurni da zelite da izbrisete okrug?",
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
                 
                    console.log("brisem okrug sa id-jem :" + idOkruga)
      
                    }
                });
            });

            // ------------------------------------------------------------------------------------------


             $('body').on('click','.izmenaOkruga', function(e){
                //var redniBroj = $(this).parent().parent()
                var idOkruga = $(this).attr("data-id");
                var idDrzave = $(this).attr("data-id-drzava");
                //var cnt = $(this).parent().parent().find('.counter').html()
                $('#okruziModal').load('./modals/okruzi_modal.php',{'akcija':'izmena' , 'idOkruga' : idOkruga, 'idDrzave':idDrzave},function(){
                    $('#okruziModal').modal('show')
                    $('#drzavaSelect').select2();
                })
            })

             //---------------------------------------------------------------------------------------------
                
          
              $('body').on('click','#sacuvaj',function(e){

                e.preventDefault();
                var str = $('#modalForm').serialize();
                console.log(str)


                $.ajax({
                    url: "./ajax/test.php",
                    method: "POST",
                    data: str
                    }).success(function(response) {
                        console.log(response)
                        if(response == 'jok'){
                            console.log("Nije uspelo!")
                         } else {
                            console.log("ok");
                            $('#example').DataTable().ajax.reload();
                         }
                    });  

                    $('#okruziModal').modal('hide');

                });

              //--------------------------------------------------------------------------------------------
               $('#novo').on('click', function(e){
                $('#okruziModal').load('./modals/okruzi_modal.php',{'akcija':'novo'},function(){
                    $('#okruziModal').modal('show')
                    $('#drzavaSelect').select2({
                        placeholder: 'Izaberi drzavu'
                    });
                })
            })

});

    </script>
  </body>
</html>