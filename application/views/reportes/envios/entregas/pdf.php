
<?php
$i=1;
echo'
<div align="center">
	<table width="100%">
		<tr>
			<td width="20%">';
				if(strlen($this->session->userdata('logotipo')) and file_exists('img/logos/'.$this->session->userdata('logotipo')))	
				{
					echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 60px; max-height: 60px" />';
				}
			echo'
			</td> 
			<td align="center">
			<span style="font-size:18px">'.$configuracion->nombre.'</span>
			<br />
			<span style="font-size:14px">Relación de salida de mercancía</span>
			</td>
			<td width="20%">';
				if(strlen($this->session->userdata('logotipo')) and file_exists('img/logos/'.$this->session->userdata('logotipo')))	
				{
					echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 60px; max-height: 60px" />';
				}
			echo'
			</td> 
			<td width="14%">
				<table class="admintable" width="100%" >
					<tr>
						<th class="entregas">Folio</th>
					</tr>
					<tr>
						<td class="entregas" align="center">'.$registro->folio.'</td>
					</tr>

					<tr>
						<th class="entregas">Fecha</th>
					</tr>
					<tr>
						<td class="entregas" align="center">'.obtenerFechaMesCorto($registro->fechaRegistro).'</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>';

if($registros!=null)
{
	$i=1;
	echo '
	<table class="admintable" width="100%" >
		<tr>
			<th class="entregas" align="center">#</th>
			<th class="entregas" align="center">FECHA</th>
			<th class="entregas" align="center">CLIENTE</th>
			<th class="entregas" align="center">TOTAL</th>
			<th class="entregas" align="center">PAGADO</th>
			<th class="entregas" align="center">X COBRAR</th>
			<th class="entregas" align="center">NOTA FAC.</th>
		</tr>';	
	
	foreach($registros as $row)
	{
		$saldo	= $row->total-$row->pagado;

		echo '
		<tr>
			<td align="center">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaCompra).'</td>
			<td align="center">'.$row->cliente.'</td>
			<td align="right">$'.number_format($row->total,2).'</td>
			<td align="center">'.($saldo==0?'<img src="'.base_url().'img/success.png" width="16" />':'').'</td>
			<td align="center">'.($saldo>0?'<img src="'.base_url().'img/success.png" width="16" />':'').'</td>
			<td align="center">'.$row->estacion.$row->folio.'</td>
		</tr>';

		$i++;
	}
	
	echo '</table>';
}

?>
