<table class="admintable" width="100%">
    <tr>
    	<th colspan="2">Detalles de corte de caja</th>
    </tr>
    
	<tr>
    	<td class="key">Fondo de caja</td>
        <td>$<?php echo number_format($fondoCaja,decimales)?></td>
    </tr>
    
    <?php
    foreach($formas as $row)
	{
		echo '
		<tr>
			<td class="key">'.$row->forma.'</td>
			<td>$'.number_format($row->pago,decimales).'</td>
		</tr>';
	}
	?>
    
    
    <tr>
    	<td class="key">Retiro de efectivo</td>
        <td>$<?php echo number_format($retiros,decimales)?></td>
    </tr>
    
</table>
