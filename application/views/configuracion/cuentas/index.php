<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/configuracion/cuentas.js"></script>
<!-- CONTABILIDAD -->
<script src="<?php echo base_url()?>js/contabilidad/asociarCuentas.js"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
  	<!--<div class="seccionDiv">
    	Cuentas
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
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos/cuentas"); ?>" class="escalaGrisesConfiguracion"> <span class="icon-option" title="Cuentas"> <img src="<?php print(base_url()); ?>img/dinero.png"   width="30" height="30" title="Cuentas" /></span> Cuentas </a> </td>
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

  <div class="listproyectos">
  <table class="toolbar" width="10%">
    <tr>
      <td style="border:none" width="10%" align="center" valign="middle" class="button">
      	 <a id="btnRegistrarCuenta" onclick="formularioCuentas()" > 
          	<img src="<?php print(base_url()); ?>img/add.png" title="Agregar cuenta" style="cursor:pointer" /><br />
			Registrar 
          </a>
          
       <?php
	    if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnRegistrarCuenta\');
			</script>';
		}
      ?>
      </td>
    </tr>
  </table>
<?php

if(!empty($cuentas))
{
	echo'
    <table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" width="4%" align="left" >#</th>
		<th class="encabezadoPrincipal" width="18%" align="left">Banco</th>
		<th class="encabezadoPrincipal" align="left">No. Cuenta</th>
		<th class="encabezadoPrincipal" align="left">Clabe</th>
		<th class="encabezadoPrincipal" align="left">Tarjeta de crédito</th>
		<th class="encabezadoPrincipal" align="right">Saldo inicial</th>
		<!--<th class="encabezadoPrincipal" align="left">Cliente</th>-->
		<th class="encabezadoPrincipal" align="left">Emisor</th>
		
		<th class="encabezadoPrincipal" align="center">Visible en reportes</th>

		<th class="encabezadoPrincipal" width="15%" align="center">Acciones</th>
	</tr>';

	$i=1;
	foreach ($cuentas as $row)
	{
		$estilo	= $i%2>0?'class="sinSombra"':'class="sombreado"';
		
		?>
		<tr <?php echo $estilo?>>
            <td align="left"><?php echo $i; ?>  </td>
            <td align="left"> <?php echo $row->nombre; ?> </td>
            <td align="left">  <?php echo $row->cuenta; ?> </td>
            <td align="left">  <?php echo $row->clabe; ?> </td>
            <td align="left">  <?php echo $row->tarjetaCredito; ?> </td>
            <td align="right">$<?php echo number_format($row->saldoInicial,decimales) ?> </td>
            <!--<td align="left">  <?php echo $row->cliente; ?> </td>-->
            <td align="left">  <?php echo $row->emisor; ?> </td>
            <td align="center">  <?php echo $row->reportes=='1'?'Si':'No'; ?> </td>
          
            
            <td align="center">
            	<img id="btnEditarCuenta<?php echo $i?>" onclick="accesoEditarCuenta(<?php echo $row->idCuenta?>)" src="<?php echo base_url()?>/img/editar.png" width="22" height="22" />
                &nbsp;&nbsp;
                
                <img id="btnBorrarCuenta<?php echo $i?>" src="<?php echo base_url()?>/img/borrar.png" width="22" height="22" onClick="borrarCuenta(<?php echo $row->idCuenta?>,'¿Realmente desea borrar la cuenta?')" />
                
                <br />
                
                <a id="a-btnEditarCuenta<?php echo $i?>">Editar</a>
                <a id="a-btnBorrarCuenta<?php echo $i?>">Borrar</a>
                
				<?php
				#or $row->idCuenta==1
                if($permiso[2]->activo==0 )
                {
                     echo '
                    <script>
                        desactivarBotonSistema(\'btnEditarCuenta'.$i.'\');
                    </script>';
                }
                
                if($permiso[3]->activo==0 or $row->idCuenta==1)
                {
                     echo '
                    <script>
                        desactivarBotonSistema(\'btnBorrarCuenta'.$i.'\');
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
	echo '<div class="Error_validar" style=" width:88%; margin-left:4%;">No hay registros de cuentas de banco </div>'; 
}
 ?>
 
<div id="ventanaCuentas" title="Cuentas">
<div id="registrandoCuenta"></div>
<div id="errorCuenta" class="ui-state-error" ></div>
<div id="formularioCuentas"></div>
</div>

<div id="ventanaEditarCuenta" title="Editar cuenta">
<div id="editandoCuenta"></div>
<div id="errorEditarCuenta" class="ui-state-error" ></div>
<div id="obtenerCuenta"></div>
</div>

<div id="ventanaFormularioAsociarCuenta" title="Cuentas contables">
    <div id="asociandoCuentas"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioAsociarCuenta"></div>
</div>



</div>
</div>
