<?php 
if(!empty($materiales))
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagMateriales">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:3%;">#</th>
			<th class="encabezadoPrincipal" align="center" width="8%" >Código interno</th>
			<th class="encabezadoPrincipal" width="20%">
			<?php echo sistemaActivo=='IEXE'?'Insumos':'Materia prima' ?>
			 <?php
			 	echo '<img onclick="ordenMateriaPrima('.($orden=='asc'?'\'desc\'':'\'asc\'').')" src="'.base_url().'img/'.($orden=='asc'?'ocultar':'mostrar').'.png" width="17" />';
		 	 ?>
			</th>
            <th class="encabezadoPrincipal" >Unidad</th>
			<th class="encabezadoPrincipal" align="center" style="width:12%;">Proveedor</th>
			
            <th class="encabezadoPrincipal" >Conversión</th>
			<th class="encabezadoPrincipal" width="9%">Costo </th>
			<th class="encabezadoPrincipal" width="9%">Costo promedio </th>
			<th class="encabezadoPrincipal" >Inventario </th>
			<th class="encabezadoPrincipal" style="width:22%;">Acciones</th>               
		</tr>
	
	<?php
	$i=$inicio;
	foreach($materiales as $row)
	{
		$estilo	= $i%2>0?'class="sinSombra"':'class="sombreado"';
	
		?>
		<tr <?php echo $estilo?> id="<?php echo 'filaMaterial'.$row->idMaterial.'_'.$row->idProveedor?>">
			<td align="right" valign="middle"> <?php print($i); ?> </td>	
			<td align="left" valign="middle"> <?php echo $row->codigoInterno ?> </td>
			<td align="left" valign="middle"> 
			<?php 
			
				echo $row->nombre;
				
				if($row->produccion!=0)
				{
					echo ' <i style="font-weight: 100">(Producido en la empresa)</i>';
				}
			
			$faltante	= ($row->stock-$row->stockMinimo)*-1;
			
			?> 
			</td>
            <td align="center" valign="middle"> <?php echo $row->descripcion ?> </td>
			<td align="left" valign="middle"> <?php echo $row->nombreProveedor ?> </td>
			
            <td align="center" valign="middle"> <?php echo $this->materiales->obtenerConversion($row->idConversion) ?> </td>
			<td align="right" valign="middle"> $ <?php print(number_format($row->costo,4)); ?> </td>
			<td align="right" valign="middle"> $ <?php print(number_format($row->costoPromedio,4)); ?> </td>
			<td align="right" valign="middle"><?php print(number_format($row->inventario-$row->salidas,4)); ?></td>
			<td align="left"   valign="middle">
                &nbsp;&nbsp;&nbsp;
                <img id="proveedorMaterial<?php echo $i?>"  onclick="obtenerTodosProveedores('<?php echo $row->idMaterial?>')" src="<?php echo base_url()?>img/proveedores.png" style="width:22px; height:22px;" title="Agregar proveedor" />
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnEditarMaterial<?php echo $i?>" onclick="accesoEditarMaterial('<?php echo $row->idMaterial?>',<?php echo $row->idProveedor?>)" src="<?php echo base_url()."img/edit.png"?>" width="22" height="22" title="Editar"  /></a>
                
                &nbsp;&nbsp;&nbsp;&nbsp;
                <img id="btnBorrarMaterial<?php echo $i?>" onclick="confirmarBorrarMaterial(<?php echo $row->idMaterial?>,<?php echo $row->idProveedor?>)" src="<?php echo base_url()."img/borrar.png"?>" width="22" height="22" title="Borrar" />
                     
                &nbsp;&nbsp;&nbsp;&nbsp;
                
                <img id="btnSalidas<?php echo $i?>" src="<?php echo base_url()."img/mermas.png"?>" width="22" height="22" onclick="obtenerMermasMaterial('<?php echo $row->idMaterial?>','<?php echo $row->idProveedor?>')" title="Salidas"/>
				&nbsp;&nbsp;
                
                <img id="btnFaltantes<?php echo $i?>" onclick="informacion('<?php echo $row->nombre ?>','<?php echo number_format($faltante,4).' '. $row->descripcion?>')" src="<?php echo base_url()."img/warning.png"?>" width="22" height="22" title="Nueva compra"/>
				
                <br /> 
                
			<?php
			echo'
			<a id="a-proveedorMaterial'.$i.'">Proveedor</a> 
			<a id="a-btnEditarMaterial'.$i.'">Editar</a>
			&nbsp;<a id="a-btnBorrarMaterial'.$i.'">Borrar</a>
			&nbsp;
			<a id="a-btnSalidas'.$i.'">Salidas</a>
			<a id="a-btnFaltantes'.$i.'">Alerta</a>';
			
			if($permiso[1]->activo==0 or $row->produccion==1)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'proveedorMaterial'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0 or $row->produccion==1)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarMaterial'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnFaltantes'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0 or $row->produccion==1)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarMaterial'.$i.'\');
				</script>';
			}
			
			if($row->stock>$row->stockMinimo)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnFaltantes'.$i.'\');
				</script>';
			}
		  ?>
		  </td>
		</tr>
	
	<?php
	$i++;
	 }
	?>
	</table>
	<?php
	
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagMateriales">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de '.(sistemaActivo=='IEXE'?'insumos':'materia prima').'</div>';
}
?>
