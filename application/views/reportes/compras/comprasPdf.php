
<?php
$this->load->view('reportes/compras/encabezado');

if($compras!=null)
{
	?>
	<table class="admintable" style="width:100%">
	
	<?php
	$i		=1;
	$total	=0;
	
	foreach($compras as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$total		+=$row->total;
		$pagado		=$this->reportes->obtenerPagadoCompra($row->idCompras);
		?>
		<tr <?php echo $estilo?>>
            <td width="3%" align="right"><?php echo $i ?></td>
            <td width="10%" align="center"><?php echo obtenerFechaMesCorto($row->fechaCompra)?></td>
            <td width="15%" align="center"><?php echo $row->empresa?></td>
            <td width="15%" align="center"><?php echo $row->nombre?></td>
            
            <?php
			$seguimiento	= null;
			if(strlen($row->idSeguimiento)>0)
			{
				$seguimiento	= $this->crm->obtenerUltimoSeguimientoCompra($row->idCompras);
			}
			
            echo'
			<td align="center" width="10%">';
				
				if($seguimiento!=null)
				{
					echo'
					<span >
						<div style="background-color: '.$seguimiento->color.'" class="circuloStatus"></div>
						<i style="font-weight:100" style="color: '.$seguimiento->color.'">'.$seguimiento->status.'<br />'.obtenerFechaMesCortoHora($seguimiento->fecha).'</i>
					</span>';
				}
				
			echo'
			</td>';
			?>
            
            
            <td width="10%" align="right">$<?php echo number_format($row->subTotal,2)?></td>
            <td width="10%" align="right">$<?php echo number_format($row->descuento,2)?></td>
            <td width="9%" align="right">$<?php echo number_format($row->iva,2)?></td>
            <td width="9%" align="right">$<?php echo number_format($row->total,2)?></td>
               
            <td width="9%" align="right">$<?php echo number_format($row->total-$pagado,2)?></td>
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
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras</div>';
}
?>

