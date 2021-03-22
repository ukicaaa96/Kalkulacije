<?php
include_once '../connection.php';

if($_POST['akcija'] == 'pretraga'){

    $mesto = $_POST['mesto'];
    $postanskiBroj = $_POST['postanskiBroj'];
    $okrug = $_POST['okrug'];
    $drzava = $_POST['drzava'];

    if($mesto){
        $sqlMesto = " AND mesta.mes_dssnaziv LIKE '%$mesto%' ";
    } else {
        $sqlMesto = "";
    }

    if($postanskiBroj){
        $sqlPostanskiBroj =  " AND mesta.mes_dsspostanskibroj LIKE '%$postanskiBroj%'";
    } else{
        $sqlPostanskiBroj = "";
    }

    if($okrug == "-1"){
        $sqlOkrug = "";
    } else{
        $sqlOkrug = " AND okruzi.okr_cdiokrug = $okrug";
    }

    if($drzava == "-1"){
        $sqlDrzava = "";
    } else{
        $sqlDrzava = " AND drzave.drz_cdidrzava = $drzava";
    }



    $sqlWhere = $sqlMesto . $sqlPostanskiBroj . $sqlOkrug. $sqlDrzava;

    $sql = "SELECT drzave.drz_dssnaziv, drzave.drz_cdidrzava, okruzi.okr_cdiokrug, mesta.mes_dssnaziv, okruzi.okr_dssnaziv, mesta.mes_dsspostanskibroj
            FROM mesta
            INNER JOIN okruzi
            ON mesta.mes_cdiokrug = okruzi.okr_cdiokrug
            INNER JOIN drzave
            ON okruzi.okr_cdidrzava = drzave.drz_cdidrzava
            WHERE drzave.drz_cdidrzava > 0 $sqlWhere"
            ;


    $html = "";

    $brojac = 1;
    $data = $conn->query($sql);

    while ($row = $data->fetch_assoc()) {

        $html .= "
            <tr>
                <th scope='row'>$brojac</th>
                <td>".$row['mes_dssnaziv']."</td>
                <td>".$row['mes_dsspostanskibroj']."</td>
                <td>".$row['okr_dssnaziv']."</td>
                <td>".$row['drz_dssnaziv']."</td>
            </tr>";

        $brojac += 1;
    }

    echo $html;

}



//where mesta.mes_cdimesto = 18

if($_POST['akcija'] == 'novo'){

    $mesto = $_POST['mesto'];
    $postanskiBroj = $_POST['postanskiBroj'];
    $okrug = $_POST['okrug'];

    $proveriId = "SELECT MAX(mes_cdimesto) maksimum from mesta";
    $rezultat = $conn->query($proveriId);
    if($rezultat->num_rows > 0){
        $idMestaStr = $rezultat->fetch_assoc()['maksimum'];
        $idMesta = (int)$idMestaStr+1;

        $sql = "INSERT INTO mesta (mes_cdimesto, mes_cdiokrug, mes_dssnaziv,mes_dsspostanskibroj)
         VALUES (".$idMesta.", ".$okrug.", '".$mesto."', '".$postanskiBroj."')";

        $conn->query($sql);

        if($conn->affected_rows > 0){
            $sql = "SELECT drzave.drz_dssnaziv, mesta.mes_dssnaziv, okruzi.okr_dssnaziv, mesta.mes_dsspostanskibroj
                    FROM mesta
                    INNER JOIN okruzi
                    ON mesta.mes_cdiokrug = okruzi.okr_cdiokrug
                    INNER JOIN drzave
                    ON okruzi.okr_cdidrzava = drzave.drz_cdidrzava
                    WHERE mesta.mes_cdimesto =".$idMesta.";";

            $rezultat = $conn->query($sql);
            $podaci = $rezultat->fetch_assoc();

            $drzava = $podaci['drz_dssnaziv'];
            $mesto = $podaci['mes_dssnaziv'];
            $okrug = $podaci['okr_dssnaziv'];
            $postanskiBroj = $podaci['mes_dsspostanskibroj'];

            $niz = [
                'akcija' => 'novo',
                'id'=> $idMesta,
                'drzava'=>$drzava,
                'mesto'=>$mesto,
                'okrug'=>$okrug,
                'postanskiBroj'=>$postanskiBroj
            ];

            $json = json_encode($niz);
            echo $json;
      
        }
        else{
            echo "jok!";
        }     
    } else{
        echo 'jok';
    }
}





if($_POST['akcija'] == 'brisanje'){
    $sql = "DELETE FROM mesta WHERE mes_cdimesto =".$_POST['id'];

    $conn->query($sql);

    if($conn->affected_rows > 0){
        echo 'ok';
    }
    else{
        echo 'jok';
    }




}


if($_POST['akcija'] == 'izmena'){
// mesto: razmaktest
// okrug: 7
// postanskiBroj: 123
// akcija: izmena
// idMesto: 14

    $id = $_POST['idMesto'];
    $mesto = $_POST['mesto'];
    $okrug = $_POST['okrug'];
    $postanskiBroj = $_POST['postanskiBroj'];


    $sql = "UPDATE mesta SET mes_cdiokrug =".$okrug." , mes_dssnaziv ='".$mesto."' , mes_dsspostanskibroj ='".$postanskiBroj."' WHERE mes_cdimesto = ". $id;

    $conn->query($sql);

    if($conn->affected_rows >0){

        $sql = "SELECT drzave.drz_cdidrzava FROM drzave INNER JOIN okruzi on okruzi.okr_cdidrzava = drzave.drz_cdidrzava where okruzi.okr_cdiokrug =". $okrug;
            $rezultat = $conn->query($sql);
            $podaci = $rezultat->fetch_assoc();

            $drzava=$podaci['drz_cdidrzava'];



        $niz = [
            'akcija' => 'izmena',
            'id' => $id,
            'mesto' => $mesto,
            'okrug' => $okrug,
            'postanskiBroj' => $postanskiBroj,
            'drzava' => $drzava
        ];

        $json = json_encode($niz);
        echo $json;
    }

}


?>