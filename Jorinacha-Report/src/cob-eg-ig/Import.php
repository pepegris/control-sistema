<?php
  require '../../includes/log.php';
  include '../../includes/loading.php';
  include '../../services/sqlserver.php';
  include '../../services/adm/cob-eg-ig/import.php';

if (isset($_POST)) {


    $scripts=$_POST['scripts'];

    if ($scripts== 'backups') {

      $query=getImport();

      if ($query==true) {
        $mensaje='Se esta realizando los respaldos debe esperar 30min para Importar';
      }else {
        $mensaje='No se pudo realizar los respaldos ERROR IN THE DATABASE';
      }

    }elseif ($scripts== 'restore') {

        $query=getRestore();

        if ($query==true) {
            $mensaje='Se esta realizando la importación debe esperar 45min para poder Integrar';
          }else {
            $mensaje='No se pudo realizar los respaldos ERROR IN THE DATABASE';
          }
      
    }else{

        header('refresh:1;url= Import-database.php');

    } 
    echo "<center><h3 style='color:white'>$mensaje</h3></center>";
    echo "<center><a href='Import-database.php' class='btn btn-success'>Volver</a></center>";


} else {
    header('refresh:1;url= Import-database.php');
    exit;
}
?>
<div id="hasta45Min" style="color: red; font-weight: bolder;"></div>
<div id="despues45Min" style="color: green; font-weight: bolder;"></div>
<script>
    hasta45Min = document.getElementById("hasta45Min");
        despues45Min = document.getElementById("despues45Min");
        // Declaro variables para después de los 45 minutos.
        let sec= 0, min = 0, hour = 0;
        // Para contar hasta los 45 minutos
        let startMin = 45*60, cronometro = 0;
        // Creo la funcion
        setInterval(function (){
            if(cronometro >= startMin){
                sec++;
                if(sec == 60){
                    min++;
                    sec = 0;
                    if(min == 60){
                        hour++;
                        min = 0;
                    }
                }
                console.log(`Ha pasado 45 Min. Empezamos a contar: ${hour} Horas, ${min} Minutos y ${sec} Segundos.`);
                hasta45Min.style.display = 'none';
                despues45Min.innerHTML = `Ha pasado 45 Min. Empezamos a contar: ${hour} Horas, ${min} Minutos y ${sec} Segundos.`;
            }else{
                cronometro++
                console.log(`Tiempo restante a 45 Min ${startMin - cronometro} segundos`);
                hasta45Min.innerHTML = `Tiempo restante a 45 Min &rarr; ${startMin - cronometro} segundos`;
            }
        },1000);
</script>
<div id="hasta45Min" style="color: red; font-weight: bolder;"></div>
<div id="despues45Min" style="color: green; font-weight: bolder;"></div>