<?php


function getImport()
{

    try {

        $serverName = "172.16.1.39";
        $connectionInfo = array("Database" => "master", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);


        $sql = "
                DECLARE @path VARCHAR(500)
                DECLARE @name VARCHAR(500)
                DECLARE @filename VARCHAR(256)



                SET @path = 'C:\BAK\'  

                
                DECLARE db_cursor CURSOR FOR  
                SELECT name 
                FROM master.dbo.sysdatabases 
                WHERE name NOT IN (

                    'C_ACARI',
                    'C_APURA',
                    'C_MERINA1',
                    'C_MERINA3',
                    'C_CORINA1',
                    'C_CORINA2',
                    'C_PUFIJO',
                    'C_MATUR',
                    'C_VALENA',
                    'C_TRINA',
                    'C_KAGU',
                    'C_NACHARI',
                    'C_HIGUE',
                    'C_VALLEPA',
                    'C_OJENA',
                    'C_PUECRUZ',
                    'C_CATICA2',
                    'C_PREVIA',
                    'C_JORINA',
                    'C_CARACAS1',
                    'C_CARACAS2',
                    'C_CAGUA',
                    'C_MATURIN',

                    'N_ACARI',
                    'N_APURA',
                    'N_MERINA1',
                    'N_MERINA3',
                    'N_CORINA1',
                    'N_CORINA2',
                    'N_PUFIJO',
                    'N_MATUR',
                    'N_VALENA',
                    'N_TRINA',
                    'N_KAGUA',
                    'N_NACHARI',
                    'N_HIGUE',
                    'N_VALLEPA',
                    'N_OJENA',
                    'N_PUECRUZ',
                    'N_CATICA2',
                    'N_PREVIA',
                    'N_JORINA',
                    'N_CARACAS1',
                    'N_CARACAS2',
                    'N_CAGUA',
                    'N_MATURIN',
                    
                    'MasterProfit','MasterProfitPro','ReportServer','ReportServerTempDB',
                    'SISTEMAS','TPLIO1A','TPLIOT1A','REPLI','EMPTY','DEV_EMP','DEMON','DEMOC','demo',
                    'master','model','msdb','tempdb','SISTEMAS','REPLI','Sincronizador','N_PRUEBA',
                    'DEMON1','migra','PRUEBA','DEV_EMP','DEV_EMP2'


                    )  

                

                OPEN db_cursor   
                FETCH NEXT FROM db_cursor INTO @name   

                WHILE @@FETCH_STATUS = 0   
                BEGIN


                
                
                    SET @fileName = @path + @name + '.BAK'  
                    BACKUP DATABASE @name TO DISK = @fileName  WITH COMPRESSION,INIT 

                
                    FETCH NEXT FROM db_cursor INTO @name    
                END   
                CLOSE db_cursor   
                DEALLOCATE db_cursor";

        $consulta = sqlsrv_query($conn, $sql);


        if ($consulta == null) {

            $res = false;
            return $res;
        }else{

            $res = true;
            return $res;
        }

    } catch (\Throwable $th) {

        throw $th;
    }
}





function getRestore()
{

    try {

        $serverName = "172.16.1.19";
        $connectionInfo = array("Database" => "master", "UID" => "mezcla", "PWD" => "Zeus33$", "CharacterSet" => "UTF-8");
        $conn = sqlsrv_connect($serverName, $connectionInfo);


        $sql = "
                RESTORE DATABASE ACARI21
                FROM DISK = N'\\Sql2k8\bak\ACARI.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'ACARI21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\ACARI21.mdf',
                MOVE N'ACARI21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\ACARI21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE APURA21
                FROM DISK = N'\\Sql2k8\bak\APURA.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'APURA21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\APURA21.mdf',
                MOVE N'APURA21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\APURA21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE CAGUA
                FROM DISK = N'\\Sql2k8\bak\CAGUA.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'CAGUA' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CAGUA.mdf',
                MOVE N'CAGUA_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CAGUA_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5

                RESTORE DATABASE CARACAS1
                FROM DISK = N'\\Sql2k8\bak\CARACAS1.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'CARACAS1' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CARACAS1.mdf',
                MOVE N'CARACAS1_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CARACAS1_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5

                RESTORE DATABASE CARACAS2
                FROM DISK = N'\\Sql2k8\bak\CARACAS2.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'CARACAS2' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CARACAS2.mdf',
                MOVE N'CARACAS2_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CARACAS2_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5

                RESTORE DATABASE CORINA21
                FROM DISK = N'\\Sql2k8\bak\CORINA1.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'CORINA1' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CORINA21.mdf',
                MOVE N'CORINA1_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CORINA21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE CORI2_21
                FROM DISK = N'\\Sql2k8\bak\CORINA2.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'CORI2_21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CORI2_21.mdf',
                MOVE N'CORI2_21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\CORI2_21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE HIGUE21
                FROM DISK = N'\\Sql2k8\bak\HIGUE.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'HIGUE21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\HIGUE21.mdf',
                MOVE N'HIGUE21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\HIGUE21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE JORINA21
                FROM DISK = N'\\Sql2k8\bak\JORINA.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'JORINA_A' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\JORINA21.mdf',
                MOVE N'JORINA_A_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\JORINA21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE MATURIN
                FROM DISK = N'\\Sql2k8\bak\MATURIN.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'MATURIN' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\MATURIN.mdf',
                MOVE N'MATURIN_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\MATURIN_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE OJENA21
                FROM DISK = N'\\Sql2k8\bak\OJENA.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'OJENA21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\OJENA21.mdf',
                MOVE N'OJENA21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\OJENA21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE PREVIA_A
                FROM DISK = N'\\Sql2k8\bak\PREVIA_A.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'PREVIA_A' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\PREVIA_A.mdf',
                MOVE N'PREVIA_A_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\PREVIA_A_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE PUECRU21
                FROM DISK = N'\\Sql2k8\bak\PUECRUZ.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'PUECRU21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\PUECRU21.mdf',
                MOVE N'PUECRU21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\PUECRU21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5

                RESTORE DATABASE PUFIJO21
                FROM DISK = N'\\Sql2k8\bak\PUFIJO.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'PUFIJO21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\PUFIJO21.mdf',
                MOVE N'PUFIJO21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\PUFIJO21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5

                RESTORE DATABASE TRAINA21
                FROM DISK = N'\\Sql2k8\bak\TRINA.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'TRAINA21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\TRAINA21.mdf',
                MOVE N'TRAINA21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\TRAINA21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE VALENA21
                FROM DISK = N'\\Sql2k8\bak\VALENA.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'VALE_A21' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\VALENA21.mdf',
                MOVE N'VALE_A21_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\VALENA21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5


                RESTORE DATABASE VALLEP21
                FROM DISK = N'\\Sql2k8\bak\VALLEPA.BAK' --RUTA DEL .BAK A RESTAURAR
                WITH FILE = 1, MOVE N'VALLEPA' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\VALLEP21.mdf',
                MOVE N'VALLEPA_log' TO N'C:\Program Files\Microsoft SQL Server\MSSQL11.MSSQLSERVER\MSSQL\DATA\VALLEP21_log.ldf',
                NOUNLOAD, REPLACE, STATS = 5
                ";

        $consulta = sqlsrv_query($conn, $sql);


        if ($consulta == null) {

            $res = false;
            return $res;
        }else{

            $res = true;
            return $res;
        }

    } catch (\Throwable $th) {

        throw $th;
    }
}
