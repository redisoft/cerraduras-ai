<table class="admintable" width="100%">
    
	<tr>
        <td class="key">Conteo:</td>
        <td><?php echo conteos.$conteo->folio?></td>
    </tr>
    
    <tr>
        <td class="key">Usuario:</td>
         <td><?php echo $conteo->usuario?></td>
    </tr>
    
     <tr>
        <td class="key">Tienda:</td>
        <td><?php echo $conteo->tienda?></td>
    </tr>

    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <?php echo $conteo->comentarios?>
        </td>
    </tr>
</table>

<table class="admintable" width="100%" id="tablaConteos">
	<thead>
        <tr>
            <th width="3%">#</th>
            <th width="20%">Código</th>
            <th width="60%">Producto</th>
            <th>Cantidad</th>
        </tr>
    </thead>
    <tbody>
    	<?php
		$i=1;
        foreach($productos as $row)
		{
			echo '
			<tr '.($i%2>0?'class="sombreado"':'class="sinSombra"').'>
				<td>'.$i.'</td>
				<td>'.$row->codigoInterno.'</td>
				<td>'.$row->nombre.'</td>
				<td align="center">'.round($row->cantidad,decimales).'</td>
			</tr>';
			
			$i++;
		}
		?>
    
    </tbody>
</table>