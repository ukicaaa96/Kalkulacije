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
                    <form method="POST" id = 'formaPretraga'>
                        <input type="hidden" name="akcija" value="pretraga">
                        <div class="form-group d-flex ">
                            <div class = "col-sm-6 p-0">
                                <label for="usr">Naziv okruga:</label>
                                <input id='parametar' name = 'okrug' type="text" class="form-control">
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
                                <th class="text-center">#</th>
                                <th class="">Okrug</th>
                                <th class="">Oznaka</th>
                                <th class="">Drzava</th>
                                <th class="text-center">Akcija</th>
           
                             
                            </tr>
                        </thead>
                        <div class ='div-tbody'>

                        </div>
                        <tfoot>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="">Okrug</th>
                                <th class="">Oznaka</th>
                                <th class="">Drzava</th>
                                <th class="text-center">Akcija</th>
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
    <script type="text/javascript" src="js.cookie.min.js"></script>
    <script type="text/javascript" src="jquery.cookie.min.js"></script>

    <script>
    
  
$( document ).ready(function() {

//----------DATA TABLE----------------------------------------------------------------------------------

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
            {data:'counter', className : 'text-center'},
            {data:'okrug'},
            {data:'oznaka'},
            {data:'drzava'},
            {data:'akcijaClick'},
            ],
            initComplete: function( settings, json ) {
                $('[data-toggle="tooltip"]').tooltip()
            }
        });

//---------BRISANJE OKRUGA----------------------------------------------------------------------------------------

        $('body').on('click', '.brisanjeOkruga', function(e) {

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

                callback: function(result) {

                    var str = {
                        'akcija': 'brisanje',
                        'idOkruga': idOkruga
                    }
                    $.ajax({
                        url: "./ajax/test.php",
                        method: "POST",
                        data: str
                    }).success(function(response) {

                            if (response == 'ok') {
                                var rowCount = $('tbody tr').length;
                                if(rowCount>1){
                                    $('#example').DataTable().ajax.reload();
                                }else{
                                    $('tbody').remove()
                                    $('.dataTables_info').hide()
                                    $('.dataTables_paginate').hide()
                                    $('.paging_simple_numbers').hide()
                                    $('#example_wrapper').find('.dataTables_length').hide()
                                }

                                bootbox.alert("Uspešno ste izbrisali okrug!")
                            }
                        })
                    }
                })
            })
            

// --------------IZMENA OKRUGA----------------------------------------------------------------------------


             $('body').on('click','.izmenaOkruga', function(e){
                var idOkruga = $(this).attr("data-id");
                var idDrzave = $(this).attr("data-id-drzava");
                $('#okruziModal').load('./modals/okruzi_modal.php',{'akcija':'izmena' , 'idOkruga' : idOkruga, 'idDrzave':idDrzave},function(){
                    $('#okruziModal').modal('show')
                    $('#drzavaSelect').select2();
                })
            })

//------------------DODAJ NOVO / IZMENI---------------------------------------------------------------------------
                
          
              $('body').on('click','#sacuvaj',function(e){

                e.preventDefault();
                var str = $('#modalForm').serialize();
                //console.log(str)

                $.ajax({
                    url: "./ajax/test.php",
                    method: "POST",
                    data: str
                    }).success(function(response) {
                        //console.log(response)
                        if(response == 'jok'){
                            console.log("Nije uspelo!")
                         } else {
                            //console.log("ok");

                            var json = JSON.parse(response);
                            var akcija = json['akcija']
//-------AKO JE NOVO-------------------------------------------------------------------------------------
                            if(json['akcija'] == 'novo'){

                                var idOkruga = json['idOkrug']
                                var nazivOkruga = json['nazivOkrug']
                                var oznaka = json['oznaka']
                                var drzava = json['drzava']
                                var drzavaId = json['drzavaId']

                                var html=
                                
                                `<tbody>
                                    <tr class='odd'>
                                        <td class="text-center"><span class="text-center counter">1</span></td>
                                        <td><span class="podatakOkrug">${nazivOkruga}</span></td>
                                        <td><span class="podatakOznaka">${oznaka}</span></td>
                                        <td><span class="podatakOkrug">${drzava}</span></td>
                                        <td>

                                        <div class ="d-flex">
                                            <div class="custom col-sm-6 p-1">
                                                <div class="izmenaOkruga float-right" data-id-drzava ="${drzavaId}" data-id="${idOkruga}" data-toggle="tooltip" data-placement="top" title="izmeni mesto">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                                        ></path>
                                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                                                    </svg>
                                                </div>
                                            </div>

                                            <div class="custom col-sm-6 p-1">
                                                <div class="brisanjeOkruga" data-id="${idOkruga}" data-toggle="tooltip" data-placement="top" title="izbriši mesto">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"
                                                            />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>`

                            
                                $( "tbody" ).replaceWith(html);
                                $('.dataTables_info').hide()
                                $('.dataTables_paginate').hide()
                                $('.paging_simple_numbers').hide()
                                $('#example_wrapper').find('.dataTables_length').hide()
                                $('[data-toggle="tooltip"]').tooltip()

                                bootbox.alert("Uspešno ste dodali okrug!")
                            }
//-----AKO JE IZMENA---------------------------------------------------------------------------------------                         
                         if(akcija=='izmena'){
                                var rowCount = $('tbody tr').length;

                                if(rowCount>1){
                                    $('#example').DataTable().ajax.reload(null, false);
                                    bootbox.alert("Uspešno ste izbrisali okrug!")
                                } else{
                                    console.log(json)
                                    var idOkruga = json['idOkrug']
                                    var nazivOkruga = json['nazivOkrug']
                                    var oznaka = json['oznaka']
                                    var drzava = json['drzava']
                                    var drzavaId = json['drzavaId']

                                    var html=
                                    
                                    `<tbody>
                                        <tr class='odd'>
                                            <td><span class="text-center counter">1</span></td>
                                            <td><span class="podatakOkrug">${nazivOkruga}</span></td>
                                            <td><span class="podatakOznaka">${oznaka}</span></td>
                                            <td><span class="podatakOkrug">${drzava}</span></td>
                                            <td>

                                            <div class ="d-flex">
                                                <div class="custom col-sm-6 p-1">
                                                    <div class="izmenaOkruga float-right" data-id-drzava ="${drzavaId}" data-id="${idOkruga}" data-toggle="tooltip" data-placement="top" title="izmeni mesto">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                            <path
                                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                                                            ></path>
                                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                                                        </svg>
                                                    </div>
                                                </div>

                                                <div class="custom col-sm-6 p-1">
                                                    <div class="brisanjeOkruga" data-id="${idOkruga}" data-toggle="tooltip" data-placement="top" title="izbriši mesto">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                                            <path
                                                                d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"
                                                                />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>`

                                    $( "tbody" ).replaceWith(html);
                                    bootbox.alert("Uspešno ste izmenili okrug!")
                                }
                            }
                        }
                    });  
                    $('#okruziModal').modal('hide');
                });

//-----BUTTON NOVO---------------------------------------------------------------------------------------

               $('#novo').on('click', function(e){
                $('#okruziModal').load('./modals/okruzi_modal.php',{'akcija':'novo'},function(){
                    $('#okruziModal').modal('show')
                    $('#drzavaSelect').select2({
                        placeholder: 'Izaberi drzavu'
                    });
                })
            })

//------PRETRAGA--------------------------------------------------------------------------------------


   $('#pretraga').on('click', function () {
      
        var parametarPretrage = $("#parametar").val();

        $.cookie('parametar', parametarPretrage)
        $('#example').DataTable().page(0).draw('page');

    });

});

    </script>
  </body>
</html>