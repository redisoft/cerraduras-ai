<script type="text/javascript" src="<?php echo base_url()?>js/fichaConfiguracion.js"></script>
<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar">
    <div class="seccionDiv">
    Tiendas
    </div>

   <table class="toolbar" width="100%">
        <tr>
            <td width="5%" align="center" valign="middle"><a  href="<?php print(base_url()."configuracion/"); ?>" > <span class="icon-option" title="Configuración de Sistema"> <img src="<?php print(base_url()); ?>img/configure.png"  width="30" height="30" border="0" title="Configuración de Sistema" /> </span> Sistema </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/listauser"); ?>" > <span class="icon-option" title="Lista de usuarios"> <img src="<?php print(base_url()); ?>img/clientes.png"   width="30" height="30" title="Lista de usuarios" /></span> Usuarios </a> </td>
            <!--td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/adduser"); ?>" > <span class="icon-option" title="Añadir nuevo usuario"> <img src="<?php print(base_url()); ?>img/nuevo.png"   width="30" height="30" title="Añadir nuevo usuario" /></span> Nuevo usuario </a> </td-->
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/roles"); ?>" > <span class="icon-option" title="Añadir nuevo usuario"> <img src="<?php print(base_url()); ?>img/roles.png"   width="30" height="30" title="Roles" /></span> Roles </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos"); ?>" > <span class="icon-option" title="Banco"> <img src="<?php print(base_url()); ?>img/banco.png"   width="30" height="30" title="Banco" /></span> Banco </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos/cuentas"); ?>" > <span class="icon-option" title="Cuentas"> <img src="<?php print(base_url()); ?>img/dinero.png"   width="30" height="30" title="Cuentas" /></span> Cuentas </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/unidades"); ?>" > <span class="icon-option" title="Unidades"> <img src="<?php print(base_url()); ?>img/bascula.png"   width="30" height="30" title="Unidades" /></span> Unidades </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/zonas"); ?>" > <span class="icon-option" > <img src="<?php print(base_url()); ?>img/zona.png" width="30" height="30" title="<?php echo $this->session->userdata('identificador')?>" /></span> <?php echo $this->session->userdata('identificador')?> </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/facturacion"); ?>"><span class="icon-option" title="FEL"><img src="<?php print(base_url()); ?>img/fel.png"  width="30" height="34" title="Facturación electronica"/></span>Emisores</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/vigilancia"); ?>" ><span class="icon-option" title="Vigilancia"><img src="<?php print(base_url()); ?>img/camara.png"  width="30" height="34" title="Vigilancia"/></span>Vigilancia</a></td>
            <!--td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/tiendas"); ?>" ><span class="icon-option" title="Vigilancia"><img src="<?php print(base_url()); ?>img/tienda.png"  width="30" height="34" title="Tiendas" alt="Vigilancia" /></span>Tiendas</a></td-->
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
        </tr>
    </table>
  </div>
</div>
 <table style=" margin-left:1100px;" class="toolbar" width="10%">
    <tr>
      <td style="border:none" width="27%" align="center" valign="middle" class="button">
       <?php
		if($permiso->escribir=='1')
		{
			?>
          	<span class="icon-option" id="agregarTienda" title="Añadir tienda" style="cursor:pointer">
           <img src="<?php print(base_url()); ?>img/add.png" border="0" title="Añadir tienda" /> 
           </span>Agregar
           <?php
		}
       ?>
       </td>
       
    </tr>
  </table>

 <?php

if(!empty ($tiendas))
{
	?>
	<table width="100%" class="admintable">
    <thead>
     <tr >
        <th class="encabezadoPrincipal" width="10%" align="center" valign="middle">#</th>
        <th class="encabezadoPrincipal" width="20%" align="center">Nombre</th>
        <th class="encabezadoPrincipal" width="20%" align="center">Dirección</th>
        <th class="encabezadoPrincipal" width="" align="center">Número</th>
        <th class="encabezadoPrincipal" width="%" align="center">Colonia</th>
        <th class="encabezadoPrincipal" width="" align="center">Ciudad</th>
        <th class="encabezadoPrincipal" width="15%" align="center">Titular</th>
	    <th class="encabezadoPrincipal" width="10%" align="center">Acciones</th>
     </tr>
    </thead>
   <tbody>

<?php
  $i=1;
foreach ($tiendas as $row)
{
	$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
	?>
	<tr <?php echo $estilo?>>
	<td align="center"> <?php echo $i ?> </td>
	<td align="center" valign="middle"><?php echo $row->nombre ?></td>
	<td align="center" valign="middle"><?php echo $row->direccion ?></td>
	<td align="center" valign="middle"><?php echo $row->numero ?></td>
	<td align="center" valign="middle"><?php echo $row->colonia ?></td>
	<td align="center" valign="middle"><?php echo $row->ciudad ?></td>
	<td align="center" valign="middle"><?php echo $row->nombre.' '.$row->apellidoPaterno.' '.$row->apellidoMaterno ?></td>
	<td align="center" valign="middle">
	 <?php
	if($permiso->escribir=='1')
	{
		?>
        <a href="<?php echo base_url()?>configuracion/borrarTienda/<?php echo $row->idTienda ?>">
        <img src="<?php echo base_url()?>img/bin_empty.png" width="22" height="22" border="0" title="Eliminar" 
        onClick="return confirm('Esta seguro de borrar esta tienda')" ></a><br />
        
        <a>Borrar</a>
        <?php
	}
?>
</td>
</tr>

<?php

$i++;

}//Foreach
?>
   </tbody>
 </table>

<?php
}else
{
    print('<div class="Error_validar" style="margin-top:2px; width:90%; margin-bottom: 5px;">
           Sin registro de tiendas.
           </div>');
}
?>

<div id="ventanaAgregarTienda" title="Tiendas:">
<div style="width:99%;" id="registrandoTienda"></div>
<div id="ErrorRegistrarTienda" class="ui-state-error" ></div>
<table class="admintable" width="99%;">
    <tr>
        <td class="key">Nombre:</td>
        <td>
        	<input name="txtNombreTienda" id="txtNombreTienda" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>
     <tr>
        <td class="key">Dirección:</td>
        <td>
        	<textarea id="txtDireccion" name="txtDireccion" class="TextArea" style="width:250px"></textarea>
        </td>
    </tr>	
     <tr>
        <td class="key">Numero:</td>
        <td>
        	<input name="txtNumero" id="txtNumero" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>	
      <tr>
        <td class="key">Colonia:</td>
        <td>
        	<input name="txtColonia" id="txtColonia" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>	
      <tr>
        <td class="key">Codigo postal:</td>
        <td>
        	<input name="txtCodigoPostal" id="txtCodigoPostal" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>	
      <tr>
        <td class="key">Ciudad:</td>
        <td>
        	<input name="txtCiudad" id="txtCiudad" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>	
      <tr>
        <td class="key">Telefono:</td>
        <td>
        	<input name="txtTelefono" id="txtTelefono" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>
    <tr>
    	<th colspan="2">Encargado de la tienda</th>
    </tr>
      <tr>
        <td class="key">Nombre:</td>
        <td>
        	<input name="txtNombre" id="txtNombre" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>	
     <tr>
        <td class="key">Apellido paterno:</td>
        <td>
        	<input name="txtPaterno" id="txtPaterno" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>		
     <tr>
        <td class="key">Apellido materno:</td>
        <td>
        	<input name="txtMaterno" id="txtMaterno" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>
     <tr>
        <td class="key">Usuario:</td>
        <td>
        	<input name="txtUsuario" id="txtUsuario" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>	
     <tr>
        <td class="key">Contraseña:</td>
        <td>
        	<input name="txtPassword" id="txtPassword" type="password" class="cajas" style="width:250px" />
        </td>
    </tr>	
    
      <tr>
        <td class="key">Email:</td>
        <td>
        	<input name="txtEmail" id="txtEmail" type="text" class="cajas" style="width:250px" />
        </td>
    </tr>					
</table>
</div>

</div>
