<?php
if($registros!=null)
{
	$this->load->view('reportes/caja/encabezado');
	
	echo '
	<table class="admintable" width="100%">';
	
	$i=1;
	foreach($registros as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';

		if($row->tipoRegistro>0)
		{
			$folio	=	obtenerFolioRegistro($row->tipoRegistro).configurarFolioTipo($row->folio);
			$totales-=$row->importe;
		}
		else
		{
			$folio	= $row->folio.' - '.$row->estacion;
			$totales+=$row->importe;
		}

		echo '
		<tr '.$estilo.'>
			<td width="4%" align="right">'.$i.'</td>
			<td width="36%" align="center">'.$folio.'</td>
			<td width="30%" align="right">$'.number_format($row->importe,2).'</td>
			<td width="30%" align="center">'.obtenerHora($row->fecha).'</td>
		</tr>';
		
		$i++;
	}
	
	$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
	
	echo '
	<tr '.$estilo.'>
		<td colspan="2" align="right" class="totales">Total</td>

		<td align="right" class="totales">$'.number_format($totales,2).'</td>
		<td align="center"></td>
	</tr>';
	
	echo '</table>';
}
else
{
	echo '<div class="Error_validar">Sin registros</div>';
}
?>