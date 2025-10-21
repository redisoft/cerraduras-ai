<?php
if($mostrarMenu)
{
	echo '
	<script src="'.base_url().'js/ventas/ventasFacturacion.js"></script>
	<script src="'.base_url().'js/informacion.js"></script>';
}
?>

<script src="<?php echo base_url()?>js/clientes/contactos/contactos.js"></script>

<script>
$(document).ready(function()
{
	obtenerContactos()
});
</script>

<div class="derecha" style="min-height:100px">
    <div class="submenu" <?php echo !$mostrarMenu?'style="display:none"':''?>>
    <div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
	<?php
    if($mostrarMenu)
    {
        ?>
       
            <table class="toolbar" width="30%">
               <!-- <tr>
                    <td class="seccion">Contactos</td>
                    <td colspan="3" align="left" valign="middle" style="font-size:14px; text-align:left"> 
                    <?php
                    echo 'Cliente: '.$cliente->empresa
                    ?>
                    </td>
               </tr>-->
                
                <tr>
                  
                  <?php
                    
                    echo '
					<td class="button" width="5%">
						<a id="btnPuntoVenta"  style="cursor:pointer" href="'.base_url().'ventas/puntoVenta/'.(isset($idCliente)?$idCliente:'').'">
							<img src="'.base_url().'img/ventas.png" width="30px;" height="30px;" title="Registrar venta" alt="Registrar venta" /><br />
							
						   Punto de venta        
						</a>      
					</td>
		
                    <td align="center" valign="middle" style="border:none" width="6%" >
                        <a id="btnVentas" href="'.base_url().'clientes/ventas/'.$cliente->idCliente.'" class="toolbar">
                            <img src="'.base_url().'img/ventas.png" width="30px" title="Ver lista de ventas" style="vertical-align:middle;display:inline-table;cursor:pointer;" /> <br />
                            Ventas
                        </a>
                   </td>
                    <td align="center" valign="middle" style="border:none" width="10%" >         
                        <a id="btnCotizaciones"  class="toolbar" href="'.base_url().'clientes/cotizaciones/'.$cliente->idCliente.'" >
                            <img src="'.base_url().'img/remision.png" width="30px" id="" style="vertical-align:middle;display:inline-table;cursor:pointer;" />  <br />
                            Cotizaciones                      
                        </a>      
                    </td>
					
					<td align="center" valign="middle" style="border:none" width="5%" >
						<a id="btnCrm" href="'.base_url().'cotizaciones/llamadas/'.$cliente->idCliente.'" class="toolbar">
							<img src="'.base_url().'img/crm.png" width="30px" title="CRM" /> <br />
							CRM
						</a>
				   </td>
					
					<td align="center" valign="middle" style="border:none" width="10%" >         
						<a id="btnContactos" >
							<img src="'.base_url().'img/contactos.png" width="30px" id="" title="Contactos" />  <br />
							Contactos                      
						</a>      
					</td>
					
                    <td align="center" valign="middle" style="border:none" width="10%" >         
                        <a id="btnFacturas"  class="toolbar" onclick="obtenerFacturasCliente()" >
                            <img src="'.base_url().'img/pdf.png" width="30px" id="" title="Contactos" style="vertical-align:middle;display:inline-table;cursor:pointer;" />  <br />
                            Facturas                      
                        </a>      
                    </td>';
					
					echo '
					<script>
						desactivarBotonSistema(\'btnContactos\');
					</script>';
                   
                    if($permisoVenta[0]->activo==0)
                    {
                        echo '
                        <script>
                            desactivarBotonSistema(\'btnVentas\');
                        </script>';
                    }
                    
                    if($permisoCotizacion[0]->activo==0)
                    {
                        echo '
                        <script>
                            desactivarBotonSistema(\'btnCotizaciones\');
                        </script>';
                    }
                    
                    if($permisoFactura[0]->activo==0)
                    {
                        echo '
                        <script>
                            desactivarBotonSistema(\'btnFacturas\');
                        </script>';
                    }
                    ?>
            
                    <td align="center" valign="middle" style="border:none" width="10%" >         
                        <a class="toolbar" onclick="fichaTecnicaCliente(<?php echo $cliente->idCliente; ?>)" >
                        <span class="icon-option" title="Ficha tecnica">
                            <img src="<?php print(base_url()); ?>img/fichaTecnica.png" width="30px" height="30px" title="Contactos" style="vertical-align:middle;display:inline-table;cursor:pointer;" />  
                        </span>
                        Ficha técnica                      
                        </a>      
                    </td>
                    
               </tr>
        </table>
        
        <?php
    }
    ?>
</div>

<div class="listproyectos">
<input type="hidden" name="txtClienteId" id="txtClienteId" value="<?php print($cliente->idCliente); ?>"  />

<table class="toolbar" width="10%" style="margin-top:20px">
    <tr>
        <td style="border:none" width="27%" align="center" valign="middle" class="button">
            <a id="btnRegistrarContacto" class="toolbar" onclick="formularioContacto()" >
            	<img src="<?php print(base_url()); ?>img/add.png"  border="0" title="Agregar contacto" /> 
                <br />
				Registrar
            </a>
        </td>
    </tr>
</table>
<?php
if($permiso[1]->activo==0)
{ 
	echo '
	<script>
		desactivarBotonSistema(\'btnRegistrarContacto\');
	</script>';
}
?>

<div id="procesandoContactos"></div>
<div id="obtenerContactos"></div>


<div id="ventanaAgregarContacto" title="Contactos">
    <div id="agregandoContacto"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioContacto"></div>
</div>

<div id="ventanaEditarContacto" title="Editar contacto">
    <div style="width:99%;" id="editandoContacto"></div>
    <div id="errorEditarContacto" class="ui-state-error" ></div>
    <div id="obtenerContacto"></div>
</div>

<div id="ventanaEditarContactoCliente" title="Editar contacto cliente">
    <div id="editandoContactoCliente"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerContactoCliente"></div>
</div>


<?php
if($mostrarMenu)
{
	echo '
	<div id="ventanaFacturasCliente" title="Facturación">
	<div class="ui-state-error" ></div>
	<div id="generandoReporte"></div>
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Busqueda por mes:</td>
			<td>
				<input type="text" class="cajas" id="txtMes" style="width:80px" placeholder="Seleccione" onchange="obtenerFacturasCliente()" />
				<input type="hidden" class="cajas" id="txtIdCliente" value="'.$cliente->idCliente.'" />
			</td>
		</tr>
	</table>
	<div id="obtenerFacturasCliente"></div>
	</div>
	
	<div id="ventanaFichaCliente" title="Ficha técnica del cliente">
		<div id="errorInformacionCliente" class="ui-state-error" ></div>
		<div id="obtenerFichaCliente"></div>
	</div>
	
	<div id="ventanaEnviarFichaCliente" title="Enviar ficha técnica del cliente">
		<div id="enviandoFichaCliente"></div>
		<div class="ui-state-error" ></div>
		<div id="formularioCorreoFichaCliente"></div>
	</div>';
}
?>



</div>
</div>
