
  
<?php
if($compras!=null)
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagCompras">'.$this->pagination->create_links().'</ul>
	</div>';
	?>
    
	<table class="admintable" style="width:100%">
    	<tr>
        	<th colspan="5" class="encabezadoPrincipal" align="right" style="border-right:none">
            Reporte de compras
           
            </th>
            <th class="encabezadoPrincipal" style="border-right:none; border-left:none" colspan="2">
                <img id="btnExportarPdfReporte" onclick="reporteCompras(<?php echo '\''.$inicio.'\',\''.$fin.'\','.$idProveedor?>)" src="<?php echo base_url()?>img/pdf.png" width="20" title="Generar PDF" style="cursor:pointer" />
                &nbsp;&nbsp;
            	<img id="btnExportarExcelReporte" onclick="excelCompras('<?php echo $inicio?>','<?php echo $fin?>','<?php echo $idProveedor?>')" src="<?php echo base_url()?>img/excel.png" width="20" title="Generar excel" style="cursor:pointer" />
                
                <br />
                <a>PDF</a>
                <a>Excel</a>   
                
                <?php
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporte\');
						desactivarBotonSistema(\'btnExportarExcelReporte\');
					</script>';
				}
				?>             
            </th>
            <th colspan="4" class="encabezadoPrincipal" align="right" style="border-left:none">
            	<?php echo 'Total: $'.number_format($totalCompras,2)?>
            </th>
        </tr>
        <tr>
            <th class="" align="right">#</th>
            <th class="">Fecha compra</th>
            <th class="">Proveedor</th>
            <th class="">Orden</th>
            <th class="">CRM</th>
            <th class="">Subtotal</th>
            <th class="">Descuento</th>
            <th class="">IVA</th>
            <th class="">Total</th>
            
            <th class="">Saldo</th>
            <th class="">Acciones</th>
		</tr>
	
	<?php
	$i		=1;
	$total	=0;
	
	foreach($compras as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$total		+=$row->total;
		$pagado		=$this->reportes->obtenerPagadoCompra($row->idCompras);
		$onclick	= 'onclick="obtenerComprita('.$row->idCompras.')" title="Click para ver el detalle"';
		?>
		<tr <?php echo $estilo?>>
            <td align="right" <?php echo $onclick?>><?php echo $i ?></td>
            <td align="center" <?php echo $onclick?>><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
            <td align="center" <?php echo $onclick?>><?php echo $row->empresa?></td>
            <td align="center" <?php echo $onclick?>><?php echo $row->nombre?></td>
            
            <?php
			$seguimiento	= null;
			if(strlen($row->idSeguimiento)>0)
			{
				$seguimiento	= $this->crm->obtenerUltimoSeguimientoCompra($row->idCompras);
			}
			
			$mostrarSeguimiento=false;
			
			if($permisoCrm[0]->activo==1)
			{
				$mostrarSeguimiento=true;
			}
			
            echo'
			<td align="center" title="Click para ver detallles de seguimiento" '.($mostrarSeguimiento?($seguimiento!=null?'onclick="obtenerSeguimientoServicio('.$row->idCompras.','.$seguimiento->idSeguimiento.')"':'onclick="obtenerSeguimientoServicio('.$row->idCompras.',0)"'):'').' >';
				
				if($mostrarSeguimiento and $seguimiento!=null)
				{
					echo'
					<span >
						<div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
						<i style="font-weight:100">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i>
					</span>';
				}
				if($mostrarSeguimiento and $seguimiento==null)
				{
					echo '<img src="'.base_url().'img/crm.png" width="22" height="22" />';
				}
				
			echo'
			</td>';
			?>
            
            <td align="right" <?php echo $onclick?>>$<?php echo number_format($row->subTotal,2)?></td>
            <td align="right" <?php echo $onclick?>>$<?php echo number_format($row->descuento,2)?></td>
            <td align="right" <?php echo $onclick?>>$<?php echo number_format($row->iva,2)?></td>
            <td align="right" <?php echo $onclick?>>$<?php echo number_format($row->total,2)?></td>
            <td align="right" <?php echo $onclick?>>$<?php echo number_format($row->total-$pagado,2)?></td>
            <td align="center">
            
           	 	<img id="btnConvertirVenta<?php echo $i?>" onclick="obtenerPagosComprasProveedor('<?php echo $row->idCompras?>')"  src="<?php echo base_url()."img/pagos.png"?>" width="20" height="20" title="Pagos a proveedores" style="cursor:pointer;"/>
				<br />
				<a id="a-btnConvertirVenta<?php echo $i?>">Pagos</a>
            
            <?php
           		if($permiso[1]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnConvertirVenta'.$i.'\');
					</script>';
				}
             ?>
            </td>
		</tr>
		<?php
		$i++;   
	}
	?>
        <!--tr>
            <th align="right" colspan="6">
            <a onclick="window.open('<?php echo base_url()?>reportes/comprasPDF')">
            	<img src="<?php echo base_url()?>img/pdf.png" width="20" title="Generar PDF" 
            style="cursor:pointer" />
            </a>
            &nbsp; Total $ <?php echo number_format($total,2)?>
            </th>
        </tr-->
	</table>

	<?php
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagCompras">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras</div>';
}
?>

