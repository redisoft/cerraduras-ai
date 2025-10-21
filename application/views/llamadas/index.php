<script src="<?php echo base_url()?>js/crm/clientes/llamadas.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/areas.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/editarSeguimientoClientes.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/editarCrmLlamadas.js"></script>

<script src="<?php echo base_url()?>js/clientes/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/clientes/seguimiento/responsables.js"></script>

<script src="<?php echo base_url()?>js/crm/clientes/contactos.js"></script>
<script src="<?php echo base_url()?>js/crm/clientes/registrar.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>
<script src="<?php echo base_url()?>js/ventas/ventasFacturacion.js"></script>

<script src="<?php echo base_url()?>js/configuracion/servicios/catalogo.js"></script>
<script src="<?php echo base_url()?>js/configuracion/status/catalogo.js"></script>
<script src="<?php echo base_url()?>js/configuracion/estatus/catalogo.js"></script>

<script src="<?php echo base_url()?>js/clientes/iexe/pagos.js"></script>

<script src="<?php echo base_url()?>js/informacion.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
	obtenerLlamadas();
});
	
</script>

<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
    <!--<div class="seccionDiv">
        Seguimientos
    </div>-->
 <table class="toolbar" width="100%">
    <tr>
    	<td width="5%">
        	<a id="btnRegistrarCrm" onclick="formularioCrmClientes('<?php echo date('Y-m-d')?>','<?php echo date('H')?>','<?php echo date('H')?>')" title="Registrar">
                <img src="<?php echo base_url()?>img/crm.png" width="30" />
                <br />
                Registrar
            </a>
            
            <?php
			
			if(sistemaActivo=='IEXE' and($idRol==1 or $idRol==13))
			{
				echo '
				<td class="button" width="5%">
					<a style="cursor:pointer" onclick="excelPagos()">
						<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Pagos" /><br />
					   Pagos        
					</a>  
				</td>
				
				<td class="button" width="5%">
					<a style="cursor:pointer" onclick="excelReporteLlamadas()">
						<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Exportar a excel" /><br />
					   Exportar        
					</a>   
					    
				</td>
				<td class="button" width="5%" onclick="obtenerPlantillas()">
					<a id="btnPlantillas" >
						<img src="'.base_url().'img/plantilla.png" width="30px;" height="30px;" title="Plantillas"  /><br />
						Plantillas
					</a>      
				</td>
				
				<td class="button" width="5%" onclick="obtenerPlantillaEnviar()">
					<a id="btnEnviarCorreoPlantilla" >
						<img src="'.base_url().'img/enviar.png" width="30px;" height="30px;" title="Enviar"  /><br />
						Enviar
					</a>      
				</td>
				
				';
			}
			
			echo '
			<td class="button" width="5%" >
				<a id="btnLicenciaturaSie" onclick="listaMatriculaSie(1)" >
					<img src="'.base_url().'img/libro.png" width="30px;" height="30px;" title="Enviar"  /><br />
					Licenciatura
				</a>      
			</td>
			
			
			<td class="button" width="5%" >
				<a id="btnMaestriaSie" onclick="listaMatriculaSie(0)">
					<img src="'.base_url().'img/libro.png" width="30px;" height="30px;" title="Enviar"  /><br />
					Maestria
				</a>      
			</td>';
			
			if($permisoMatricula[0]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnLicenciaturaSie\');
					desactivarBotonSistema(\'btnMaestriaSie\');
				</script>';
			}
            
			if($permiso[1]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnRegistrarCrm\');
				</script>';
			}
			?>
        </td>
        
        <?php

		if($idCliente>0)
		{
			echo '
			
			<td class="button" width="5%">
				<a id="btnPuntoVenta"  style="cursor:pointer" href="'.base_url().'ventas/puntoVenta/'.(isset($idCliente)?$idCliente:'').'">
					<img src="'.base_url().'img/ventas.png" width="30px;" height="30px;" title="Registrar venta" alt="Registrar venta" /><br />
					
				   Punto de venta        
				</a>      
			</td>
			
			<td align="center" valign="middle" style="border:none" width="6%" >
				<a id="btnVentas" class="toolbar">
					<img src="'.base_url().'img/almacen.png" width="30px" title="Ventas" /> <br />
					Ventas
				</a>
		   	</td>
		   
			<td align="center" valign="middle" style="border:none" width="10%" >
				<a id="btnCotizaciones" href="'.base_url().'clientes/cotizaciones/'.$idCliente.'" class="toolbar" id="Id_Cotizacioness">
					<img src="'.base_url().'img/remision.png" width="30px" title="Ver lista de cotizaciones" style="vertical-align:middle;display:inline-table;cursor:pointer;" /> <br />
					Cotizaciones
				</a>
		   </td>
		   
		   <td align="center" valign="middle" style="border:none" width="5%" >
				<a id="btnCrm" class="toolbar">
					<img src="'.base_url().'img/crm.png" width="30px" title="CRM" /> <br />
					CRM
				</a>
		   </td> ';
		   
		   echo '
			<td align="center" valign="middle" style="border:none" width="10%" >         
				<a id="btnContactos" class="toolbar" href="'.base_url().'ficha/contactos/'.$idCliente.'" >
					<img src="'.base_url().'img/contactos.png" width="30px" id="" title="Contactos" style="vertical-align:middle;display:inline-table;cursor:pointer;" />  <br />
					Contactos                      
				</a>      
			</td>';	
			
			echo '
			<td align="center" valign="middle" style="border:none" width="10%" >         
				<a id="btnFacturas" class="toolbar" onclick="obtenerFacturasCliente()" >
					<img src="'.base_url().'img/pdf.png" width="30px" id="" title="Contactos" style="vertical-align:middle;display:inline-table;cursor:pointer;" />  <br />
					Facturas                      
				</a>      
			</td>';
			
			echo '
			<script>
				desactivarBotonSistema(\'btnCrm\');
			</script>';
		   
			if($permisoCotizacion[0]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnCotizaciones\');
				</script>';
			}
			
			if($permisoContacto[0]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnContactos\');
				</script>';
			}
			
			if($permisoFactura[0]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnFacturas\');
				</script>';
			}
			
			echo '
			<td align="center" valign="middle" style="border:none" width="10%" >         
				<a class="toolbar" onclick="fichaTecnicaCliente('.$idCliente.')" >
				<span class="icon-option" title="Ficha tecnica">
					<img src="'.base_url().'img/fichaTecnica.png" width="30px" height="30px" title="Ficha técnica"  />  
				</span>
				Ficha técnica                      
				</a>      
			</td>';
		}
		?>

        	<td align="center" >
                De
                <input type="text"  name="FechaDia" id="FechaDia" class="busquedas" value="<?php echo date('Y-01-01')?>" style="width:100px;" onchange="obtenerLlamadas()"/>
                A
                <input type="text"  name="FechaDia2" id="FechaDia2" class="busquedas" value="<?php echo date('Y-m-'.$this->configuracion->obtenerUltimaDiaFecha(date('Y-m-d')))?>" style="width:100px;" onchange="obtenerLlamadas()"/>
                <input type="text"  name="txtBuscarLlamada" id="txtBuscarLlamada" class="busquedas" placeholder="Buscar por empresa, responsable<?php echo sistemaActivo=='IEXE'?', matrícula':''?>"  style="width:400px;" value="<?php echo $cliente?>" <?php echo $idCliente>0?'readonly="readonly"':'' ?>  />
                
                 <input type="hidden"  name="txtTipoRegistro" id="txtTipoRegistro" value="clientes" />
                 <input type="hidden"  name="txtTipoBajas" id="txtTipoBajas" value="1"/>
        	</td>
		</tr>
	</table>
</div>
</div>

<div class="listproyectos">
	<div id="procesandoInformacion"></div>
	<div id="obtenerLlamadas" style="margin-top:10px">
    	<input type="hidden" value="0" id="selectStatusBusqueda" name="selectStatusBusqueda" />
        <input type="hidden" value="0" id="selectServiciosBusqueda" name="selectServiciosBusqueda" />
        
        <input type="hidden" value="0" id="selectUsuarios" name="selectUsuarios" />
        <input type="hidden" value="0" id="selectResponsables" name="selectResponsables" />
        <input type="hidden" value="0" id="selectEstatusBuscar" name="selectEstatusBuscar" />
        <input type="hidden" value="0" id="selectProgramasBuscar" name="selectProgramasBuscar" />
    </div>
</div>

<div id="ventanaDetallesSeguimiento" title="Detalles de seguimiento">
    <div id="errorDetallesSeguimiento" class="ui-state-error" ></div>
    <div id="detallesSeguimiento"></div>
</div>

<div id="ventanaArchivosSeguimiento" title="Archivos">
    <div id="registrandoArchivosSeguimiento"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerArchivosSeguimiento"></div>
</div>

<div id="ventanaFormularioCrmCliente" title="CRM">
	<div class="ui-state-error" ></div>
    <div id="registrandoCrmCliente"></div>
	<div id="formularioCrmClientes"></div>
</div>

<div id="ventanaCatalogoServicios" title="Catálogo de servicios">
	<div id="obtenerCatalogoServicios"></div>
</div>

<div id="ventanaCatalogoStatus" title="Catálogo de CRM">
	<div id="obtenerCatalogoStatus"></div>
</div>

<div id="ventanaFormularioSeguimientoDetalle" title="Seguimiento">
    <div class="ui-state-error" ></div>
	<div id="formularioSeguimientoDetalle"></div>
</div>

<div id="ventanaEditarResponsable" title="Editar responsable">
    <div class="ui-state-error" ></div>
    <div id="editandoResponsable"></div>
	<div id="formularioEditarResponsable"></div>
</div>

<div id="ventanaCatalogoEstatus" title="Catálogo de Estatus">
	<div id="obtenerCatalogoEstatus"></div>
</div>

<div id="ventanaEditarEstatusSeguimiento" title="Estatus">
    <div id="editandoEstatusSeguimiento"></div>
	<div id="obtenerEstatusSeguimientoEditar"></div>
</div>


<input type="hidden" id="txtTipoPlantilla" value="1" />

<?php
if($idCliente>0)
{
	echo'
	<div id="ventanaFacturasCliente" title="Facturación">
		<div class="ui-state-error" ></div>
		<div id="generandoReporte"></div>
			<table class="admintable" width="100%">
				<tr>
					<td class="key">Busqueda por mes:</td>
					<td>
						<input type="text" class="cajas" id="txtMes" style="width:80px" placeholder="Seleccione" onchange="obtenerFacturasCliente()" />
						<input type="hidden" class="cajas" id="txtIdCliente" value="'.$idCliente.'" />
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

$this->load->view('clientes/prospectos/bajas/modalBajas');
$this->load->view('configuracion/causas/modalCausas');
$this->load->view('clientes/prospectos/plantillas/modalPlantillas',array('tipoPlantilla'=>'1'));
$this->load->view('clientes/prospectos/plantillas/modalEnviarPlantilla');


$this->load->view('sie/matricula/modales');

?>


<div id="ventanaEditarSeguimiento" title="Editar CRM">
    <div id="editandoCrm"></div>
    <div id="errorCrm" class="ui-state-error" ></div>
    <div id="obtenerSeguimientoEditar"></div>
</div>

</div>
