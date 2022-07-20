<?php
require "../../includes/log.php";
include '../../includes/header.php';
include '../../services/mysql.php';
include '../../services/sqlserver.php';

if ($_POST) {


    
    $linea=$_POST['linea'];
    for ($i=0; $i < 20 ; $i+=1) { 
        $sedes [] = $_POST[$i];
    }

    $bd = array (
        "Previa Shop"=> 'PREVIA_A',
        "Comercial Merina"=> 'MERINA21',
        "Comercial Merina III"=> 'MRIA3A21',
        "Comercial Corina I"=>'CORINA21',
        "Comercial Corina II"=>'CORI2_21',
        "Comercial Punto Fijo"=> 'PUFIJO21',
        "Comercial Matur"=> 'MATURA21',
        "Comercial Valena"=> 'VALENA21',
        "Comercial Trina"=> 'TRAINA21',
        "Comercial Kagu"=> 'KAGUA21',
        "Comercial Nachari"=> 'NACHAR21',
        "Comercial Higue"=> 'HIGUE21',
        "Comercial Apura"=>'APURA21',
        "Comercial Vallepa"=> 'VALLEP21',
        "Comercial Ojena"=> 'OJENA21',
        "Comercial Puecruz"=> 'PUECRU21',
        "Comercial Acari"=> 'ACARI21',
        "Comercial Catica II" => 'CATICA21',
    );

    var_dump($bd["Comercial Catica II"]);

?>










<?php
}else {
    header("location: form.php");
}




include '../../includes/footer.php';?>