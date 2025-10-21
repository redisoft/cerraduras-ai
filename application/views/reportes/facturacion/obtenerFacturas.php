<?php
#if($facturas!=null)
{
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagFactu">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo '
	<table class="admintable" width="100%">
		<tr>
			<th colspan="5" class="encabezadoPrincipal" align="right" style="border-right:none"> 
				Reporte de facturación
			</th>
			<th  class="encabezadoPrincipal" style="border-right:none; border-left:none" colspan="2">
				<img id="btnExportarPdfReporte" onclick="reporteFacturacion(\''.$mes.'\',\''.$anio.'\','.$idEmisor.','.$tipo.')" src="'.base_url().'img/pdf.png" width="22" title="PDF" />
				&nbsp;&nbsp;
				<img id="btnExportarExcelReporte" onclick="excelFacturacion(\''.$mes.'\',\''.$anio.'\','.$idEmisor.','.$tipo.')" src="'.base_url().'img/excel.png" width="22" title="Excel" />
				&nbsp;&nbsp;
				<img id="btnZippearReporte" onclick="zipearFacturas(\''.$mes.'\',\''.$anio.'\','.$idEmisor.','.$tipo.')" src="'.base_url().'img/zip.png" width="22" title="Zipear" />
				<br />
				<a>PDF</a>
    			<a>Excel</a>   
				<a>Zip</a>';
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporte\');
						desactivarBotonSistema(\'btnExportarExcelReporte\');
						desactivarBotonSistema(\'btnZippearReporte\');
					</script>';
				}
				
			echo'
			</th>
			<th colspan="4"  class="encabezadoPrincipal" style="border-left:none" align="right">Total: $'.number_format($total,2).'</th>
		</tr>
		<tr>
			<th>#</th>
			<th>
			Fecha
			
			</th>
			<th>Documento</th>
			<th>Emisor</th>
			<th>Cliente</th>
			<th>Folio y serie</th>
			<th align="center" valign="middle" >
				<select class="cajas" id="selectEstaciones" style="width:120px" onchange="obtenerFacturas()">
					<option value="0">Estación</option>';

					foreach($estaciones as $row)
					{
						echo '<option '.($row->idEstacion==$idEstacion?'selected="selected"':'').' value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
					}

				echo '</select>
			</th>
			<th>Subtotal</th>
			<th>IVA</th>
			<th>Total</th>
			<th width="22%" >Acciones</th>
		</tr>';
	
	#'.($tiendaLocal=='0'?'':'style="display: none"').'
	
	$i=$numero;
	foreach($facturas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$cancelada	=$row->cancelada==1?'<i> (Cancelada)</i>':'';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.$row->documento.'</td>
			<td align="left">'.$row->emisor.'</td>
			<td align="left">'.$row->empresa.'</td>
			<td align="center">'.($row->pendiente=='1'?'':$row->serie.$row->folio.$cancelada).'</td>
			<td align="center">'.$row->estacion.'</td>
			<td align="right">$'.number_format($row->subTotal,2).'</td>
			<td align="right">$'.number_format($row->iva,2).'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			
			<td >';
			#'.($tiendaLocal=='0'?'':'style="display: none"').'
				if($row->pendiente=='0')
				{
					if($row->documento=='Recibo de Nómina')
					{
						$url	=base_url().'pdf/reciboNomina/'.$row->idFactura;
					}
					else
					{
						$url	=base_url().'pdf/crearFactura/'.$row->idFactura;
					}
					?>
					
                    <a id="btnPdfFactura<?php echo $i?>" onclick="window.open('<?php echo $url?>')" title="Ver en PDF" >
                    	<img src="<?php echo base_url()?>img/pdf.png" width="25" />
                    </a>
					
					&nbsp;
					<a id="btnXmlFactura<?php echo $i?>" title="Descargar xml" href="<?php echo base_url()?>facturacion/descargarXML/<?php echo $row->idFactura?>">
						<img src="<?php echo base_url()?>img/xml.png" width="25" style="cursor:pointer" />
					</a>
					
					&nbsp;&nbsp;&nbsp;&nbsp;
					<img  id="btCancelarFactura<?php echo $i?>" src="<?php echo base_url()?>img/cancelar.png" title="Cancelar CFDI" width="25" style="cursor:pointer" onclick="accesoCancelarCfdi('<?php echo $row->idFactura?>')"/>
					
					&nbsp;&nbsp;&nbsp;
					<img id="btnEnviarFactura<?php echo $i?>"  src="<?php echo base_url()?>img/correo.png" title="Enviar CFDI" width="25" style="cursor:pointer" onclick="formularioCorreoFactura('<?php echo $row->idFactura?>')"/>
	
					<img id="btnZippearFactura<?php echo $i?>"  src="<?php echo base_url()?>img/zip.png" title="Zippear" width="25" style="cursor:pointer" onclick="zipearFactura('<?php echo $row->idFactura?>')"/>
					
					<?php
					
					if($row->metodoPago=="PPD, Pago en parcialidades o diferido")
					{
						echo '
						&nbsp;
						<img id="btnPagos'.$i.'" title="Pagos" src="'.base_url().'img/traspasos.png" width="25" style="cursor:pointer" onclick="formularioPagosCfdi('.$row->idFactura.')"/>';
					}
	
					echo ' 
					<br />
					<a id="a-btnPdfFactura'.$i.'">PDF</a>&nbsp;&nbsp;
					<a id="a-btnXmlFactura'.$i.'">XML</a>&nbsp;&nbsp;
					<a id="a-btCancelarFactura'.$i.'">Cancelar</a>
					<a id="a-btnEnviarFactura'.$i.'">Enviar</a>
					<a id="a-btnZippearFactura'.$i.'">Zip</a>';
					
					if($row->metodoPago=="PPD, Pago en parcialidades o diferido")
					{
						echo '&nbsp;&nbsp;&nbsp;<a id="a-btnPagos'.$i.'">Pagos</a>';
					}
					
					if($row->cancelada=="1")
					{
						echo '
						<script>
							desactivarBotonSistema(\'btnEnviarFactura'.$i.'\');
						</script>';
					}
					
					if($permiso[1]->activo==0 )
					{
						echo '
						<script>
							desactivarBotonSistema(\'btnPdfFactura'.$i.'\');
							desactivarBotonSistema(\'btnXmlFactura'.$i.'\');
							desactivarBotonSistema(\'btnZippearFactura'.$i.'\');
						</script>';
					}
					
					if($permiso[2]->activo==0 or $row->cancelada=="1")
					{
						echo '
						<script>
							desactivarBotonSistema(\'btCancelarFactura'.$i.'\');
						</script>';
					}
				}
				else
				{
					echo '
					<a onclick="window.open(\''.base_url().'pdf/crearFactura/'.$row->idFactura.'\')" title="Ver en Prefactura" >
						<img src="'.base_url().'img/print.png" width="25" />
					</a>
					&nbsp;
					<img onclick="obtenerDatosFactura('.$row->idCotizacion.')" src="'.base_url().'img/pdf.png" width="25" />
					<br />
					<a>PDF</a>
					<a>Facturar</a>';					
				}
				
		
			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
	
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagFactu">'.$this->pagination->create_links().'</ul>
	</div>';
}
/*else
{
	echo '<div class="Error_validar">Sin registro de facturas</div>';
}*/
