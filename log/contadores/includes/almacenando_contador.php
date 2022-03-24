<?php



// calculando los contadores finales

     $fecha_final_desde= date("Ymd",strtotime($fecha_desde."- 1 month"));
     $fecha_final_hasta= date("Ymd",strtotime($fecha_hasta."- 1 month"));


     $sql_final="SELECT * from contador where con_fecha between '$fecha_final_desde' and '$fecha_final_hasta' ";

     
     $consulta_final= mysqli_query($conn,$sql_final);

     $contador_Boleita=array();

     

    
     

    

     while ($res=mysqli_fetch_array($consulta_final)) {

         $indice=$res['tienda'];


         $final1=$res['inicial1'];

         $final2=$res['inicial2'];

         $final3=$res['inicial3'];

       /*   $con_img=$res['con_img'];
         $con_img2=$res['con_img2'];  
         $con_img3=$res['con_img3']; */
        


         //almacenando los contadores final en un array por cada tienda
        switch ($indice) {

             case 'Sede Boleita':
                 /* echo "<br>estoy en $indice $final1 - $final2 - $final3<br>"; */
                 
                 $contador_Boleita=array();
                $img_Boleita=array();

                 $contador_Boleita[]=$final1;
                 $contador_Boleita[]=$final2;
                 $contador_Boleita[]=$final3;

                
                 
                 break;

                 case 'Sede Sabana Grande':

                    /* echo "<br>estoy en $indice $final1 - $final2 - $final3<br>"; */

                     $contador_Sabana_Grande=array();
                     $img_Sabana_Grande=array();

                     $contador_Sabana_Grande[]=$final1;
                     $contador_Sabana_Grande[]=$final2;
                     $contador_Sabana_Grande[]=$final3;

                     
                     
                     
                     
                     break;

                     case 'Comercial Merina':
                        /* echo "<br>estoy en $indice $final1 - $final2 - $final3<br>"; */

                        $contador_Merina=array();
                        $img_Merina=array();
   
                        $contador_Merina[]=$final1;
                        $contador_Merina[]=$final2;
                        $contador_Merina[]=$final3;
   
                    
                        
                        
                        
                        break;

                         case 'Comercial Merina III':
                            /* echo "<br>estoy en $indice $final1 - $final2 - $final3<br>"; */

                            $contador_Merina_III=array();
                            $img_Merina_III=array();

                            $contador_Merina_III[]=$final1;
                            $contador_Merina_III[]=$final2;
                            $contador_Merina_III[]=$final3;
    
                           
                             
                            
                            
                            
                            break;

                             case 'Comercial Corina I':
                                /* echo "<br>estoy en $indice $final1 - $final2 - $final3<br>"; */

                                $contador_Corina_I=array();
                                $img_Corina_I=array();
        
                                $contador_Corina_I[]=$final1;
                                $contador_Corina_I[]=$final2;
                                $contador_Corina_I[]=$final3;
        
                                                                
                    
                                
                                break;

                                 case 'Comercial Corina II':
                                    /* echo "<br>estoy en $indice $final1 - $final2 - $final3<br>"; */

                                    $contador_Corina_II=array();
                                    $img_Corina_II=array();
            
                                    $contador_Corina_II[]=$final1;
                                    $contador_Corina_II[]=$final2;
                                    $contador_Corina_II[]=$final3;
            
                   
                                    
                                    
                                    break;

                                     case 'Comercial Punto Fijo':
                                         /* echo "<br>estoy en $indice $final1 - $final2 - $final3<br>"; */

                                        $contador_Punto_Fijo=array();
                                        $img_Punto_Fijo=array();
                
                                        $contador_Punto_Fijo[]=$final1;
                                        $contador_Punto_Fijo[]=$final2;
                                        $contador_Punto_Fijo[]=$final3;
                
                                    
                                        
                                        
                                        
                                        break;

                                         case 'Comercial Matur':
                                            /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                            $contador_Matur=array();
                                            $img_Matur=array();
                       
                                            $contador_Matur[]=$final1;
                                            $contador_Matur[]=$final2;
                                            $contador_Matur[]=$final3;
                                            
                                           
                                                                                     
                                            
                                            
                                            
                                            break;

                                             case 'Comercial Valena':
                                                 /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                $contador_Valena=array();
                                                $img_Valena=array();
                        
                                                $contador_Valena[]=$final1;
                                                $contador_Valena[]=$final2;
                                                $contador_Valena[]=$final3;
                        
                                                                                    
                                                
                                                
                                                
                                                break;

                                                 case 'Comercial Trina':
                                                     /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                    $contador_Trina=array();
                                                    $img_Trina=array();
                            
                                                    $contador_Trina[]=$final1;
                                                    $contador_Trina[]=$final2;
                                                    $contador_Trina[]=$final3;
                            
                                                                                           
                                                    
                                                    
                                                    
                                                    break;

                                                     case 'Comercial Kagu':
                                                         /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                        $contador_Kagu=array();
                                                        $img_Kagu=array();
                                
                                                        $contador_Kagu[]=$final1;
                                                        $contador_Kagu[]=$final2;
                                                        $contador_Kagu[]=$final3;
                                
                                                  
                                                        
                                                        
                                                        break;

                                                         case 'Comercial Nachari':
                                                            /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                            $contador_Nachari=array();
                                                            $img_Nachari=array();
                                    
                                                            $contador_Nachari[]=$final1;
                                                            $contador_Nachari[]=$final2;
                                                            $contador_Nachari[]=$final3;

                                                            
                                                            
                                                            break;

                                                             case 'Comercial Higue':
                                                                /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                                $contador_Higue=array();
                                                                $img_Higue=array();
                                        
                                                                $contador_Higue[]=$final1;
                                                                $contador_Higue[]=$final2;
                                                                $contador_Higue[]=$final3;
                                        
                                                            
                                                                
                                                                break;

                                                                 case 'Comercial Turme':
                                                                    /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                                    $contador_Turme=array();
                                                                    $img_Turme=array();
                                               
                                                                    $contador_Turme[]=$final1;
                                                                    $contador_Turme[]=$final2;
                                                                    $contador_Turme[]=$final3;
                                               
                                                                                                                                     
                                                                
                                                                    break;

                                                                     case 'Comercial Apura':
                                                                         /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                                        $contador_Apura=array();
                                                                        $img_Apura=array();
                                                
                                                                        $contador_Apura[]=$final1;
                                                                        $contador_Apura[]=$final2;
                                                                        $contador_Apura[]=$final3;
                                                
                                                                      
                                                                        
                                                                        
                                                                        break;
                                                                         case 'Comercial Vallepa':
                                                                            /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                                            $contador_Vallepa=array();
                                                                            $img_Vallepa=array();
                                                    
                                                                            $contador_Vallepa[]=$final1;
                                                                            $contador_Vallepa[]=$final2;
                                                                            $contador_Vallepa[]=$final3;
                                                    
                                                                         
                                                                            
                                                                            break;

                                                                             case 'Comercial Ojena':
                                                                                 /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                                                $contador_Ojena=array();
                                                                                $img_Ojena=array();
                                                        
                                                                                $contador_Ojena[]=$final1;
                                                                                $contador_Ojena[]=$final2;
                                                                                $contador_Ojena[]=$final3;
                                                        
                                                                                                                                                             
                                                                              
                                                                                
                                                                                break;

                                                                                 case 'Comercial Puecruz':
                                                                                     /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                                                    $contador_Puecruz=array();
                                                                                    $img_Puecruz=array();
                                                            
                                                                                    $contador_Puecruz[]=$final1;
                                                                                    $contador_Puecruz[]=$final2;
                                                                                    $contador_Puecruz[]=$final3;
                                                            
                                                                                                                                                                       
                                                                                   
                                                                                    
                                                                                    break;

                                                                                     case 'Comercial Acari':
                                                                                        /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                                                        $contador_Acari=array();
                                                                                        $img_Acari=array();
                                                                
                                                                                        $contador_Acari[]=$final1;
                                                                                        $contador_Acari[]=$final2;
                                                                                        $contador_Acari[]=$final3;
                                                                                        
                                                                                       
                                                                                     
                                                                                     
                                                                                        break;

                                                                                        case 'Comercial Catica II':
                                                                                            /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */

                                                                                            $contador_Catica_II=array();
                                                                                            $img_Catica_II=array();
                                                                    
                                                                                            $contador_Catica_II[]=$final1;
                                                                                            $contador_Catica_II[]=$final2;
                                                                                            $contador_Catica_II[]=$final3;

                                                                                                                                                                                    
                                                                                            
                                                                                            
                                                                                            
                                                                                            break;

                                                                                            case 'Comercial Catica I':
                                                                                                /* echo "<br>estoy en $indice/ $final1 - $final2 - $final3<br>"; */
    
                                                                                                $contador_Catica_I=array();
                                                                                                $img_Catica_I=array();
                                                                        
                                                                                                $contador_Catica_I[]=$final1;
                                                                                                $contador_Catica_I[]=$final2;
                                                                                                $contador_Catica_I[]=$final3;
    
                                                                                                                                                                                        
                                                                                                
                                                                                                
                                                                                                
                                                                                                break;
                     
                 
             
             default:
                 # code...
                 
                 
                 
                 
                 break;
         } 


        



     }





     


?>