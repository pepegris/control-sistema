<?php 

$data = array (

    array (
        'id'=>1,
        'nombre'=>'andres',
        'edad'=>29
    ),
    array (
        'id'=>2,
        'nombre'=>'diana',
        'edad'=>30
    ),
    array (
        'id'=>3,
        'nombre'=>'perozo',
        'edad'=>22
    ),
    array (
        'id'=>4,
        'nombre'=>'camila',
        'edad'=>9
    ),
);

echo json_encode($data);
?>