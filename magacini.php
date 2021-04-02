<?php
include_once 'connection.php';


$sqlMagacini = "SELECT * FROM magacini WHERE mag_cdimagacin > 0";
$sqlLokacije = "SELECT * FROM lokacije WHERE lok_cdilokacija > 0";

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
                <a class="nav-item nav-link" href="http://localhost/ub_test/kalkulacije.php">KALKULACIJE</a>
                
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
                                <label for="usr">Naziv magacina:</label>
                                <input name = 'magacin' type="text" class="form-control">
                            </div>

                            <div class = "col-sm-3">
                            <label for="usr">Lokacija:</label>
                                <div class='d-block'>
                                    
                                    <select class='okrugSelect' name='okrug' class="mb-3">
                                        <option value='-1' selected>Izaberi okrug:</option>
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
                            <h4>Magacini:</h4>
                        </div>              
                        <div class='float-right'>       
                            <button id='novo' class = 'btn btn-primary float-right'>Novo+</button>
                        </div>                      
                    </div>
                    <div class="card-body">
                    <table id='mytable' class="">
                        <thead>
                            <tr>
                                <th class ="text-center" style="width: 22px" scope="col">#</th>
                                <th scope="col">Naziv</th>
                                <th scope="col">Lok</th>

                                <th class ='text-center' scope="col">Akcija</th>
                            </tr>
                        </thead>

                        <tbody id="osvezi">

                        <?php
                            $brojac = 1;
                            $data = $conn->query($sqlMagacini);
                            while ($row = $data->fetch_assoc()) {

                            
                        ?>
                            <tr class='col-sm-12 red' id=<?=$brojac?>>
                                <td class='text-center counter'><?=$brojac?></th>
                                <td class='podatakMesto'><?=$row['mag_dssnaziv']?></td>
                                <td class='podatakMesto'><?=$row['mag_dssnaziv']?></td>
                                <td class ='d-flex'>
                                    <div class="custom col-sm-6 p-1">
                                        <div class='izmenaMagacina float-right' data-id='<?=$row['mag_cdimagacin']?>'  data-toggle="tooltip" data-placement="top" title="izmeni magacin">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                                            </svg>
                                        </div>
                                    </div>



                                    <div class="custom col-sm-6 p-1">
                                        <div class='brisanjeMagacina' data-id='<?=$row['mag_cdimagacin']?>' data-toggle="tooltip" data-placement="top" title="izbriši magacin">
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

          
        });

    </script>
  </body>
</html>