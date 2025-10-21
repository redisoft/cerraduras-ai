<?php
$i	= $limite;
if($cuentas!=null)
{
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaCuentasCatalogo tr:even").addClass("sinSombra");
		$("#tablaCuentasCatalogo tr:odd").addClass("sombreado");  
	});
	</script>
	
	<div style="max-height: 600px; overflow: scroll; overflow-x: hidden; overflow-y: auto">
		<table class="admintable" id="tablaCuentasCatalogo" style="width:100%">
			<tr>
				<th class="encabezadoPrincipal" colspan="6">
					<img src="'.base_url().'img/pdf.png" width="24" onclick="reporteBalanza()" />
					&nbsp;&nbsp;
					<img src="'.base_url().'img/excel.png" width="24" onclick="excelBalanza()" />
					&nbsp;&nbsp;
					<img src="'.base_url().'img/xml.png" width="24" onclick="xmlBalanza()" />
					<br />
					PDF &nbsp;
					Excel &nbsp;
					XML
				</th>
			</tr>
			<tr>
				<th width="20%">Cuenta</th>
				<th width="20%">Descripción</th>
				<th width="20%">Saldo inicial</th>
				<th width="10%">Cargos</th>
				<th width="10%">Abonos</th>
				<th width="20%">Saldo final</th>
			</tr>';
		
			$totalSaldos	= 0;
			$totalDebe		= 0;
			$totalHaber		= 0;
			
			foreach($cuentas as $row)
			{
				$saldo	= $row->saldo;
				$debe	= $row->debe;
				$haber	= $row->haber;
			
				if($row->cuentasHijo>0)
				{
					$saldos	= $this->contabilidad->obtenerSaldoCuentas($row->idCuentaCatalogo,$row->cuentasHijo);
					$saldo	= $saldos[0];
					$debe	= $saldos[1];
					$haber	= $saldos[2];
				}
				
				$totalSaldos	+= $saldo;
				$totalDebe		+= $debe;
				$totalHaber		+= $haber;
				
				$mostrar		= false;
				if($filtro==0 and ($saldo>0 or $debe>0 or $haber>0))
				{
					$mostrar=true;
				}
				
				if($filtro==1 and $saldo==0 and $debe==0 and $haber==0)
				{
					$mostrar=true;
					
					$totalSaldos	= 0;
					$totalDebe		= 0;
					$totalHaber		= 0;
				}
				
				if($filtro==2)
				{
					$mostrar=true;
				}
								
				if($mostrar)
				{
					echo'
					<tr id="filaCuenta'.$row->idCuentaCatalogo.'">
						<td align="left">'.$row->numeroCuenta.'</td>
						<td align="left">'.$row->descripcion.'</td>
						<td align="right">$'.number_format($saldo,decimales).'</td>
						<td align="right">$'.number_format($debe,decimales).'</td>
						<td align="right">$'.number_format($haber,decimales).'</td>
						<td align="right">$'.number_format($saldo+$debe-$haber,decimales).'</td>
					</tr>';
				}
				
				/*if($filtro!=0 and $sa)
				{
					
				}*/
				
				if($filtro!=3)
				{
					if($row->cuentasHijo>0)
					{
						$this->contabilidad->obtenerCuentasBalanzaVista($row->idCuentaCatalogo,2,$filtro);
					}
				}

				$i++;
			}
		
			echo '
		</table>
	</div>
	<table class="admintable" width="100%">
		<tr>
			<td width="40%" class="totales" colspan="2" align="right">Totales</td>
			<td width="20%" class="totales" align="right">$'.number_format($totalSaldos,decimales).'</td>
			<td width="10%" class="totales" align="right">$'.number_format($totalDebe,decimales).'</td>
			<td width="10%" class="totales" align="right">$'.number_format($totalHaber,decimales).'</td>
			<td width="20%" class="totales" align="right">$'.number_format($totalSaldos+$totalDebe-$totalHaber,decimales).'</td>
		</tr>
		
		<tr>
			<td class="totales" colspan="2" align="right">Diferencias</td>
			<td class="totales" align="right"></td>
			<td class="totales" align="right">$'.number_format($totalDebe-$totalHaber,decimales).'</td>
			<td class="totales" align="right"></td>
			<td class="totales" align="right"></td>
		</tr>
	</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de balanza de comprobación</div>';
}
?>