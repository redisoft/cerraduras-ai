<?php
if(!empty ($direcciones))
{
	echo '
	<table width="100%" class="admintable" >
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" align="center">RFC</th>
			<th class="encabezadoPrincipal" align="center">Empresa</th>
			<th class="encabezadoPrincipal" align="center">Teléfono</th>
			<th class="encabezadoPrincipal" align="center">Tipo</th>
			<th class="encabezadoPrincipal" width="25%" align="center">Acciones</th>
		</tr>';
	?>
	
	<?php
	$i=1;
	foreach ($direcciones as $row)
	{
		$estilo=$i%2>0?' class="sinSombra" ':' class="sombreado" ';
		
		?>
        <tr <?php echo $estilo?> id="filaDirecciones<?php echo $row->idDireccion?>">
            <td align="center"> <?php echo $i?> </td>
            <td align="center" valign="middle"><?php echo $row->rfc ?></td>
            <td align="center" valign="middle"><?php echo $row->razonSocial ?></td>
            <td align="center" valign="middle"><?php echo $row->telefono ?></td>
            <td align="center" valign="middle"><?php echo obtenerTipoDireccion($row->tipo) ?></td>
            <td align="center" valign="middle" class="vinculos">
                
                <!--<img src="<?php echo base_url()?>img/add.png" width="22" height="22" title="Agregar a producto" onClick="agregarDireccionProducto(<?php echo $row->idDireccion?>,'<?php echo $row->nombre?>','<?php echo $row->color?>')" >
                 &nbsp;&nbsp;&nbsp;-->
                
                <img id="btnEditarDirecciones<?php echo $i?>" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar area" onClick="obtenerDireccionesEditar('<?php echo $row->idDireccion?>')" >
                
                &nbsp;&nbsp;
                <img id="btnBorrarDirecciones<?php echo $i?>" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar area" onClick="borrarDirecciones(<?php echo $row->idDireccion?>)" >
                <br />
               <!-- <a id="a-btnEditarDirecciones<?php echo $i?>">Agregar</a>-->
                <a id="a-btnEditarDirecciones<?php echo $i?>">Editar</a>
                <a id="a-btnBorrarDirecciones<?php echo $i?>">Borrar</a>
                
                <?php
                if($row->relaciones>0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnBorrarDirecciones'.$i.'\');
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