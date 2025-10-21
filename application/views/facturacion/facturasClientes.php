<script src="<?php echo base_url()?>js/facturacion.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/facturacion/facturaManual.js?v=<?=rand()?>"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js?v=<?=rand()?>"></script> 

<script>
$(document).ready(function()
{
	$("#txtBusquedas").autocomplete(
	{
		source:base_url+'configuracion/obtenerClientes',
		
		select:function( event, ui)
		{
			window.location.href=base_url+'facturacion/facturasCliente/'+ui.item.idCliente;
		}
	});
});

</script>

<div class="derecha">
<div class="submenu">

<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
    <table class="toolbar" width="100%">
        <tr>
        	 <td>
                <a id="btn" onclick="formularioFacturaManual()">
                    <img src="<?php echo base_url()?>img/cfdi.png" width="30px;" height="30px;" title="Factura global" /><br />
                    
                   Factura manual
                </a>      
             </td>
             
            <td class="key">
            	<input type="text"  name="txtBusquedas" id="txtBusquedas" class="busquedas" placeholder="Buscar facturas por cliente"  style="width:600px;"/>
            </td>
        </tr>	
    </table>
</div>

<div class="listproyectos">
<?php

if(!empty ($facturas))
{
	echo '
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table class="admintable" width="100%" >
        <tr>
            <th class="encabezadoPrincipal" colspan="6">Facturas del cliente '.$cliente->empresa.'</th>
        </tr>
	 <tr>
		<th width="30px" align="center" valign="middle">#</th>
		<th width="250px" align="center" valign="middle">Fecha factura</th>
        <th width="250px" align="center" valign="middle">Documento</th>
		<th width="200px" align="center" valign="middle">Folio</th>
		<th width="250px" align="right" valign="middle">Monto</th>
		<th width="250px" align="center" valign="middle">Acciones</th>
	 </tr>';

	$i=1;
	
	foreach ($facturas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		?>
		 <tr <?php echo $estilo?>>
			<td width="30px" align="center"> <?php print($i); ?> </td>
			<td width="140px" align="center"><?php echo obtenerFechaMesCortoHora($row->fecha); ?></td>
            <td width="140px" align="center"><?php echo $row->documento; ?></td>
			<td width="210px" align="center"><?php echo $row->serie.$row->folio; ?></td>
			<td width="210px" align="right">$ <?php echo number_format($row->total,2); ?> </td>
			<td width="20%" align="left" valign="middle">
			
			<?php
			
			echo '
			<a id="btnExportarPdf'.$i.'" onclick="window.open(\''.base_url().'pdf/crearFactura/'.$row->idFactura.'\')" title="Ver factura en PDF" >
            	<img src="'.base_url().'img/pdf.png" width="22" />
            </a>
			<a id="btnExportarExcel'.$i.'" title="Descargar xml" href="'.base_url().'facturacion/descargarXML/'.$row->idFactura.'">
				<img src="'.base_url().'img/xml.png" width="25" style="cursor:pointer" />
			</a>
			&nbsp;&nbsp;
			<img id="btnCancelarCfi'.$i.'" src="'.base_url().'img/cancelar.png" title="Cancelar CFDI" width="25" style="cursor:pointer" onclick="accesoCancelarCfdi('.$row->idFactura.')"/>
				
            &nbsp;&nbsp;&nbsp;
            <img id="btnEnviarCfdi'.$i.'" src="'.base_url().'img/correo.png" title="Enviar CFDI" width="25" style="cursor:pointer" onclick="formularioCorreo('.$row->idFactura.')"/>';
            
			echo'
			<br />
			<a>PDF</a>
			<a>XML</a>
			<a>Cancelar</a>
			<a>Enviar</a>';
			
			if($permiso[1]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnExportarPdf'.$i.'\');
					desactivarBotonSistema(\'btnExportarExcel'.$i.'\');
					desactivarBotonSistema(\'btnEnviarCfdi'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0 or $row->cancelada=="1")
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnCancelarCfi'.$i.'\');
				</script>';
			}
			?>
		   
			</td>
		</tr>
		<?php
		$i++;
		}//Foreach
		
		?>
		<input type="hidden" id="clienteFactura" name="clienteFactura" value="<?php echo $this->uri->segment(3)?>" />
		</table>
		
		<div style="width:90%; margin-bottom:1%;">
	 <?php
	 print("<ul id='pagination-digg' class='ajax-pag'>");
	 print($this->pagination->create_links());
	 print("</ul>");
	 ?>
	</div>
	
	  <?php
	
	}
	else
	{
		 echo '
		 <div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		 	No se encontraron facturas del cliente '.$cliente->empresa.'.
		 </div>';
	}
?>

</div>

<div id="ventanaCancelarFactura" title="Cancelar CFDI">
    <div id="cancelandoCfdi"></div>
    <div class="ui-state-error" ></div>
    <div id="obtenerFacturaCancelar"></div>
</div>

<div id="ventanaEnviarCorreo" title="Enviar factura por correo electrónico">
    <div id="enviandoCorreo"></div>
    <div id="errorCorreo" class="ui-state-error" ></div>
    <div id="formularioCorreo"></div>
</div>

<div id="ventanaFacturaManual" title="Facturación">
    <div id="registrandoFacturaManual"></div>
    <div id="formularioFacturaManual"></div>
</div>

</div>
</div>
