<?php


$bd= array(15=>'ACARI_A',
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
185=>'VALLEPAA',);

$serv= array(15=>'SRVPREV',
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
185=>'VALLEPA',);

    for ($i=5; $i <= $sedes_nom; $i+=10) { 


        /*    echo $bd[$i].$tiendas_seleccionadas[$i].'<br>'; */
            $base_dato=$bd[$i];
            $servidor=$serv[$i];
            
    }

    $serverName = "$servidor"; 
    $connectionInfo = array( "Database"=>"$base_dato", "UID"=>"Mezcla", "PWD"=>"Zeus33$");
    $conn = sqlsrv_connect( $serverName, $connectionInfo);




?>

