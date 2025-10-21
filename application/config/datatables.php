<?php
 if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Database table settings
|--------------------------------------------------------------------------
|
| Table names
|
*/

$config['proveedores']							='proveedores';
$config['usuarios']								='usuarios';
$config['productos']							='productos';
$config['clientes']								='clientes';
$config['clientes_contactos']					='clientes_contactos';

//Tablas de Cotizaciones ...
$config['cotizaciones']							='cotizaciones';
$config['cotiza_cancela']						='cotiza_cancela';
$config['cotiza_detalles_venta']				='cotiza_detalles_venta';
$config['cotiza_detalles_pedido']				='cotiza_detalles_pedido';
$config['cotiza_productos']						='cotiza_productos';
$config['cotiza_productos_pedidos']				='cotiza_productos_pedidos';
$config["facturas"]								="facturas";
$config["pagos"]								="pagos";
$config["documentos"]							="documentos";
$config["ensambles"]							="ensambles";
$config["ensamble_productos"]					="ensamble_productos";


//Compras de Proveedores

$config["historia_pagos_clientes"]				="historia_pagos_clientes";
$config["historia_pagos_proveedores"]			="historia_pagos_proveedores";
$config["mensajeria_proveedores"]				="mensajeria_proveedores";
$config["mensajeria_noguia_proveedor"]			="mensajeria_noguia_proveedor";
$config["mensajeria_clientes"]					="mensajeria_clientes";
$config["bancos"]								="bancos";
$config["productos_proveedores"]				="productos_proveedores";
$config['historia_inventario']					="historia_inventario";
$config['historia_inventario_proveedores']		="historia_inventario_proveedores";
$config['contactos_proveedores']				="contactos_proveedores";
$config['historiaProductosSerieProveedores']	="historiaproductosserieproveedores";
$config['clientes_contactos']					="clientes_contactos";//historiaProductosSerieProveedores

//Para PDF

#$config['FPDF_FONTPATH']=ROOTPATH."/application/libraries/font/";

/* End of file database_tables.php */
/* Location: ./application/config/database_tables.php */


?>
