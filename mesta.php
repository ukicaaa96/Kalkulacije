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
                    <form id = 'formaPretraga'>
                        <input type="hidden" name="akcija" value="pretraga">
                        <div class="form-group d-flex ">
                            <div class = "col-sm-3">
                                <label for="usr">Naziv mesta:</label>
                                <input name = 'mesto' type="text" class="form-control">
                            </div>

                            <div class = "col-sm-3">
                                <label for="usr">Poštanski broj:</label>
                                <input name = 'postanskiBroj' type="text" class="form-control">
                            </div>

                            <div class = "col-sm-3">
                            <label for="usr">Okrug:</label>
                                <div class='d-block'>
                                    
                                    <select class='okrugSelect' name='okrug' class="mb-3">
                                        <option value='-1' selected>Izaberi okrug:</option>
                                        <?php
                                            $data = $conn->query($sqlOkruzi);
                                            while ($row = $data->fetch_assoc()) {
                                        ?>

                                        <option value=<?=$row['okr_cdiokrug']?>><?=$row['okr_dssnaziv']?></option>
                                    <?php
                                            }
                                    ?>
                                    </select>
                                </div>
                               
                            </div>

                            <div class = "col-sm-3">
                            <label for="usr">Država:</label>
                                <div class='d-block'>
                                    
                                <select class='drzavaSelect' name ='drzava' class="mb-3">
                                        <option value='-1' selected>Izaberi državu:</option>
                                        <?php
                                            $data = $conn->query($sqlDrzave);
                                            while ($row = $data->fetch_assoc()) {
                                        ?>

                                        <option value=<?=$row['drz_cdidrzava']?>><?=$row['drz_dssnaziv']?></option>
                                    <?php
                                            }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button id = 'pretraga' class="btn btn-primary">Pretraži</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <div class = 'row'>
            <div class="col-sm-12 pt-3">
            <div class="card">
                    <div class="card-header">                   
                        <div class='float-left'>
                            <h4>Mesta:</h4>
                        </div>              
                        <div class='float-right'>       
                            <button id='novo' class = 'btn btn-primary float-right'>Novo+</button>
                        </div>                      
                    </div>
                    <div class="card-body">
                    <table id='mytable' class="">
                        <thead>
                            <tr>
                                <th class ="text-center" scope="col">#</th>
                                <th scope="col">Naziv</th>
                                <th scope="col">Poštanski broj</th>
                                <th scope="col">Okrug</th>
                                <th scope="col">Država</th>
                                <th class ='text-center' scope="col">Akcija</th>
                            </tr>
                        </thead>

                        <tbody id="osvezi">

                        <?php
                            $brojac = 1;
                            $data = $conn->query($sql);
                            while ($row = $data->fetch_assoc()) {
                            
                        ?>
                            <tr class='red' id=<?=$brojac?>>
                                <td class='text-center counter'><?=$brojac?></th>
                                <td class='podatakMesto'><?=$row['mes_dssnaziv']?></td>
                                <td class='podatakBroj'><?=$row['mes_dsspostanskibroj']?></td>
                                <td class='podatakOkrug'><?=$row['okr_dssnaziv']?></td>
                                <td class='podatakDrzava'><?=$row['drz_dssnaziv']?></td>

                                <td class ='d-flex'>
                                    <div class="custom col-sm-6 p-1">
                                        <div class='izmenaMesta float-right' data-id='<?=$row['mes_cdimesto']?>'  data-toggle="tooltip" data-placement="top" title="izmeni mesto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                                            </svg>
                                        </div>
                                    </div>



                                    <div class="custom col-sm-6 p-1">
                                        <div class='brisanjeMesta' data-id='<?=$row['mes_cdimesto']?>' data-toggle="tooltip" data-placement="top" title="izbriši mesto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                              <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $brojac += 1;
                            }
                            ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id='mestaModal' class="modal fade" tabindex="-1" role="dialog">

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

            $('#mytable').DataTable({
                language: {
            "lengthMenu": "Prikaz po stranici _MENU_ ",
            "emptyTable": "Nema rezultata",
            "info": "Strana _PAGE_ od _PAGES_",
            "paginate": {
            "next": "Sledeća",
            "previous": "Prethodna"
            }
            },
            lengthMenu: [ 3, 6, 9, 12, 15, 18 ],
            searching: false,
            ordering:false,
            columnDefs:[{
                className: 'dt-body-center'
            }],
        });

            $('[data-toggle="tooltip"]').tooltip()

            $('select').select2({
            placeholder: 'Select an option'
           });
           

            $('body').on('click','.brisanjeMesta', function(e){

                var idMesta = $(this).attr("data-id");

                bootbox.confirm({
                    message: "Da li ste sigurni da zelite da izbrisete mesto?",
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
                        id: idMesta 
                    }

                    console.log(str)
                    $.ajax({
                        url: "./ajax/ajax_mesta.php",
                        method: "POST",
                        data: str
                        }).success(function(response) {
                            if(response=='ok'){
                                console.log(response)
                                $(".brisanjeMesta[data-id="+idMesta+"]").parent().parent().parent().remove();
                               
                                var brojac = 0
                                $( ".counter" ).each(function() {
                                    $( this ).text(brojac+1);
                                    brojac+=1
                                });                            
                            }
                            $('[data-toggle="tooltip"]').tooltip()  
                               
                        });
                    }
                }
            });

               
           })
           $('body').on('click','#sacuvaj',function(e){

                e.preventDefault();
                var str = $('#modalForm').serialize();
                console.log(str)


                $.ajax({
                    url: "./ajax/ajax_mesta.php",
                    method: "POST",
                    data: str
                    }).success(function(response) {
                        console.log(response)
                        if(response == 'jok'){
                            alert("Nije uspelo!")
                         } else {

                            
                            var json = JSON.parse(response);
                            var akcija = json['akcija']
                            var redniBrojStr = $('#mytable > tbody > tr').last().find('th').text();

                            if(!redniBrojStr){
                                redniBrojStr = 0;
                            }
                            var redniBroj = parseInt(redniBrojStr)+1;
                            var redniBrojStr1 = redniBroj.toString()
                            var mesto = json['mesto'];
                            var postanskiBroj = json['postanskiBroj'];
                            var okrug = json['okrug'];
                            var drzava = json['drzava'];
                            var id = json['id'];

                            if(akcija == 'novo'){
                                $("#osvezi").append(
                                    "<tr class='red' id="+redniBrojStr1+">"+
                                        "<th class='counter'>"+redniBroj+"</th>"+
                                        "<td>"+mesto+"</td>"+
                                        "<td>"+postanskiBroj+"</td>"+
                                        "<td>"+okrug+"</td>"+
                                        "<td>"+drzava+"</td>"+
                                   

                                    `<td class ='d-flex'>
                                        <div class="custom col-sm-6 p-1">
                                            <div class='izmenaMesta float-right' data-id='${id}'  data-toggle="tooltip" data-placement="top" title="izmeni mesto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="custom col-sm-6 p-1">
                                            <div class='brisanjeMesta' data-id='${id}' data-toggle="tooltip" data-placement="top" title="izbriši mesto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                                  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                                               </svg>
                                            </div>
                                        </div>

                                    </td>

                                    </tr>`
                                
                                );

                            } else{


                                                            
                                var idElementa = $("div").find("[data-id='" + id + "']").parent().parent().parent().attr('id')
                                //var okrugValue = $( "#myselect option:selected" ).text();
                                var okrugHtml = $( ".okrugSelect option[value="+okrug+"]").html()
                                var drzavaHtml = $( ".drzavaSelect option[value="+drzava+"]").html()

                                console.log("REDNI BROJ : "+ idElementa)


                                $('#'+idElementa).html(
                                `
                                        <th class='counter'>
                                        <td>${mesto}</td>
                                        <td>${postanskiBroj}</td>
                                        <td>${okrugHtml}</td>
                                        <td>${drzavaHtml}</td>
                                   

                                        <td class ='d-flex'>
                                        <div class="custom col-sm-6 p-1">
                                            <div class='izmenaMesta float-right' data-id='${id}'  data-toggle="tooltip" data-placement="top" title="izmeni mesto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                                  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="custom col-sm-6 p-1">
                                            <div class='brisanjeMesta' data-id='${id}' data-toggle="tooltip" data-placement="top" title="izbriši mesto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                                  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </td>

                                    `)

                                var brojac = 0
                            $( ".counter" ).each(function() {
                                $( this ).text(brojac+1);
                                brojac+=1
                            });
                            }

                            $('#mestaModal').modal('hide');
                            $('[data-toggle="tooltip"]').tooltip()
                             

                            if(akcija=='novo'){
                                $('html, body').animate({
                                        scrollTop: $("#"+redniBrojStr1).offset().top
                                    }, 2000);

                                $('#mytable > tbody > tr').last().addClass('bg-success');

                                setTimeout(function() {
                                     $('#mytable > tbody > tr').last().removeClass('bg-success');
                                }, 3000);
                                 
                                bootbox.alert("Uspešno ste dodali mesto!")
                            }

                            else{

                                $('#'+idElementa).addClass('bg-success');

                                setTimeout(function() {
                                      $('#'+idElementa).removeClass('bg-success');
                                }, 3000);
                                 bootbox.alert("Uspešno ste izmenili mesto!")
                                }

                             }
                         });
                    $('[data-toggle="tooltip"]').tooltip()

            })
           

            $('#pretraga').on("click", function(e){
                e.preventDefault();
                var str = $('#formaPretraga').serialize();
                console.log(str)

                $.ajax({
                    url: "./ajax/ajax_mesta.php",
                    method: "POST",
                    data: str
                    }).success(function(response) {
                        console.log(response)

                        $('#osvezi').html(response)
                    });

            })

            $('#novo').on('click', function(e){
                $('#mestaModal').load('./modals/mesta_modal.php',{'akcija':'novo'},function(){
                    $('#mestaModal').modal('show')
                    $('#okrugSelect').select2({
                        placeholder: 'Izaberi okrug'
                    });
                })
            })


             $('body').on('click','.izmenaMesta', function(e){
                //var redniBroj = $(this).parent().parent()
                var idMesta = $(this).attr("data-id");
                //var cnt = $(this).parent().parent().find('.counter').html()
                $('#mestaModal').load('./modals/mesta_modal.php',{'akcija':'izmena' , 'id' : idMesta},function(){
                    $('#mestaModal').modal('show')
                    $('#okrugSelect').select2();
                })
            })
        
            $('body').on('change keypres paste' ,"#modal-mesto, #modal-postanskibroj, #okrugSelect", function(e){
                var okrug = $('#okrugSelect').val();
                var naziv = $('#modal-mesto').val();
                var postanskiBroj = $('#modal-postanskibroj').val();

                if(okrug != '-1' && naziv.length >0 && postanskiBroj.length >0 ){
                    
                    $('#sacuvaj').removeAttr('hidden');
                    console.log('if - '+okrug+'||'+naziv+'||'+postanskiBroj)
                }
                else{
                    $('#sacuvaj').attr('hidden', true);
                    console.log('else - '+okrug+'||'+naziv+'||'+postanskiBroj)
                } 
            })


        });

    </script>
  </body>
</html>