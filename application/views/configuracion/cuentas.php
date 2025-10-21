<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/fichaConfiguracion.js"></script>
<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar">
      <div class="seccionDiv">
    Cuentas contables
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
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/facturacion"); ?>"><span class="icon-option" title="FEL"><img src="<?php print(base_url()); ?>img/fel.png"  width="30" height="34" title="Facturación electronica"/></span>Facturación</a></td>
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
          <span class="icon-option" id="agregarCuentaContable" title="Agregar cuenta contable" style="cursor:pointer">
           <img src="<?php print(base_url()); ?>img/add.png" title="Agregar cuenta contable" /> 
           </span>Agregar
           <?php
		}
       ?>
       </td>
      
    </tr>
  </table>

 <?php

if(!empty ($cuentas))
{
	?>
	<table width="100%" class="admintable" >
		 <tr >
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
            <th class="encabezadoPrincipal" width="15%" align="center">Nivel 1/Clave</th>
            <th class="encabezadoPrincipal" width="15%" align="center">Nivel 2/Clave</th>
            <th class="encabezadoPrincipal" width="15%" align="center">Nivel 3/Clave</th>
            <th class="encabezadoPrincipal" width="15%" align="center">Nivel 4/Clave</th>
			<th class="encabezadoPrincipal" width="25%" align="center">Descripcion</th>
			<th class="encabezadoPrincipal" width="14%" align="center">Acciones</th>
		 </tr>
	<?php
	  $i=1;
	foreach ($cuentas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		?>
		<tr <?php echo $estilo?>>
		<td align="center"> <?php echo $i; ?> </td>
		<td align="center" valign="middle"><?php echo $row->nivel1.' / '.$row->clave1 ?></td>
        <td align="center" valign="middle"><?php echo $row->nivel2.' / '.$row->clave2 ?></td>
        <td align="center" valign="middle"><?php echo $row->nivel3.' / '.$row->clave3 ?></td>
        <td align="center" valign="middle"><?php echo $row->nivel4.' / '.$row->clave4 ?></td>
		<td align="center" valign="middle"><?php echo $row->nombre ?></td>
		<td align="center" valign="middle">
		 <?php
			
			if($row->idCuenta==1)
			{
				echo '<a>Sistema</a>';
			}
			else
			{
				if($permiso->escribir=='1')
				{
					?>
					
					<img id="btnCuentaContable<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" border="0" title="Eliminar" 
					onClick="obtenerCuentaContable('<?php echo $row->idCuenta?>')" >
					&nbsp;&nbsp;
					<a href="<?php echo base_url()?>configuracion/borrarCuentaContable/<?php echo $row->idCuenta ?>">
					<img src="<?php echo base_url()?>img/bin_empty.png" width="22" height="22" border="0" title="Eliminar" 
					onClick="return confirm('Esta seguro de borrar esta cuenta')" ></a>
					<br />
					<a>Editar</a>
					<a>Borrar</a>
					
					<?php
				}
			}
		?>
		</td>
		</tr>
		
		<?php
		
		$i++;
	}
	
	echo'</table>';
}
else
{
	print('<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
	Sin registros de cuentas contables.
	</div>');
}
?>

<div id="ventanaEditarCuentasContables" title="Cuentas contables">
<div style="width:99%;" id="editandoCuentas"></div>
<div id="errorEditarCuentas" class="ui-state-error" ></div>
<div id="cargarCuentaContable"></div>
</div>

<div id="ventanaCuentasContables" title="Cuentas contables">
<div style="width:99%;" id="registrandoCuentas"></div>
<div id="errorRegistrarCuentas" class="ui-state-error" ></div>
<table class="admintable" width="99%;">
	 <tr>
    	<td style="width:15%" class="key">Nivel 1:</td>
    	<td>
    		<input name="txtNivel1" id="txtNivel1" type="text" class="cajasSelect"  />
    	</td>
        <td style="width:15%" class="key">Clave:</td>
    	<td>
           <input name="txtClave1" id="txtClave1" type="text" class="cajasSelect"  />
    	</td>
    </tr>	
    
    <tr>
    	<td width="10%" class="key">Nivel 2:</td>
    	<td>
    		<input name="txtNivel2" id="txtNivel2" type="text" class="cajasSelect"  />
    	</td>
        <td  class="key">Clave:</td>
    	<td>
           <input name="txtClave2" id="txtClave2" type="text" class="cajasSelect"  />
    	</td>
    </tr>	
    
    <tr>
    	<td  width="10%" class="key">Nivel 3:</td>
    	<td>
    		<input name="txtNivel3" id="txtNivel3" type="text" class="cajasSelect"  />
    	</td>
        <td class="key">Clave:</td>
    	<td>
           <input name="txtClave3" id="txtClave3" type="text" class="cajasSelect"  />
    	</td>
    </tr>	
    
     <tr>
    	<td width="10%" class="key">Nivel 4:</td>
    	<td>
    		<input name="txtNivel4" id="txtNivel4" type="text" class="cajasSelect"  />
    	</td>
        <td class="key">Clave:</td>
    	<td>
           <input name="txtClave4" id="txtClave4" type="text" class="cajasSelect"  />
    	</td>
    </tr>	
     <tr>
    	<td width="10%" class="key">Descripcion:</td>
    	<td colspan="3">
    		<input name="txtCuenta" style="width:300px" id="txtCuenta" type="text" class="cajasSelect"  />
    	</td>
    </tr>	
</table>
</div>

</div>
