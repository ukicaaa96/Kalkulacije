<?php
include_once '../connection.php';


if(isset($_POST['akcija'])){

    $akcija = $_POST['akcija'];

//////////////////////////////////////////////////////////////////////////////////////////

    if($akcija == 'izmena'){

       $idDrzave =      $_POST['drzava'];
       $nazivOkruga =   $_POST['okrug'];
       $oznakaOkruga =  $_POST['oznaka'];
       $idOkrug =       $_POST['id-okrug'];

        $vratiDrzavu = "SELECT drz_dssnaziv FROM drzave WHERE drz_cdidrzava=".$idDrzave;
        $podaciDrzava = $conn->query($vratiDrzavu);
        $drzavaString = $podaciDrzava->fetch_assoc()['drz_dssnaziv'];

       $sql = "UPDATE okruzi SET okr_dssnaziv = '".$nazivOkruga."',okr_dssoznaka = '".$oznakaOkruga."',okr_cdidrzava=".$idDrzave." WHERE okr_cdiokrug = ".$idOkrug;

       $conn->query($sql);

       if($conn->affected_rows){
            $arr = [
                'akcija' =>         'izmena', 
                'idOkrug'=>         $idOkrug,
                'nazivOkrug' =>     $nazivOkruga,
                'oznaka'=>          $oznakaOkruga,
                'drzava'=>          $drzavaString,
                'drzavaId'=>        $idDrzave
            ];
            
            echo json_encode($arr);
        }
       else{
        echo 'jok';
       }

    }
//////////////////////////////////////////////////////////////////////////////////////////
    if($akcija == 'novo'){

        $idOkrug = $_POST['id-okrug'];
        $nazivOkruga = $_POST['okrug'];
        $oznaka = $_POST['oznaka'];
        $drzava = $_POST['drzava'];

        $sql = "INSERT INTO okruzi (okr_cdiokrug, okr_dssnaziv, okr_dssoznaka, okr_cdidrzava) 
                VALUES ('".$idOkrug."','".$nazivOkruga."','".$oznaka."','".$drzava."')";

        $conn->query($sql);

        if($conn->affected_rows){
            $vratiDrzavu = "SELECT drz_dssnaziv FROM drzave WHERE drz_cdidrzava=".$drzava;
            $podaciDrzava = $conn->query($vratiDrzavu);
            $drzavaString = $podaciDrzava->fetch_assoc()['drz_dssnaziv'];
            $arr = [
                'akcija' =>         'novo', 
                'idOkrug'=>         $idOkrug,
                'nazivOkrug' =>     $nazivOkruga,
                'oznaka'=>          $oznaka,
                'drzava'=>          $drzavaString,
                'drzavaId'=>        $drzava
            ];

            echo json_encode($arr);
        }
        else{
            echo 'jok';
        }
    }

//////////////////////////////////////////////////////////////////////////////////////////

    if($akcija == 'brisanje'){

        $idOkrug = $_POST['idOkruga'];

        $sql = "DELETE FROM okruzi WHERE okr_cdiokrug = ".$idOkrug;

        $conn->query($sql);

        if($conn->affected_rows > 0){
            echo 'ok';
        } else{
            echo 'jok';
        }
    }







}

// ======================================================== DATA TABLES ============================================================
else{

    if(isset($_POST["parametar"])){
        $parametar = $_POST["parametar"];
        $sql = "SELECT * 
                FROM okruzi
                INNER JOIN drzave
                ON drz_cdidrzava = okr_cdidrzava 
                WHERE okr_dssnaziv LIKE "%".$parametar."%"";
    }
    else{
        $sql = "SELECT * 
                FROM okruzi
                INNER JOIN drzave
                ON drz_cdidrzava = okr_cdidrzava";
        }

    $akcijaClick = '';


    $start =    (int)$_POST['start'];
    $len =      (int)$_POST['length'];
    $draw =     $_POST['draw']; 
    $end =      $start+$len;

    if($draw == ""){
        $draw = 1;
    } 

    $podaci =   [];   
    $brojac =   1;
    $i =        1;
    $data =     $conn->query($sql);

    while ($row = $data->fetch_assoc()) {
        if($start<$i && $end>=$i)
        {

            $idOkrug =      $row["okr_cdiokrug"];
            $redniBroj =    "<span class='counter'>".$i."</span>";
            $okrug =        "<span class='podatakOkrug'>".$row["okr_dssnaziv"]."</span>";
            $oznaka =       "<span class='podatakOznaka'>".$row['okr_dssoznaka']."</span>";
            $drzava=        "<span class='podatakDrzava'>".$row["drz_dssnaziv"]."</span>" ;                   
            $akcijaClick .= 
            '
            <div class ="d-flex">
            <div class="custom col-sm-6 p-1">
                <div class="izmenaOkruga float-right" data-id-drzava ="'.$row['drz_cdidrzava'].'" data-id="'.$idOkrug.'" data-toggle="tooltip" data-placement="top" title="izmeni mesto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
                        ></path>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                    </svg>
                </div>
            </div>

            <div class="custom col-sm-6 p-1">
                <div class="brisanjeOkruga" data-id="'.$idOkrug.'" data-toggle="tooltip" data-placement="top" title="izbriši mesto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                        <path
                            d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"
                        />
                    </svg>
                </div>
            </div>
            </div>

            ';

                $myArr = [
                'counter'=>         $redniBroj,
                'okrug' =>          $okrug,
                'oznaka' =>         $oznaka,
                'drzava' =>         $drzava,
                'akcijaClick' =>    $akcijaClick
            ];  

            $akcijaClick = '';


            array_push($podaci, $myArr);          
            $brojac += 1;
        }
        $i+= 1;
    }



    $mesta_niz["draw"]=             $draw;
    $mesta_niz["recordsTotal"] =    $i-1;
    $mesta_niz["recordsFiltered"] = $i-1;

    echo json_encode([
        'data'=>                $podaci,
        'draw'=>                $draw,
        'recordsTotal'=>        $i-1,
        'recordsFiltered' =>    $i-1
    ]);
}
?>
