<?php $anio=date("Y");?>
<div class="izquierda">

<div style="<?php echo $this->session->userdata('checador')=='1'?' display:none ':''?>" id="ulMenuPrincipal" >
<ul class="menu_color">
<?php
$produccion	=0;
$reportes	=0;
$recursos	=0;
$dibujarP	=0;
$dibujarRc	=0;
$dibujarRe	=0;



/*#DASHBOARD
#------------------------------------------------------------------------------------------------------------#
$permiso=' onclick="window.location.href=\''.base_url().'dashboard/'.'\'"';

if($permisos[0]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'<div class="col-md-1 margenResponsivoDerecho"><li '.$permiso.' id="menu-dashboard" ><div class="letras">Dashboard</div></li></div>';


$permiso=' onclick="window.location.href=\''.base_url().'principal/calendario/'.'\'"';

if($permisos[58]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'<div class="col-md-1 margenResponsivo"><li '.$permiso.' id="menu-calendario"><div class="letras">Calendario</div></li></div>'; 
*/

/*$permiso=' onclick="window.location.href=\''.base_url().'ventas/puntoVenta/0'.'\'"';

if($permisos[62]->activo==0)
{
	$permiso=' class="desactivado" ';
}


echo'<div class="col-md-2 margenResponsivoDerecho"><li '.$permiso.' id="menu-puntoVenta" ><div class="letras">Punto de venta</div></li></div>';*/

#PUNTO DE VENTA
#------------------------------------------------------------------------------------------------------------#
$permiso='';

if($permisos[62]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'
<div class="col-md-2 margenResponsivoDerecho">
	<li '.$permiso.' id="menu-listaClientes" ><div onclick="window.location.href=\''.base_url().'ventas/puntoVenta/0\'" class="letras">Punto de venta</div>
	<ul>';

	 //LISTA DE COTIZACIONES
	$permiso=' onclick="window.location.href=\''.base_url().'ventas/caja\'"';

	if($permisos[63]->activo==0)
	{
		$permiso=' class="desactivado" ';
	}
		
	echo'<li  '.$permiso.' id="menu-caja" style="margin-top:2vh; width: 31vh"><div class="letras">Caja</div></li>
	</ul>
</li>
</div>';

	 

#CLIENTES
#------------------------------------------------------------------------------------------------------------#
$permiso='';

if($permisos[1]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'
<div class="col-md-1 margenResponsivo">
	<li '.$permiso.' id="menu-listaClientes" ><div onclick="window.location.href=\''.base_url().'clientes\'" class="'.(sistemaActivo=='IEXE'?'letrasMenu':'letras').'">'.(sistemaActivo=='IEXE'?'Alumnos / <br /> Clientes':'Clientes').'</div>
	<ul>';
	
	
	if(sistemaActivo=='IEXE')
	{
		//prospectos
		$permiso=' onclick="window.location.href=\''.base_url().'clientes/prospectos\'"';
	
		if($permisos[61]->activo==0)
		{
			$permiso=' class="desactivado" ';
		}
			
		echo'<li  '.$permiso.' id="menu-cotizaciones" style="margin-top:2vh;"><div class="letras">Prospectos</div></li>';
	}
	
	

	//LISTA DE COTIZACIONES
	$permiso=' onclick="window.location.href=\''.base_url().'cotizaciones\'"';

	if($permisos[2]->activo==0)
	{
		$permiso=' class="desactivado" ';
	}
		
	echo'<li  '.$permiso.' id="menu-cotizaciones" style="'.(sistemaActivo!='IEXE'?'margin-top:2vh;':'').' "><div class="letras">Cotizaciones</div></li>';
	
	
	//LISTA DE COTIZACIONES
	$permiso=' onclick="window.location.href=\''.base_url().'ventas\'"';

	if($permisos[4]->activo==0)
	{
		$permiso=' class="desactivado" ';
	}
		
	echo'<li  '.$permiso.' id="menu-ventasMenu" ><div class="letras">Ventas</div></li>';
	 
	$permiso=' onclick="window.location.href=\''.base_url().'reportes/envios\'"';

	if($permisos[64]->activo==0)
	{
		$permiso=' class="desactivado" ';
	}
		
	 
	echo'<li  '.$permiso.' id="menu-envios" ><div class="letras">Envíos</div></li>';
	
	/*//LISTA DE LLAMADAS
	$permiso=' onclick="window.location.href=\''.base_url().'cotizaciones/llamadas\'"';

	if($permisos[3]->activo==0)
	{
		$permiso=' class="desactivado" ';
	}
		
	echo'<li  '.$permiso.' id="menu-llamadas" ><div class="letras">Seguimientos</div></li>';*/

echo'
	</ul>
</li>
</div>';

#PROVEEDORES
#------------------------------------------------------------------------------------------------------------#

$permiso='';

if($permisos[6]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'
<div class="col-md-1 margenResponsivo">
<li '.$permiso.' id="menu-listaProveedores" ><div onclick="window.location.href=\''.base_url().'proveedores\'" class="letras">Proveedores</div></li>
</div>';

#COMPRAS
#------------------------------------------------------------------------------------------------------------#
	$permiso='onclick="window.location.href=\''.base_url().'compras/productos\'"';

	if($permisos[10]->activo==0)
	{
		$permiso=' class="desactivado" ';
	}

	echo'
	<div class="col-md-1 margenResponsivo">
		<li '.$permiso.' id="menu-comprasProductos" '.$permiso.'><div class="letras">Compras</div></li>
	</div>';



#PRODUCTOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' onclick="window.location.href=\''.base_url().'inventarioProductos\'"';

if($permisos[14]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'
<div class="col-md-1 margenResponsivo">
	<li '.$permiso.' id="menu-inventarioProductos"><div class="letras">Inventario</div></li>
</div>';

#SERVICIOS
#------------------------------------------------------------------------------------------------------------#
$permiso=' onclick="window.location.href=\''.base_url().'inventarioProductos/servicios\'"';

if($permisos[14]->activo==0 and $permisos[15]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'
<div class="col-md-1 margenResponsivo">
	<li '.$permiso.' id="menu-servicios"><div class="letras">Servicios</div></li>
</div>';



#PRODUCCIÓN
#------------------------------------------------------------------------------------------------------------#
$permiso='';

if($permisos[17]->activo==0 and $permisos[18]->activo==0 and $permisos[19]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'
<div class="col-md-2 margenResponsivo">
<li '.$permiso.'><div class="letras">Producción</div>
		<ul>';

#MATERIA PRIMA
#------------------------------------------------------------------------------------------------------------#
$permiso=' onclick="window.location.href=\''.base_url().'materiales\'"';

if($permisos[17]->activo==0)
{
	$permiso=' class="desactivado" ';
}
echo'<li '.$permiso.' id="menu-materiales" style="margin-top:2vh"><div class="letras">Materia prima</div></li>';



#PRODUCCIÓN
#------------------------------------------------------------------------------------------------------------#
$permiso=' onclick="window.location.href=\''.base_url().'produccion\'"';

if($permisos[18]->activo==0)
{
	$permiso=' class="desactivado" ';
}
echo'<li '.$permiso.' id="menu-analisis"><div class="letras">Explosión de materiales</div></li>';

#ORDENES DE PRODUCCIÓN
#------------------------------------------------------------------------------------------------------------#
$permiso=' onclick="window.location.href=\''.base_url().'ordenes\'"';

if($permisos[19]->activo==0)
{
	$permiso=' class="desactivado" ';
}
echo'<li '.$permiso.' id="menu-ordenesProduccion"><div class="letras">Orden de producción</div></li>
	</ul>
	</li>
</div>';
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


	 


#ADMINISTRACIÓN
#------------------------------------------------------------------------------------------------------------#
$permiso='';

if($permisos[12]->activo==0 
and $permisos[13]->activo==0 

and $permisos[43]->activo==0
and $permisos[44]->activo==0
and $permisos[45]->activo==0
and $permisos[46]->activo==0
and $permisos[47]->activo==0
and $permisos[48]->activo==0)
{
	$permiso=' class="desactivado" ';
}

echo'
<div class="col-md-2 margenResponsivo">
<li '.$permiso.' id="" ><div class="letras">Administración</div>
	<ul>';

	#ADMINISTRACION
	#------------------------------------------------------------------------------------------------------------#
	$permiso=' onclick="window.location.href=\''.base_url().'produccion/gastos\'"';
	
	if($permisos[12]->activo==0 )
	{
		$permiso=' class="desactivado" ';
	}
	echo'<li  '.$permiso.' style="margin-top: 2vh" id="menu-gastos" ><div class="letras">Ingresos / Egresos</div></li>';
	
	
	
	
	
	#NOMINA
	#------------------------------------------------------------------------------------------------------------#
	$permiso='';
	
	if($permisos[43]->activo==0
	and $permisos[44]->activo==0
	and $permisos[45]->activo==0
	and $permisos[46]->activo==0
	and $permisos[47]->activo==0
	and $permisos[48]->activo==0)
	{
		$permiso=' class="desactivado" ';
	}
	
	echo'
	<li '.$permiso.' id="menu-nomina" ><div class="letras">Nómina</div>
		<ul>';
		
		#RECIBOS
		#------------------------------------------------------------------------------------------------------------#
		$permiso=' onclick="window.location.href=\''.base_url().'nomina\'"';
		
		if($permisos[43]->activo==0)
		{
			$permiso=' class="desactivado" ';
		}
		
		echo'<li '.$permiso.' id="menu-recibos" ><div class="letras">Recibos</div></li>';
		
		#EMPLEADOS
		#------------------------------------------------------------------------------------------------------------#
		$permiso=' onclick="window.location.href=\''.base_url().'nomina/empleados\'"';
		
		if($permisos[44]->activo==0)
		{
			$permiso=' class="desactivado" ';
		}
		
		echo'<li '.$permiso.' id="menu-empleados" ><div class="letras">Empleados</div></li>';
		
		#DEPARTAMENTOS
		#------------------------------------------------------------------------------------------------------------#
		$permiso=' onclick="window.location.href=\''.base_url().'nomina/departamentos\'"';
		
		if($permisos[45]->activo==0)
		{
			$permiso=' class="desactivado" ';
		}
		
		echo'<li '.$permiso.' id="menu-departamentos" ><div class="letras">Departamentos</div></li>';
		
		#PUESTOS
		#------------------------------------------------------------------------------------------------------------#
		$permiso=' onclick="window.location.href=\''.base_url().'nomina/puestos\'"';
		
		if($permisos[46]->activo==0)
		{
			$permiso=' class="desactivado" ';
		}
		
		echo'<li '.$permiso.' id="menu-puestos" ><div class="letras">Puestos</div></li>';
		
		#DEDUCCIONES
		#------------------------------------------------------------------------------------------------------------#
		$permiso=' onclick="window.location.href=\''.base_url().'nomina/deducciones\'"';
		
		if($permisos[47]->activo==0)
		{
			$permiso=' class="desactivado" ';
		}
		
		echo'<li '.$permiso.' id="menu-deducciones" ><div class="letras">Deducciones</div></li>';
		
		#PERCEPCIONES
		#------------------------------------------------------------------------------------------------------------#
		$permiso=' onclick="window.location.href=\''.base_url().'nomina/percepciones\'"';
		
		if($permisos[48]->activo==0)
		{
			$permiso=' class="desactivado" ';
		}
		
		echo'<li '.$permiso.' id="menu-percepciones" ><div class="letras">Percepciones</div></li>';
	
	echo'
		</ul>
	</li>';	
	
	#RECURSOS HUMANOS
	#------------------------------------------------------------------------------------------------------------#
	$permiso=' onclick="window.location.href=\''.base_url().'administracion/recursosHumanos\'"';
	
	if($permisos[54]->activo==0 )
	{
		$permiso=' class="desactivado" ';
	}
	echo'<li  '.$permiso.' id="menu-recursosHumanos" ><div class="letras">Recursos humanos</div></li>';
	
	#CATÁLOGO DE CUENTAS
	#------------------------------------------------------------------------------------------------------------#
	$permiso=' onclick="window.location.href=\''.base_url().'contabilidad\'"';
	
	if($permisos[13]->activo==0 )
	{
		$permiso=' class="desactivado" ';
	}
	
	echo'<li  '.$permiso.' id="menu-catalogoCuentas" ><div class="letras">Catálogo de cuentas</div></li>';
	
	#PÓLIZAS
	#------------------------------------------------------------------------------------------------------------#
	$permiso=' onclick="window.location.href=\''.base_url().'contabilidad/polizas\'"';
	
	if($permisos[13]->activo==0 )
	{
		$permiso=' class="desactivado" ';
	}
	
	echo'<li  '.$permiso.' id="menu-polizas" ><div class="letras">Pólizas</div></li>';
	
	#BALANZA DE COMPROBACIÓN
	#------------------------------------------------------------------------------------------------------------#
	$permiso=' onclick="window.location.href=\''.base_url().'contabilidad/balanza\'"';
	
	if($permisos[13]->activo==0 )
	{
		$permiso=' class="desactivado" ';
	}
	
	echo'<li  '.$permiso.' id="menu-balanza" ><div class="letras">Balanza de comprobación</div></li>';
	
	
	

echo'</ul>
</li>
</div>';




#REPORTES
#------------------------------------------------------------------------------------------------------------#
$permiso=' onclick="window.location.href=\''.base_url().'reportes/lista\'"';

if($permisos[20]->activo==0
and $permisos[21]->activo==0
and $permisos[22]->activo==0
and $permisos[23]->activo==0
and $permisos[24]->activo==0
and $permisos[25]->activo==0
and $permisos[26]->activo==0
and $permisos[27]->activo==0
and $permisos[28]->activo==0
and $permisos[29]->activo==0
and $permisos[30]->activo==0
and $permisos[31]->activo==0
and $permisos[32]->activo==0
and $permisos[33]->activo==0
and $permisos[34]->activo==0
and $permisos[35]->activo==0
and $permisos[36]->activo==0
and $permisos[37]->activo==0
and $permisos[38]->activo==0
and $permisos[39]->activo==0
and $permisos[40]->activo==0
and $permisos[41]->activo==0
and $permisos[42]->activo==0
and $permisos[52]->activo==0
and $permisos[53]->activo==0
and $permisos[59]->activo==0
and $permisos[60]->activo==0
)
{
	$permiso=' class="desactivado" ';
}
echo'
<div class="col-md-1 margenResponsivoIzquierdo"><li '.$permiso.'><div class="letras">Reportes</div> </li></div>';





?>

</ul>
</div>
</div>



