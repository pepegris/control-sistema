<?php


$tienda1=$_POST['Comercial_Acari'];
$tienda2=$_POST['Comercial_Apura'];
$tienda3=$_POST['Comercial_Catica_II'];
$tienda4=$_POST['Comercial_Corina_I'];
$tienda5=$_POST['Comercial_Corina_II'];
$tienda6=$_POST['Comercial_Higue'];
$tienda7=$_POST['Comercial_Kagu'];
$tienda8=$_POST['Comercial_Matur'];
$tienda9=$_POST['Comercial_Merina'];
$tienda10=$_POST['Comercial_Merina_III'];
$tienda11=$_POST['Comercial_Nachari'];
$tienda12=$_POST['Comercial_Ojena'];
$tienda13=$_POST['Comercial_Puecruz'];
$tienda14=$_POST['Comercial_Punto_Fijo'];
$tienda15=$_POST['Comercial_Trina'];
$tienda16=$_POST['Comercial_Turme'];
$tienda17=$_POST['Comercial_Valena'];
$tienda18=$_POST['Comercial_Vallepa'];
$tienda19=$_POST['Sede_Boleita'];







// var_dump($_POST);

$tienda_total=0;
$tienda_total=$tienda1+$tienda2+$tienda3+$tienda4+$tienda5+$tienda6+$tienda7+$tienda8+$tienda9+$tienda10+$tienda11+$tienda12+$tienda13+$tienda14+$tienda15+$tienda16+$tienda17+$tienda18+$tienda19;

$tiendas_seleccionadas=array(
    15=>$tienda1,
    25=>$tienda2,
    35=>$tienda3,
    45=>$tienda4,
    55=>$tienda5,
    65=>$tienda6,
    75=>$tienda7,
    85=>$tienda8,
    95=>$tienda9,
    105=>$tienda10,
    115=>$tienda11,
    125=>$tienda12,
    135=>$tienda13,
    145=>$tienda14,
    155=>$tienda15,
    165=>$tienda16,
    175=>$tienda17,
    185=>$tienda18,
    195=>$tienda19,);



    $bd_tienda= array(15=>'ACARI_A',
    25=>'APURA_A',
    35=>'CATICA2A',
    45=>'CORINA_A',
    55=>'CORINA2A',
    65=>'HIGUE_A',
    75=>'KAGU_A',
    85=>'MATUR_A',
    95=>'MERINA_A',
    105=>'merina3a',
    115=>'nacharia',
    125=>'ojena_a',
    135=>'puecruza',
    145=>'PUFIJO_A',
    155=>'TRINA_A',
    165=>'TURME_A',
    175=>'VALENA_A',
    185=>'VALLEPAA',
    195=>'PREVIA_A',);

// SERV BOLEITA
$bd = array (
    15=> 'ACARI21',
    25=>'APURA21',
    35=> 'CATICA21',
    45=>'CORINA21',
    55=>'CORI2_21',
    65=> 'HIGUE21',
    75=> 'KAGUA21',
    85=> 'MATURA21',
    95=> 'MERINA21',
    105=> 'MRIA3A21',
    115=> 'NACHAR21',
    125=> 'OJENA21',
    135=> 'PUECRU21',
    145=> 'PUFIJO21',
    155=> 'TRAINA21',
    165=> 'TURME21',
    175=> 'VALENA21',
    185=> 'VALLEP21'
 )


    $serv_tiendas= array(15=>'SRVPREV',
        25=>'APURA',
        35=>'CATICA2',
        45=>'SRVPREV\CORINA',
        55=>'CORINA2',
        65=>'SRVPREV\HIGUE',
        75=>'KAGU',
        85=>'MATUR',
        95=>'MERINA',
        105=>'merina3',
        115=>'nacharia',
        125=>'ojena',
        135=>'puecruza',
        145=>'PUFIJO',
        155=>'TRINA',
        165=>'TURME',
        175=>'VALENA',
        185=>'VALLEPA',
        195=>'SQL',);





?>