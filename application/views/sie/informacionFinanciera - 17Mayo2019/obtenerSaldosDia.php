
    <?php

	echo '
	<table class="admintable" width="100%">
		<tr>
			<th class="resaltadoIexe">Saldo al cierre del día</th>
		</tr>
		
		<tr>
			<td align="center">$'.number_format($saldoDia,decimales).'</td>
		</tr>
		
		<tr>
			<th class="resaltadoIexe">Saldo al '.obtenerFechaMesLargo($fecha2,0).'</th>
		</tr>
		
		<tr>
			<td align="center">$'.number_format($saldoDia2,decimales).'</td>
		</tr>
		
		<tr>
			<th class="resaltadoIexe">Saldo al '.obtenerFechaMesLargo($fecha3,0).'</th>
		</tr>
		
		<tr>
			<td align="center">$'.number_format($saldoDia3,decimales).'</td>
		</tr>
		
		<tr>
			<th class="resaltadoIexe">Saldo al '.obtenerFechaMesLargo($fecha4,0).'</th>
		</tr>
		
		<tr>
			<td align="center">$'.number_format($saldoDia4,decimales).'</td>
		</tr>';

	echo'
	</table>';
	?>



