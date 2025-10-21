<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/configuracion/catalogosContables.js"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
    <!--<div class="seccionDiv">
    	Catálogos contables
    </div>-->

    <table class="toolbar" width="100%">
        <tr>
            <td width="5%" align="center" valign="middle"><a  href="<?php print(base_url()."configuracion/"); ?>" > <span class="icon-option" title="Configuración de Sistema"> <img src="<?php print(base_url()); ?>img/configure.png"  width="30" height="30" border="0" title="Configuración de Sistema" /> </span> Sistema </a> </td>
            <?php
            if($this->session->userdata('idLicencia')=='1')
			{
				?>
                <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/listauser"); ?>" > <span class="icon-option" title="Lista de usuarios"> <img src="<?php print(base_url()); ?>img/clientes.png"   width="30" height="30" title="Lista de usuarios" /></span> Usuarios </a> </td>
                <?php
			}
			?>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/roles"); ?>" > <span class="icon-option" title="Añadir nuevo usuario"> <img src="<?php print(base_url()); ?>img/roles.png"   width="30" height="30" title="Roles" /></span> Roles </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos"); ?>" > <span class="icon-option" title="Banco"> <img src="<?php print(base_url()); ?>img/banco.png"   width="30" height="30" title="Banco" /></span> Banco </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos/cuentas"); ?>" > <span class="icon-option" title="Cuentas"> <img src="<?php print(base_url()); ?>img/dinero.png"   width="30" height="30" title="Cuentas" /></span> Cuentas </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/unidades"); ?>" > <span class="icon-option" title="Unidades"> <img src="<?php print(base_url()); ?>img/bascula.png"   width="30" height="30" title="Unidades" /></span> Unida. </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/zonas"); ?>" > <span class="icon-option" > <img src="<?php print(base_url()); ?>img/zona.png" width="30" height="30" title="<?php echo $this->session->userdata('identificador')?>" /></span> <?php echo $this->session->userdata('identificador')?> </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/facturacion"); ?>"><span class="icon-option" title="FEL"><img src="<?php print(base_url()); ?>img/fel.png"  width="30" height="34" title="Facturación electronica"/></span>Emiso.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/impuestos"); ?>" ><span class="icon-option" title="Impuestos"><img src="<?php print(base_url()); ?>img/impuestos.png"  width="30" height="34" title="Impuestos"/></span>Impuestos</a></td>
             <?php
            if(sistemaActivo=='IEXE')
			{
				?>
                <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/programasComisiones"); ?>" ><span class="icon-option" title="Comisiones"><img src="<?php print(base_url()); ?>img/comisiones.png"  style="max-width: 30px; max-height: 30px" title="Comisiones"  /></span>Comisiones</a></td>
                <?php
			}
			?>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/procesos"); ?>" ><span class="icon-option" title="Estilo"><img src="<?php print(base_url()); ?>img/produccion.png"  width="30" height="34" title="Procesos"  /></span>Procesos</a></td>
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/divisas"); ?>" ><span class="icon-option" title="Divisas"><img src="<?php print(base_url()); ?>img/divisas.jpg"  width="30" height="34" title="Divisas"  /></span>Divisas</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/catalogosContables"); ?>" class="escalaGrisesConfiguracion" ><span class="icon-option" title="Catálogos contables"><img src="<?php print(base_url()); ?>img/engranes.png"  width="30" height="34" title="Catálogos contables"  /></span>Cat. Cont.</a></td> 
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/lineas"); ?>" ><span class="icon-option" title="Líneas"><img src="<?php print(base_url()); ?>img/lineas.png"  width="30" height="34" title="Lineas"  /></span>Líneas</a></td>      
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/servicios"); ?>" ><span class="icon-option" title="Servicios"><img src="<?php print(base_url()); ?>img/servicios.png"  width="30" height="34" title="Servicios"  /></span>Servi.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/formasPago"); ?>" ><span class="icon-option" title="Formas de pago"><img src="<?php print(base_url()); ?>img/formas.png"  width="30" height="34" /></span>F. Pago</a></td>
            <!--td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."tiendas"); ?>" ><span class="icon-option" title="Tiendas"><img src="<?php print(base_url()); ?>img/tienda.png"  width="30" height="34" title="Tiendas"  /></span>Tiendas</a></td-->
<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/categorias"); ?>" ><span class="icon-option" title="Categorías"><img src="<?php print(base_url()); ?>img/categorias.png"  width="30" height="34" title="Categorías"  /></span>Categorías</a></td>
<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."estaciones"); ?>" ><span class="icon-option" title="Estaciones"><img src="<?php print(base_url()); ?>img/estaciones.png"  width="30" height="34" title="Estaciones"  /></span>Estaciones</a></td>           
        </tr>
    </table>
  </div>
</div>

<div class="listproyectos" >


<?php

//PARA LOS DEPARTAMENTOS
echo '
<table class="toolbar" width="10%">
	<tr>
		<td style="border:none" width="27%" align="center" valign="middle" class="button">
		
			<a id="btnRegistrarDepartamento" onclick="formularioDepartamentos()" title="Agregar departamento" style="cursor:pointer">
				<img src="'.base_url().'img/add.png" border="0" /> <br />
				Agregar
			</a>';

			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarDepartamento\');
				</script>';
			}
		
		echo'
		</td>
	</tr>
</table>';

if(!empty ($departamentos))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" colspan="4">Departamentos</th>
		</tr>
		<tr >
			<th class="" width="3%" align="center" valign="middle">#</th>
			<th class="" width="50%" align="center">Nombre</th>
			<th class="" align="center">Tipo</th>
			<th class="" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=1;
	foreach ($departamentos as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
		<tr <?php echo $estilo?>>
		<td align="center"> <?php echo $i?> </td>
		<td align="center" valign="middle"><?php echo $row->nombre ?></td>
        <td align="center" valign="middle"><?php echo obtenerTipoCatalogo($row->tipo) ?></td>
		<td align="center" valign="middle">
        	<img id="btnEditarDepartamento<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" border="0" title="Departamentos" onClick="accesoEditarDepartamentoConfiguracion('<?php echo $row->idDepartamento?>')" >
            
            &nbsp;&nbsp;
          
			<img id="btnBorrarDepartamento<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" border="0" title="Borrar" onClick="borrarDepartamento(<?php echo $row->idDepartamento?>,'¿Realmente desea borrar este departamento?')" >
            <br />
			<a id="a-btnEditarDepartamento<?php echo $i?>">Editar</a>
            <a id="a-btnBorrarDepartamento<?php echo $i?>">Borrar</a>
            
		<?php
		
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarDepartamento'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarDepartamento'.$i.'\');
				</script>';
			}
		?>
		</td>
		</tr>
		
		<?php
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros de departamentos
	</div>';
}

//PARA LOS PRODUCTOS
echo '
<table class="toolbar" width="10%">
	<tr>
		<td style="border:none" width="27%" align="center" valign="middle" class="button">';

			echo'
			<a id="btnRegistrarProducto" onclick="formularioProductos()" title="Agregar producto" style="cursor:pointer">
				<img src="'.base_url().'img/add.png" border="0" /> <br />
				Agregar
			</a>';
		
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarProducto\');
				</script>';
			}
		
		echo'
		</td>
	</tr>
</table>';

if(!empty ($productos))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" colspan="4">Conceptos</th>
		</tr>
		<tr >
			<th class="" width="3%" align="center" valign="middle">#</th>
			<th class="" width="50%" align="center">Nombre</th>
			<th class="" align="center">Tipo</th>
			<th class="" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=1;
	foreach ($productos as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
		<tr <?php echo $estilo?>>
		<td align="center"> <?php echo $i?> </td>
		<td align="center" valign="middle"><?php echo $row->nombre ?></td>
        <td align="center" valign="middle"><?php echo obtenerTipoCatalogo($row->tipo) ?></td>
		<td align="center" valign="middle">
        	<img id="btnEditarProducto<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" onClick="accesoEditarProductoConfiguracion('<?php echo $row->idProducto?>')" >
            
            &nbsp;&nbsp;
			<img id="btnBorrarProducto<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" border="0" title="Borrar" onClick="borrarProductoAdministracion(<?php echo $row->idProducto?>,'¿Realmente desea borrar este producto?')" >
            <br />
			<a id="a-btnEditarProducto<?php echo $i?>">Editar</a>
            <a id="a-btnBorrarProducto<?php echo $i?>">Borrar</a>
	
    	<?php
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarProducto'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0 or $row->sistema=='1')
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarProducto'.$i.'\');
				</script>';
			}
		?>
		</td>
		</tr>
		
		<?php
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
	No se encontraron registros de productos
	</div>';
}

//PARA LOS GASTOS
echo '
<table class="toolbar" width="10%">
	<tr>
		<td style="border:none" width="27%" align="center" valign="middle" class="button">
		
			<a id="btnRegistrarGasto" onclick="formularioGastos()" title="Agregar gasto" style="cursor:pointer">
				<img src="'.base_url().'img/add.png" border="0" /> <br />
				Agregar
			</a>';
		
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarGasto\');
				</script>';
			}
		
		echo'
		</td>
	</tr>
</table>';

if(!empty ($gastos))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" colspan="4">Tipos de gastos</th>
		</tr>
		<tr >
			<th class="" width="3%" align="center" valign="middle">#</th>
			<th class="" width="50%" align="center">Nombre</th>
			<th class=""  align="center">Tipo</th>
			<th class="" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=1;
	foreach ($gastos as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
		<tr <?php echo $estilo?>>
		<td align="center"> <?php echo $i?> </td>
		<td align="center" valign="middle"><?php echo $row->nombre ?></td>
        <td align="center" valign="middle"><?php echo obtenerTipoCatalogo($row->tipo) ?></td>
		<td align="center" valign="middle">

			<img id="btnEditarGasto<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" border="0" onClick="accesoEditarTipoConfiguracion('<?php echo $row->idGasto?>')" >
            
            &nbsp;&nbsp;
			<img id="btnBorrarGasto<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar" onClick="borrarTipoGasto(<?php echo $row->idGasto?>,'¿Realmente desea borrar este tipo de gasto?')" >
            <br />
			<a id="a-btnEditarGasto<?php echo $i?>">Editar</a>
            <a id="a-btnBorrarGasto<?php echo $i?>">Borrar</a>
			
			<?php
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarGasto'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarGasto'.$i.'\');
				</script>';
			}
		?>
		</td>
		</tr>
		
		<?php
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
	No se encontraron registros de tipos de gastos
	</div>';
}

//PARA LOS NOMBRES
echo '
<table class="toolbar" width="10%">
	<tr>
		<td style="border:none" width="27%" align="center" valign="middle" class="button">
		
			<a id="btnRegistrarNombre" onclick="formularioNombres()" title="Agregar nombre" style="cursor:pointer">
				<img src="'.base_url().'img/add.png" border="0" /> <br />
				Agregar
			</a>';
		
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarNombre\');
				</script>';
			}
			
		echo'
		</td>
	</tr>
</table>';

if(!empty ($nombres))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" colspan="3">Nombres</th>
		</tr>
		<tr >
			<th class="" width="3%" align="center" valign="middle">#</th>
			<th class="" width="50%" align="center">Nombre</th>
			<th class="" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=1;
	foreach ($nombres as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
		<tr <?php echo $estilo?>>
		<td align="center"> <?php echo $i?> </td>
		<td align="center" valign="middle"><?php echo $row->nombre ?></td>
		<td align="center" valign="middle">
        
        	<img id="btnEditarNombre<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" border="0" onClick="accesoEditarNombreConfiguracion('<?php echo $row->idNombre?>')" >
            
            &nbsp;&nbsp;
			<img id="btnBorrarNombre<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" border="0" title="Borrar" onClick="borrarNombre(<?php echo $row->idNombre?>,'¿Realmente desea borrar este nombre?')" >
            <br />
			<a id="a-btnEditarNombre<?php echo $i?>">Editar</a>
            <a id="a-btnBorrarNombre<?php echo $i?>">Borrar</a>
			<?php
            
            if($permiso[2]->activo==0)
            {
                 echo '
                <script>
                    desactivarBotonSistema(\'btnEditarNombre'.$i.'\');
                </script>';
            }
            
            if($permiso[3]->activo==0)
            {
                 echo '
                <script>
                    desactivarBotonSistema(\'btnBorrarNombre'.$i.'\');
                </script>';
            }
		?>
		</td>
		</tr>
		
		<?php
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros de nombres
	</div>';
}
?>



<div id="ventanaEditarDepartamento" title="Editar departamento">
<div id="editandoDepartamento"></div>
<div class="ui-state-error" ></div>
<div id="obtenerDepartamento"></div>
</div>

<div id="ventanaDepartamentos" title="Departamentos:">
<div id="registrandoDepartamento"></div>
<div  class="ui-state-error" ></div>
<div id="formularioDepartamentos"></div>
</div>

<div id="ventanaEditarProducto" title="Editar producto">
<div id="editandoProducto"></div>
<div class="ui-state-error" ></div>
<div id="obtenerProducto"></div>
</div>

<div id="ventanaProductos" title="Productos:">
<div id="registrandoProducto"></div>
<div  class="ui-state-error" ></div>
<div id="formularioProductos"></div>
</div>

<div id="ventanaEditarGasto" title="Editar gasto">
<div id="editandoGasto"></div>
<div class="ui-state-error" ></div>
<div id="obtenerGasto"></div>
</div>

<div id="ventanaGastos" title="Gastos:">
<div id="registrandoGasto"></div>
<div  class="ui-state-error" ></div>
<div id="formularioGastos"></div>
</div>

<div id="ventanaEditarNombre" title="Editar nombre">
<div id="editandoNombre"></div>
<div class="ui-state-error" ></div>
<div id="obtenerNombre"></div>
</div>

<div id="ventanaNombres" title="Nombres:">
<div id="registrandoNombre"></div>
<div  class="ui-state-error" ></div>
<div id="formularioNombres"></div>
</div>



</div>
</div>




