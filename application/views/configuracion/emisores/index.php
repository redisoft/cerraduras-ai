<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/emisores.js"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
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
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/facturacion"); ?>" class="escalaGrisesConfiguracion"><span class="icon-option" title="FEL"><img src="<?php print(base_url()); ?>img/fel.png"  width="30" height="26" title="Facturación electronica"/></span>Emiso.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/impuestos"); ?>" ><span class="icon-option" title="Impuestos"><img src="<?php print(base_url()); ?>img/impuestos.png"  width="30" height="30" title="Impuestos"/></span>Impuestos</a></td>
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
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/lineas"); ?>" ><span class="icon-option" title="Líneas"><img src="<?php print(base_url()); ?>img/lineas.png"  width="30" height="34" title="Lineas"  /></span>Líneas</a></td>      
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/servicios"); ?>" ><span class="icon-option" title="Servicios"><img src="<?php print(base_url()); ?>img/servicios.png"  width="30" height="34" title="Servicios"  /></span>Servi.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/formasPago"); ?>" ><span class="icon-option" title="Formas de pago"><img src="<?php print(base_url()); ?>img/formas.png"  width="30" height="34" /></span>F. Pago</a></td>
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/categorias"); ?>" ><span class="icon-option" title="Categorías"><img src="<?php print(base_url()); ?>img/categorias.png"  width="30" height="34" title="Categorías"  /></span>Categorías</a></td>
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."estaciones"); ?>" ><span class="icon-option" title="Estaciones"><img src="<?php print(base_url()); ?>img/estaciones.png"  width="30" height="34" title="Estaciones"  /></span>Estaciones</a></td>           
        </tr>
    </table>
  </div>
</div>

<table class="toolbar" width="100%">
    <tr>
        <td style="border:none" width="10%" align="center" valign="middle" class="button">
			<?php
		   	echo'
			<a id="btnRegistrarEmisor" onclick="formularioEmisores()">
				<img src="'.base_url().'img/add.png" title="Registrar emisor" /> <br />
				Registrar
			</a>';
			
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarEmisor\');
				</script>';
			}
			
            ?>
        </td>
    </tr>
</table>

<div class="listproyectos">

<?php
if(!empty ($emisores))
{
	?>
    <script>
	$(document).ready(function()
	{
		$("#tablaEmisores tr:even").addClass("sombreado");
		$("#tablaEmisores tr:odd").addClass("sinSombra");  
	});
	</script>
    <div id="procesandoInformacion"></div>
	
	<table width="100%" class="admintable">
		<tr>
			<th class="encabezadoPrincipal" colspan="2">Resumen de folios</th>
		</tr>
		<tr>
			<td class="key">Folios comprados:</td>		
			<td><?=$comprados?></td>		
		</tr>
		<tr>
			<td class="key">Folios consumidos:</td>		
			<td><?=$consumidos?></td>		
		</tr>
		<tr>
			<td class="key">Folios disponibles:</td>		
			<td><?=$comprados-$consumidos?></td>		
		</tr>
	</table>
	<table width="100%" class="admintable" id="tablaEmisores">
		 <tr >
			<th class="encabezadoPrincipal" width="2%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" align="center">Sucursal</th>
			<th class="encabezadoPrincipal" align="center">RFC</th>
            <th class="encabezadoPrincipal" align="center">Nombre</th>
            <th class="encabezadoPrincipal" align="center">Serie</th>
            <th class="encabezadoPrincipal" align="center">Certificado</th>
            <th class="encabezadoPrincipal" align="center">Fecha caducidad</th>
            
			<th class="encabezadoPrincipal" width="10%" align="center" valign="middle">Acciones</th>
		 </tr>

	<?php
	$i=1;
	foreach ($emisores as $row)
	{
		?>
		<tr>
            <td align="center"> <?php echo $i ?> </td>
            <td align="center" valign="middle"><?php echo $row->licencia?></td>
			<td align="center" valign="middle"><?php echo $row->rfc?></td>
            <td align="center" valign="middle"><?php echo $row->nombre?></td>
            <td align="center" valign="middle"><?php echo $row->serie?></td>
            <td align="center" valign="middle"><?php echo $row->numeroCertificado?></td>
            <td align="center" valign="middle"><?php echo $row->fechaCaducidad?></td>
            
           <td align="center" class="vinculos" valign="middle">
            
            <?php
				echo'
				<img id="btnEditarEmisor'.$i.'" onclick="accesoEditarEmisor('.$row->idEmisor.')" src="'.base_url().'img/editar.png" title="Editar emisores">
				&nbsp;
				<img id="btnBorrarEmisor'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar emisor '.$row->nombre.'" onclick="accesoBorrarEmisor('.$row->idEmisor.')" />
				<br />
				<a id="a-btnEditarEmisor'.$i.'">Editar</a>
				<a id="a-btnBorrarEmisor'.$i.'">Borrar</a>';
				#or $idLicencia!=$row->idLicencia
				if($permiso[2]->activo==0 )
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarEmisor'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0 or $idLicencia!=$row->idLicencia)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarEmisor'.$i.'\');
					</script>';
				}
            ?>
			</td>
		</tr>
		
		<?php
		
		$i++;
	}
	?>
	 </table>
	<?php
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:99%; margin-bottom: 5px;">
		Sin registro de emisores
	</div>';
}
?>


<div id="ventanaRegistrarEmisor" title="Registrar emisor">
<div id="registrandoEmisor"></div>
<div id="formularioEmisores"></div>
</div>

<div id="ventanaEditarEmisor" title="Editar emisor">
<div id="editandoEmisor"></div>
<div id="obtenerEmisor"></div>
</div>

</div>
</div>
