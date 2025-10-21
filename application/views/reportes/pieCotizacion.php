<?php
/*echo '<div style="margin-bottom:20px;">';
echo nl2br('Vigencia 30 días naturales
Entregado el producto no hay devoluciones de dinero ni cancelaciones.
Cualquier cambio de medida está sujeto a una nueva cotización.
Instalaciones se agendarán el día del pago del anticipo del 80% , sólo aplica en la ciudad de Querétaro, instalaciones foráneas se requiere el pago al 100%. Cancelación de Instalaciones tiene una penalización del 20% del total del anticipo. Si el cliente no cuenta con las condiciones adecuadas y acordadas para la instalación se reagendará la cita de instalación de acuerdo a disponibilidad. En envíos por paquetería el depósito debe estar en firme.');
echo '</div>';*/

if($cuentas!=null)
{
	echo '
	<div style="font-size:20px">';
	
	
		echo '<section style="width:70px; float:left; font-size:10px">
		<table style="font-size:10px; width:70px; color: #000">
			<tr>';
				
				foreach($cuentas as $row)
				{
					echo'
					<td>';
					
					echo 'Banco:'.$row->banco.'<br />';
					echo 'Cuenta:'.$row->cuenta.'<br />';
					echo 'Clabe:'.$row->clabe.'';
					
					echo '
					</td>';
				}
			
			echo'
			</tr>
		</table>
		</section>';
	
	
	echo'
	</div>';
}

?>



<div class="leyendas" style="font-weight:bold; font-size:12px; width:100%; color: #000">
<div style="width:500px; float:left; padding-left:0px; " align="left"> <?php echo '<label>Página '.'{PAGENO}/{nb}'.'</label>';?></div>
<div style="width:150px; float:right" align="right"><?php echo obtenerFechaMesCortoHora(date('Y-m-d H:i'))?></div>
</div>
 
 