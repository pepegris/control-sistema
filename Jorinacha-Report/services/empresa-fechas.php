<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////   
if ($sede == "Sucursal Caracas I" && $Month < 04  && $Year <= 2023) {
    $sede = 'Comercial Merina';
}
if ($sede == "Comercial Merina" && $Month == 04  && $Year >= 2023) {
    $sede = 'Sucursal Caracas I';
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////   
if ($sede == "Sucursal Caracas II" && $Month < 04  && $Year <= 2023) {
    $sede = 'Comercial Merina3';
}
if ($sede == "Comercial Merina3" && $Month == 04   && $Year >=  2023) {
    $sede = 'Sucursal Caracas II';
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($sede == "Sucursal Maturin" && $Month < 10  && $Year <= 2023) {
    $sede = 'Comercial Matur';
}
if ($sede == 'Comercial Matur' && $Month > 9 && $Year == '2023') {
    $sede = "Sucursal Maturin";
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////          
if ($sede == "Sucursal Cagua" && $Month < 06  && $Year <= 2023) {
    $sede = 'Comercial Kagu';
}
if ($sede == 'Comercial Kagu' && $Month > 5 && $Year == '2023') {
    $sede = "Sucursal Cagua";
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////   
if ($sede == 'Sucursal Coro1' && $Month <= 10 && $Year == '2024') {
    $sede = "Comercial Trina";
} elseif ($sede == 'Comercial Trina' && $Month > 10 && $Year == '2024') {
    $sede = "Sucursal Coro3";
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////    
if ($sede == 'Sucursal Coro2' && $Month <= 10 && $Year == '2024') {
    $sede = "Comercial Corina I";
} elseif ($sede == 'Comercial Corina I' && $Month > 10 && $Year == '2024') {
    $sede = "Sucursal Coro3";
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////             
if ($sede == 'Sucursal Coro3' && $Month <= 10 && $Year == '2024') {
    $sede = "Comercial Corina II";
} elseif ($sede == 'Comercial Corina II' && $Month > 10 && $Year == '2024') {
    $sede = "Sucursal Coro3";
}
