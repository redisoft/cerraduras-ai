<div class="derecha">
<div class="submenu" style="height:30px">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<!--<div class="seccionDiv">
Reportes
</div>-->
</div>
<div class="listproyectos">

<?php

#echo intval(-1.99);
#VENTAS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/index/'.date('Y-m-01').'/'.date('Y-m-'.$this->configuracion->obtenerUltimaDiaFecha(date('Y-m-d'))).'\'"';
	
if($permisos[20]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Ventas</div>';

if(sistemaActivo=='olyess')
{
	#PEDIDOS
	#------------------------------------------------------------------------------------------------------------#
	$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/pedidos\'"';
		
	if($permisos[20]->activo==0)
	{
		$permiso=' class="contenedorReportesDesactivado" ';
	}
	
	echo '<div '.$permiso.'>Pedidos</div>';
}


#VENTAS CONTADORA
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/ventasContadora\'"';
	
if($permisos[20]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Ventas contadora</div>';


#COMPRAS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/reportesCompras\'"';

if($permisos[21]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Compras</div>';

#COBRANZA
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/cobranza\'"';

if($permisos[22]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Cobranza</div>';

#FACTURACIÓN
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/facturacion\'"';

if($permisos[23]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Facturación</div>';

/*#NÓMINA
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/nomina\'"';

if($permisos['leer'][16]==0 and $permisos['escribir'][16]==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Nómina</div>';
*/

#INGRESOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/ingresos\'"';

if($permisos[24]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Ingresos</div>';


#EGRESOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/gastos\'"';

if($permisos[25]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Egresos</div>';

#FLUJO DE EFECTIVO
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/flujoEfectivo\'"';

if($permisos[26]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Flujo efectivo</div>';


#AUXILIAR DE PROVEEDORES
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/auxiliarProveedores\'"';

if($permisos[27]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Auxiliar proveedores</div>';

#CAJA CHICA
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/cajaChica\'"';

if($permisos[28]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Caja chica</div>';


#FLUJO CAJA CHICA
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/flujoCajaChica\'"';

if($permisos[29]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Flujo caja chica</div>';


#PRONÓSTICO DE COBROS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/pronosticoIngresos\'"';

if($permisos[30]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Pronóstico de cobros</div>';


#PRONÓSTICO DE PAGOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/pronosticoGastos\'"';

if($permisos[31]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Pronóstico de pagos</div>';


#REPORTE DE INVENTARIOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/inventarios\'"';

if($permisos[32]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Inventario productos</div>';


#REPORTE DE MATERIA PRIMA !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! FALTA EL PERMISO
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/materiaPrima\'"';

if($permisos[52]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Inventario materia prima</div>';


#REPORTE DE PAGOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/pagos\'"';

if($permisos[33]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Pagos</div>';


#REPORTE DE MOBILIARIO
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/mobiliario\'"';

if($permisos[34]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Inventario mobiliario / equipo</div>';


#DEPÓSITOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/depositos\'"';

if($permisos[35]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Depósitos</div>';

#RETIROS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/retiros\'"';

if($permisos[36]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Retiros</div>';

#INGRESOS FACTURADOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/ingresosFacturados\'"';

if($permisos[37]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Ingresos facturados</div>';

#GASTOS FACTURADOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/gastosFacturados\'"';

if($permisos[38]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Gastos facturados</div>';

#RELACIÓN PROVEEDORES
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/relacionProveedores\'"';

if($permisos[39]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Relación proveedores</div>';

#RELACIÓN CLIENTES
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/relacionClientes\'"';

if($permisos[40]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Relación clientes</div>';

#UTILIDAD
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/utilidad\'"';

if($permisos[41]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Utilidad</div>';

/*#FACTURACIÓN SAT
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/facturacionSat\'"';

if($permisos[42]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Facturación Sat</div>';
*/
#HISTORIAL DE MOVIMIENTOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/historialMovimientos\'"';

if($permisos[53]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Historial de movimientos</div>';

#VENTA DE SERVICIOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/ventaServicios\'"';

if($permisos[60]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Venta de servicios</div>';

#VENTA DE SERVICIOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/recursosHumanos\'"';

if($permisos[59]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Recursos humanos</div>';

#CHECADOR
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/checador\'"';

if($permisos[59]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Checador</div>';
	
#ENVÍOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/envios\'"';

if($permisos[59]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Envíos</div>';
	
#CORTE DIARIO
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/corteDiario\'"';

if($permisos[59]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Corte diario</div>';
	
	#CORTE DIARIO
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/pagosCredito\'"';

if($permisos[59]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Pago créditos</div>';

#CORTE DIARIO
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/prefacturas\'"';

if($permisos[59]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Remisión/Prefactura</div>';
	
#CAJA
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/caja\'"';

if($permisos[65]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Caja</div>';

#CAJA
#------------------------------------------------------------------------------------------------------------#
$permiso=' class="contenedorReportes" onclick="window.location.href=\''.base_url().'reportes/precio1\'"';

if($permisos[66]->activo==0)
{
	$permiso=' class="contenedorReportesDesactivado" ';
}

echo '<div '.$permiso.'>Precio 1</div>';
?>




</div>
</div>
