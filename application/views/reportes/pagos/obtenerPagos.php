<?php
if($compras!=null)
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagPagos">'.$this->pagination->create_links().'</ul>
	</div>';
	?>
    
	<table class="admintable" style="width:100%">
    	<tr>
        	<th colspan="4" class="encabezadoPrincipal" align="right" style="border-right:none">
            	Reporte de pagos
            </th>
            <th class="encabezadoPrincipal" style="border-right:none; border-left:none">
            	 <a id="btnExportarPdfReporte" onclick="window.open('<?php echo base_url()?>reportes/reportePagos/<?php echo $inicio.'/'.$fin.'/'.$idProveedor?>')">
                    <img src="<?php echo base_url()?>img/pdf.png" width="20" title="Generar PDF" style="cursor:pointer" />
                </a>
                &nbsp;&nbsp;
            	<img id="btnExportarExcelReporte" onclick="excelPagos('<?php echo $inicio?>','<?php echo $fin?>','<?php echo $idProveedor?>')" src="<?php echo base_url()?>img/excel.png" width="20" title="Generar excel" style="cursor:pointer" />
                
                <br />
                <a>PDF</a>
                <a>Excel</a>   
                
                <?php
                if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporte\');
						desactivarBotonSistema(\'btnExportarExcelReporte\');
					</script>';
				}
				?>             
            </th>
            <th colspan="3" class="encabezadoPrincipal" align="right" style="border-left:none">
            	<?php echo 'Total: $'.number_format($totalCompras,2)?>
            </th>
        </tr>
        <tr>
            <th class="" align="right">#</th>
            <th class="">Fecha compra</th>
            <th class="">Proveedor</th>
            <th class="">Descripción</th>
            
            <th class="">Fecha de vencimiento</th>
            <th class="">Días de vencimiento</th>

            <th class="">Saldo</th>
            <th class="">Acciones</th>
		</tr>
	
		<?php
        $i		=1;
        $total	=0;
        
        foreach($compras as $row)
        {
            $estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
            
            $total		+= $row->total;
            $pagado		= $this->reportes->obtenerPagadoCompra($row->idCompras);
            $fecha		= $this->reportes->obtenerFechaFin($row->fechaCompra,$row->diasCredito);
            $dias		= $this->reportes->obtenerDiasRestantes($fecha);
                
            ?>
            <tr <?php echo $estilo?>>
                <td align="right"><?php echo $i ?></td>
                <td align="center"><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
                <td align="center"><?php echo $row->empresa?></td>
                <td align="center">
                <?php 
                    echo $row->nombre;
                    echo ' <img src="'.base_url().'img/compras.png" width="22" height="22" title="Ver detalles" onclick="obtenerCompraInformacion('.$row->idCompras.')" />';
                ?></td>
                
                <td align="center"><?php echo obtenerFechaMesCorto($fecha)?></td>
                <td align="center"><?php echo $dias<0?'<label style="color:red">'.($dias*-1).'</label>':$dias?></td>
    
                <td align="right">$<?php echo number_format($row->total-$pagado,2)?></td>
                <td align="center">
                 	<img id="btnPagosComprasReporte<?php echo $i?>" onclick="obtenerPagosComprasProveedor('<?php echo $row->idCompras?>')" src="<?php echo base_url()."img/pagos.png"?>" width="20" height="20" title="Pagos a proveedores" style="cursor:pointer;"/>
                    <br />
                    <a id="a-btnPagosComprasReporte<?php echo $i?>">Pagos</a>
					<?php
                    if($permiso[1]->activo==0)
                    {
                         echo '
                        <script>
                            desactivarBotonSistema(\'btnPagosComprasReporte'.$i.'\');
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
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagPagos">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de pagos</div>';
}
?>

