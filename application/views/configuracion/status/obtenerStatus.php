<?php
if(!empty ($status))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" width="50%" align="center">Nombre</th>
			<th class="encabezadoPrincipal" align="center">Color</th>
			<th class="encabezadoPrincipal" align="center">Tipo</th>
			<th class="encabezadoPrincipal" align="center">Igual a</th>
			<th class="encabezadoPrincipal" width="17%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=1;
	foreach ($status as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
        <tr <?php echo $estilo?> id="filaStatus<?php echo $row->idStatus?>">
            <td align="center"> <?php echo $i?> </td>
            <td align="center" valign="middle"><?php echo $row->nombre ?></td>
            <td align="center" valign="middle">
				<div class="circuloStatusLista" style="background-color: <?php echo $row->color?>"></div>
            </td>
            <td align="center" valign="middle"><?php echo $row->cliente=='1'?'Cliente':'Proveedor' ?></td>
            <td align="center" valign="middle"><?php echo $row->igual ?></td>
            <td align="center" valign="middle" class="vinculos">

                <img id="btnEditarStatus<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar servicio" onClick="accesoEditarStatus('<?php echo $row->idStatus?>')" >
                
                &nbsp;&nbsp;
                <img id="btnBorrarStatus<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar servicio" onClick="confirmarBorrarStatus(<?php echo $row->idStatus?>)" >
                <br />
                <a id="a-btnEditarStatus<?php echo $i?>">Editar</a>
                <a id="a-btnBorrarStatus<?php echo $i?>">Borrar</a>
                
                <?php 

					if($permiso[2]->activo==0 or $row->sistema=='1')
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnEditarStatus'.$i.'\');
						</script>';
					}
					
					if($permiso[3]->activo==0 or $row->sistema=='1')
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnBorrarStatus'.$i.'\');
						</script>';
					}
				?>
            </td>
		</tr>
		
		<?php
		
		$i++;
	}
	
	echo '</table>';
}
else
{
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}
?>