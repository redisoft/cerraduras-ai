<table class="admintable" width="100%">
	<tr>
    	<th colspan="2" class="encabezadoPrincipal">
        	Detalles de requisición
        </th>
    </tr>
	<tr>
        <td class="key">Requisición:</td>
        <td><?php echo requisicion.$requisicion->folio?></td>
    </tr>
    
    <tr>
        <td class="key">Fecha requisición:</td>
        <td><?php echo obtenerFechaMesCorto($requisicion->fechaRequisicion)?></td>
    </tr>
    
    <tr>
        <td class="key">Fecha arribo:</td>
        <td><?php echo obtenerFechaMesCorto($requisicion->fechaArribo)?></td>
    </tr>

    
    
    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <?php echo $requisicion->comentarios?>
        </td>
    </tr>
</table>

<table class="admintable" width="100%" id="tablaRequisiciones">
	<tr>
    	<th colspan="4" class="encabezadoPrincipal">
        	Detalles de materia prima
        </th>
    </tr>
	<tr>
    	<th width="3%">#</th>
        <th width="60%">Materia prima</th>
        <th width="15%">Unidad</th>
        <th>Cantidad</th>
    </tr>
    
    <?php
	$i=1;
    foreach($materiales as $row)
	{
		echo '
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td>'.$i.'</td>
			<td>'.$row->material.'</td>
			<td>'.$row->unidad.'</td>
			<td align="center"> '.round($row->cantidad,decimales).'</td>
		</tr>';
		
		$i++;
	}
	?>
</table>