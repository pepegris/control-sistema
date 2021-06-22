<?php 


// calculando los contadores permitidos para cada tienda
     
$sedes_Boleita=array();
$sedes_Sabana_Grande=array();
$sedes_Merina=array();  
$sedes_Merina_III=array();
$sedes_Corina_I=array();  
$sedes_Corina_II=array(); 
$sedes_Punto_Fijo=array(); 
$sedes_Matur=array();       
$sedes_Valena=array();     
$sedes_Trina=array();      
$sedes_Kagu=array();       
$sedes_Nachari=array();   
$sedes_Higue=array();      
$sedes_Turme=array();     
$sedes_Apura=array();     
$sedes_Vallepa=array();    
$sedes_Ojena=array();      
$sedes_Puecruz=array();     
$sedes_Acari=array();       
$sedes_Catica_II=array();


$sql_sedes="SELECT * FROM sedes ";

$consulta_sedes=mysqli_query($conn,$sql_sedes);




while ($sede=mysqli_fetch_array($consulta_sedes)) {

   $sedes_nom=$sede['sedes_nom']; 
   $sedes_contador=$sede['contadores'];
   

   switch ($sedes_nom) {

       case 'Sede Boleita':
           
          $sedes_Boleita[]=$sedes_contador;

          break; 
  
          case 'Sede Sabana Grande':
  

  
              $sedes_Sabana_Grande[]=$sedes_contador;
        
              break;
  
              case 'Comercial Merina':
 
  
                  $sedes_Merina[]=$sedes_contador;
               
                 break;
  
                  case 'Comercial Merina III':

  
                      $sedes_Merina_III[]=$sedes_contador;
               
                     break;
  
                      case 'Comercial Corina I':

  
                          $sedes_Corina_I[]=$sedes_contador;
  
                                             
                         
                         break;
  
                          case 'Comercial Corina II':
    
  
                              $sedes_Corina_II[]=$sedes_contador;
     
                                                      
                             
                             break;
  
                              case 'Comercial Punto Fijo':
                                  
  
                                   $sedes_Punto_Fijo[]=$sedes_contador;
                                 
                                 
                                 break;
  
                                  case 'Comercial Matur':
                           
  
                                      $sedes_Matur[]=$sedes_contador;
                
                                                  
                                     
                                     break;
  
                                      case 'Comercial Valena':
                               
  
                                           $sedes_Valena[]=$sedes_contador;
                 
                                                                           
                                         
                                         break;
  
                                          case 'Comercial Trina':
                                   
  
                                               $sedes_Trina[]=$sedes_contador;
                     
                                                                                  
                                             
                                             break;
  
                                              case 'Comercial Kagu':
                                       
  
                                              $sedes_Kagu []= $sedes_contador;
                         
                                                                                         
                                                 
                                                 break;
  
                                                  case 'Comercial Nachari':
                                           
  
                                                      $sedes_Nachari[]=$sedes_contador;
                             
                                                                                                    
                                                     
                                                     break;
  
                                                      case 'Comercial Higue':
                                               
  
                                                          $sedes_Higue[]=$sedes_contador;
                                 
                                                                                                          
                                                         
                                                         break;
  
                                                          case 'Comercial Turme':
                                                   
  
                                                              $sedes_Turme[]=$sedes_contador;
                                        
                                                                                                                  
                                                             
                                                             break;
  
                                                              case 'Comercial Apura':
                                                       
  
                                                                   $sedes_Apura[]=$sedes_contador;
                                         
                                                                                                                          
                                                                 
                                                                 break;
                                                                  case 'Comercial Vallepa':
                                                           
  
                                                                      $sedes_Vallepa[]=$sedes_contador;
                                             
                                                                                                                                    
                                                                     
                                                                     break;
  
                                                                      case 'Comercial Ojena':
                                                               
  
                                                                           $sedes_Ojena[]=$sedes_contador;
                                                 
                                                                                                                                          
                                                                         
                                                                         break;
  
                                                                          case 'Comercial Puecruz':
                                                                   
  
                                                                               $sedes_Puecruz[]=$sedes_contador;
                                                     
                                                                                                                                                    
                                                                             
                                                                             break;
  
                                                                              case 'Comercial Acari':
                                                                       
  
                                                                                  $sedes_Acari[]=$sedes_contador;
                                                         
                                                                                                                                                      
                                                                              
                                                                                 break;
  
                                                                                 case 'Comercial Catica II':
                                                                           
  
                                                                                      $sedes_Catica_II[]=$sedes_contador;
                                                             
                                                                                                                                                                     
                                                                                     
                                                                                     break;
              
          
      
      default:
          # code...
          
          
          break;
  } 

   

   
   
};



$tienda=0;

                              $sedes_Boleita[]=0;
                            $sedes_Sabana_Grande[]=0;
                             $sedes_Merina[]=0;  
                             $sedes_Merina_III[]=0;
                             $sedes_Corina_I[]=0;  
                             $sedes_Corina_II[]=0; 
                             $sedes_Punto_Fijo[]=0; 
                             $sedes_Matur[]=0;       
                             $sedes_Valena[]=0;     
                             $sedes_Trina[]=0;      
                             $sedes_Kagu[]=0;       
                             $sedes_Nachari[]=0;   
                             $sedes_Higue[]=0;      
                             $sedes_Turme[]=0;     
                             $sedes_Apura[]=0;     
                             $sedes_Vallepa[]=0;    
                             $sedes_Ojena[]=0;      
                             $sedes_Puecruz[]=0;     
                             $sedes_Acari[]=0;       
                             $sedes_Catica_II[]=0;


switch ($tienda) {

     case 'Sede Boleita':
         
        
        $sedes_contador=$sedes_Boleita[0];
            
        
        break; 

        case 'Sede Sabana Grande':

           /* echo "<br>estoy en $indice $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

           $sedes_contador= $sedes_Sabana_Grande[0];

            
            
            
            break;

            case 'Comercial Merina':
               /* echo "<br>estoy en $indice $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

               $sedes_contador= $sedes_Merina[0];

                         
               
               break;

                case 'Comercial Merina III':
                   /* echo "<br>estoy en $indice $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                   $sedes_contador= $sedes_Merina_III[0];

                                     
                   
                   break;

                    case 'Comercial Corina I':
                       /* echo "<br>estoy en $indice $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                       $sedes_contador= $sedes_Corina_I[0];

                                           
                       
                       break;

                        case 'Comercial Corina II':
                           /* echo "<br>estoy en $indice $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                           $sedes_contador= $sedes_Corina_II[0];
   
                                                    
                           
                           break;

                            case 'Comercial Punto Fijo':
                                /* echo "<br>estoy en $indice $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                $sedes_contador= $sedes_Punto_Fijo[0];
       
                                                             
                               
                               break;

                                case 'Comercial Matur':
                                   /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                   $sedes_contador= $sedes_Matur[0];
              
                                                                
                                   
                                   break;

                                    case 'Comercial Valena':
                                        /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                        $sedes_contador= $sedes_Valena[0];
               
                                                                         
                                       
                                       break;

                                        case 'Comercial Trina':
                                            /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                            $sedes_contador= $sedes_Trina[0];
                   
                                                                                
                                           
                                           break;

                                            case 'Comercial Kagu':
                                                /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                $sedes_contador= $sedes_Kagu[0];
                       
                                                                                       
                                               
                                               break;

                                                case 'Comercial Nachari':
                                                   /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                   $sedes_contador= $sedes_Nachari[0];
                           
                                                                                                  
                                                   
                                                   break;

                                                    case 'Comercial Higue':
                                                       /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                       $sedes_contador= $sedes_Higue[0];
                               
                                                                                                        
                                                       
                                                       break;

                                                        case 'Comercial Turme':
                                                           /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                           $sedes_contador= $sedes_Turme[0];
                                      
                                                                                                                
                                                           
                                                           break;

                                                            case 'Comercial Apura':
                                                                /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                                $sedes_contador= $sedes_Apura[0];
                                       
                                                                                                                        
                                                               
                                                               break;
                                                                case 'Comercial Vallepa':
                                                                   /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                                   $sedes_contador= $sedes_Vallepa[0];
                                           
                                                                                                                                  
                                                                   
                                                                   break;

                                                                    case 'Comercial Ojena':
                                                                        /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                                        $sedes_contador= $sedes_Ojena[0];
                                               
                                                                                                                                        
                                                                       
                                                                       break;

                                                                        case 'Comercial Puecruz':
                                                                            /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                                            $sedes_contador= $sedes_Puecruz[0];
                                                   
                                                                                                                                                  
                                                                           
                                                                           break;

                                                                            case 'Comercial Acari':
                                                                               /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                                               $sedes_contador= $sedes_Acari[0];
                                                       
                                                                                                                                                    
                                                                            
                                                                               break;

                                                                               case 'Comercial Catica II':
                                                                                   /* echo "<br>estoy en $indice/ $sedes_contador1 - $sedes_contador2 - $sedes_contador3<br>"; */

                                                                                   $sedes_contador= $sedes_Catica_II[0];
                                                           
                                                                                                                                                                   
                                                                                   
                                                                                   break;
            
        
    
    default:
        # code...
        
        
        break;
} 




?>