<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/lineas.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/configuracion/lineas/sublineas.js"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
    <!--<div class="seccionDiv">
    	Líneas
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
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/catalogosContables"); ?>" ><span class="icon-option" title="Catálogos contables"><img src="<?php print(base_url()); ?>img/engranes.png"  width="30" height="34" title="Catálogos contables"  /></span>Cat. Cont.</a></td> 
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/lineas"); ?>" class="escalaGrisesConfiguracion" ><span class="icon-option" title="Líneas"><img src="<?php print(base_url()); ?>img/lineas.png"  width="30" height="34" title="Lineas"  /></span>Líneas</a></td>      
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
 <table class="toolbar" width="10%">
    <tr>
      <td style="border:none" width="27%" align="center" valign="middle" class="button">
        <a id="btnLineas" onclick="formularioLineas()" title="Agregar línea" style="cursor:pointer">
            <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Añadir línea" /> 
            <br />
            Agregar
        </a>
           
       <?php
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnLineas\');
			</script>';
		}
       ?>
       </td>
      
    </tr>
  </table>

<?php
if(!empty ($lineas))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<td colspan="4" class="sinBorde">
				<ul class="menuTabs">
					<li class="activado sinMargen">Líneas</li>
					<li class="sinMargen" onclick="window.location.href=\''.base_url().'catalogos/departamentos\'">Departamentos</li>
					
					<li class="sinMargen" onclick="window.location.href=\''.base_url().'catalogos/marcas\'">Marcas</li>
				</ul>
			</td>
		</tr>
		
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="50%" align="center">Nombre</th>
			<th class="encabezadoPrincipal" align="center">Imagen</th>
			<th class="encabezadoPrincipal" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=1;
	foreach ($lineas as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
		<tr <?php echo $estilo?>>
		<td align="center"> <?php echo $i?> </td>
		<td align="center" valign="middle"><?php echo $row->nombre ?></td>
        <td align="center" valign="middle" class="imagenesLinea">
		<?php 
		if(file_exists(carpetaProductos.$row->imagen) and strlen($row->imagen)>4)
		{
			echo '<img src="'.base_url().carpetaProductos.$row->imagen.'" />';
		}
		else
		{
			echo '<img src="'.base_url().carpetaProductos.'default.png" />';
		}
		?>
        </td>
		<td align="center" valign="middle">
        	
            
			<?php
			
			echo '
			&nbsp;&nbsp;
        	
            '.(sistemaActivo=='olyess'?'<img id="btnSubLineas'.$i.'" src="'.base_url().'img/sublinea.png" width="22" height="22" title="Sublineas" onClick="obtenerSubLineas('.$row->idLinea.')" >
            &nbsp;&nbsp;&nbsp;':'').'
            
        	<img id="btnEditarLinea'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" title="Editar" onClick="accesoEditarLinea('.$row->idLinea.')" >
            &nbsp;&nbsp;
           
			<img id="btnBorrarLinea'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" title="Borrar" onClick="borrarLinea('.$row->idLinea.',\'¿Realmente desea borrar esta línea?\')" >
            <br />
           '.(sistemaActivo=='olyess'?' <a id="a-btnSubLineas'.$i.'">Sublineas</a>':'').'
			<a id="a-btnEditarLinea'.$i.'">Editar</a>
            <a id="a-btnBorrarLinea'.$i.'">Borrar</a>';
			
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnSubLineas'.$i.'\');
				</script>';
			}
		
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarLinea'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarLinea'.$i.'\');
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
	No se encontraron registros.
	</div>';
}
?>

<div id="ventanaEditarLinea" title="Editar línea">
    <div id="editandoLinea"></div>
    <div id="errorEditarLinea" class="ui-state-error" ></div>
    <div id="obtenerLinea"></div>
</div>

<div id="ventanaLineas" title="Líneas">
    <div id="registrandoLinea"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioLineas"></div>
</div>

<div id="ventanaSubLineas" title="Sublineas">
    <div id="procesandoSubLineas"></div>
    <div id="obtenerSubLineas"></div>
</div>

<div id="ventanaFormularioSubLineas" title="Registrar sublineas">
    <div id="registrandoSubLinea"></div>
    <div id="formularioSubLineas"></div>
</div>

<div id="ventanaEditarSubLinea" title="Editar sublineas">
    <div id="editandoSubLinea"></div>
    <div id="obtenerSubLinea"></div>
</div>

</div>
</div>




