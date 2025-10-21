<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/configuracion/unidades/unidades.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/configuracion/unidades/conversiones.js"></script>
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
    <!--<div class="seccionDiv">
    	Unidades
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
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/unidades"); ?>" class="escalaGrisesConfiguracion"> <span class="icon-option" title="Unidades"> <img src="<?php print(base_url()); ?>img/bascula.png"   width="30" height="30" title="Unidades" /></span> Unida. </a> </td>
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

 <table class="toolbar" width="10%">
    <tr>
      <td style="border:none" align="center" valign="middle" class="button">
        <a id="agregarUnidades" onclick="formularioUnidades()" title="Agregar unidad" >
            <img src="<?php print(base_url()); ?>img/add.png" alt="a" border="0" title="Añadir unidad" /> 
            <br />
            Agregar
        </a>
           
       <?php
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'agregarUnidades\');
			</script>';
		}
       ?>
       </td>
      
    </tr>
  </table>

 <?php

if(!empty ($unidades))
{

?>
<table width="100%" class="admintable" >
     <tr >
        <th class="encabezadoPrincipal" width="10%" align="center" valign="middle">#</th>
        <th class="encabezadoPrincipal" width="50%" align="center">Descripcion</th>
	    <th class="encabezadoPrincipal" width="11%" align="center">Acciones</th>
     </tr>
<?php
$i=1;
foreach ($unidades as $row)
{
	$estilo	=$i%2>0?'class="sinSombra"':'class="sombreado"';
	
	?>
	<tr <?php echo $estilo?>>
	<td align="center"> <?php echo $i ?> </td>
	<td align="center" valign="middle"><?php echo $row->descripcion ?></td>
	<td align="left" valign="middle">

        &nbsp;
        <img id="btnEditarUnidad<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Conversiones" onClick="accesoEditarUnidad('<?php echo $row->idUnidad?>')" >
        &nbsp;&nbsp;
        <img id="btnBorrarUnidad<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar unidad" onClick="borrarUnidad(<?php echo $row->idUnidad  ?>,'¿Realmente desea borrar esta unidad?')" ></a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <img id="btnConversiones<?php echo $i?>" src="<?php echo base_url()?>img/bascula.png" width="22" height="22" title="Conversiones" onClick="obtenerConversiones('<?php echo $row->idUnidad?>')" >
        <br />
        
        <a id="a-btnEditarUnidad<?php echo $i?>">Editar</a>
        <a id="a-btnBorrarUnidad<?php echo $i?>">Borrar</a>
        <a id="a-btnConversiones<?php echo $i?>">Conversiones</a>
        
        <?php
		
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnConversiones'.$i.'\');
			</script>';
		}
		
		if($permiso[2]->activo==0 or $row->sistema=='1')
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnEditarUnidad'.$i.'\');
			</script>';
		}
		
		if($permiso[3]->activo==0 or $row->sistema=='1')
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnBorrarUnidad'.$i.'\');
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
	<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}
?>

<div id="ventanaEditarUnidad" title="Editar unidad">
<div id="editandoUnidad"></div>
<div id="errorEditarUnidad" class="ui-state-error" ></div>
<div id="obtenerUnidad"></div>
</div>

<div id="ventanaConversiones" title="Conversiones entre unidades">
<div style="width:99%;" id="cargandoConversiones"></div>
<div id="errorConversion" class="ui-state-error" ></div>
<div id="cargarConversiones"></div>
</div>

<div id="ventanaUnidades" title="Unidades">
<div id="registrandoUnidades"></div>
<div id="Error-Unidades" class="ui-state-error" ></div>
<script>
$(document).ready(function()
{
	$("#txtNombreUnidad").autocomplete(
	{
		source:base_url+'configuracion/obtenerUnidadesRepetidas',
		
		select:function( event, ui)
		{
			notify("La unidad ya esta registrada",500,5000,"error",5,5);
			document.getElementById("txtNombreUnidad").reset();
		}
	});
});
</script>
<table class="admintable" width="100%">
    <tr>
    <td class="key">Descripcion:</td>
    <td>
        <input name="txtNombreUnidad" id="txtNombreUnidad" type="text" class="cajas" style="width:300px"  />
    </td>
    </tr>	
</table>
</div>

<div id="ventanaEditarConversion" title="Editar conversión">
<div id="editandoConversion"></div>
<div class="ui-state-error" ></div>
<div id="obtenerConversion"></div>
</div>

</div>
