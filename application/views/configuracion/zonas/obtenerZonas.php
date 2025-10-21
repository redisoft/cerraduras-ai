<?php
if(!empty ($zonas))
{
	?>
	<table width="100%;" class="admintable">
		 <tr >
			<th width="10%" class="encabezadoPrincipal" align="center" valign="middle">#</th>
			<th width="50%" class="encabezadoPrincipal" align="center">Descripcion</th>
			<th width="10%" class="encabezadoPrincipal" align="center">Acciones</th>
		 </tr>
	   <tbody>
	
	<?php
	
	$i=1;
	foreach ($zonas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		?>
		<tr <?php echo $estilo?> id="filaZona<?php echo $row['idZona']?>">
            <td align="center"> <?php echo $i; ?> </td>
            <td align="center" valign="middle"><?php echo $row['descripcion']; ?></td>
            <td align="center" valign="middle">
            	<img id="btnEditarZona<?php echo $i?>" onclick="accesoEditarZona(<?php echo $row['idZona']?>)" src="<?php echo base_url()?>img/editar.png" width="22" height="22" title="Editar"  />
                &nbsp;&nbsp;
                <img id="btnBorrarZona<?php echo $i?>" onClick="confirmarBorrarZona(<?php echo $row['idZona']?>,'¿Esta seguro de borrar el registro?')" src="<?php echo base_url()?>img/borrar.png" width="22" height="22" title="Borrar"  />
                <br />
                <a id="a-btnEditarZona<?php echo $i?>" >Editar</a>
                <a id="a-btnBorrarZona<?php echo $i?>" >Borrar</a>
                
             	<?php
                if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarZona'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarZona'.$i.'\');
					</script>';
				}
            	?>
            </td>
		</tr>
	
	<?php
	
	$i++;
	
	}
	?>
	   </tbody>
	 </table>
	
	<?php
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:90%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}