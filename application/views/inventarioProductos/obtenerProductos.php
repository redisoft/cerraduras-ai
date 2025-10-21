<?php

echo '<input type="hidden" id="txtNumeroProductosAsignar" value="'.count($productos).'"/>' ;

if(!empty($productos))
{
	echo'
	<table class="admintable" width="100%">
	<tr>
		<td colspan="9">
			<ul id="pagination-digg" class="ajax-pagProductosInventario">'.$this->pagination->create_links().'</ul>
		</td>	
	</tr>';
		
	
	$i	= 1;
	$c	= $inicio+1;
	
	foreach($productos as $row)
	{
		 $iva			=$row->precioA*($this->session->userdata('iva')/100);
		 $precioVenta	=$row->precioA*(($this->session->userdata('iva')/100)+1);
	
		?>
		<tr <?php echo $i%2>0?'class="sinSombra"':'class="sombreado"'?> id="<?php echo 'filaInventarioProducto'.$row->idProducto?>">
			<td align="left" valign="middle" width="3%"> <?php print($c); ?> </td>
			<td align="center" valign="middle" width="15%">
                <div id="codigoBarras<?php echo $i?>" ></div>
                <script>
                    $("#codigoBarras<?php echo $i?>").barcode("<?php echo $row->codigoBarras?>", "code93",{barWidth:1, barHeight:40})
                </script>
                </td>
                <td align="center" valign="middle" width="10%"><?php print($row->codigoInterno); ?></td>
                <td align="center" valign="middle" width="10%" class="imagenesListaProducto">
                <?php
                
                $imagen	= '<img src="'.base_url().carpetaProductos.'default.png" />';
                    
                if(file_exists(carpetaProductos.$row->idProducto.'_'.$row->imagen) and strlen($row->imagen)>3)
                {
                    $imagen	= '<img src="'.base_url().'img/productos/'.$row->idProducto.'_'.$row->imagen.'" />';
                }
                    
                echo $imagen;        
                
                ?>
			</td>
		   
			<td align="left" valign="middle" width="20%"> 
				<?php 
				echo $row->nombre;
				
				if($row->reventa==1)
				{
					echo ' <i>(Producto de reventa)</i>';
				}
				
				$iva=0;
			 	?> 
			</td>
            <!--<td align="center" valign="middle"> <?php  echo $row->departamento?> </td>-->
			<td align="center" valign="middle" width="8%"> <?php  echo round($row->stock,decimales)?> </td>
            
            <td align="center" valign="middle" width="5%"> <?php  echo round($row->stockMinimo,decimales)?> </td>

			<td align="center" valign="middle" width="7%">$ <?php echo number_format($precios=='1'?$row->precioA:$row->precioImpuestos,decimales); ?></td>
			<td align="center"   valign="middle" width="22%"> 
			<?php
			if($tiendaLocal=='0')
			{
				echo'
				&nbsp;
				<img id="btnAsignarPorcentaje'.$i.'" onclick="formularioPorcentaje('.$row->idProducto.')" src="'.base_url().'img/descuento.png" width="22" height="22"  title="Actualizar" />
				&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/ficha.png" width="22" style="cursor:pointer" onclick="obtenerDetalleProducto(\''.$row->idProducto.'\')" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnEditarProducto'.$i.'" src="'.base_url().'img/editar.png" width="22" style="cursor:pointer" onclick="obtenerDetallesProducto(\''.$row->idProducto.'\')" />

				&nbsp;&nbsp;&nbsp;
				<a id="btnBorrarProducto'.$i.'" onclick="accesoBorrarProducto('.$row->idProducto.',\'¿Esta seguro que desea borrar el producto?\')">
					<img src="'.base_url().'img/borrar.png"'.'width="22" height="22" title="Borrar Producto" border="0"/>
				</a>

				&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnAgregarProveedorProducto'.$i.'" src="'.base_url().'img/proveedores.png" width="22" style="cursor:pointer" onclick="obtenerProveedoresProductos(\''.$row->idProducto.'\')" />
				&nbsp;&nbsp;';

				if(sistemaActivo=='cerraduras')
				{
					if($row->stock<$row->stockMinimo)
					{
						$cantidad	= $row->stockMinimo-$row->stock;
						$nombre		= reemplazarApostrofe($row->nombre);

						echo '&nbsp;&nbsp;<img src="'.base_url().'img/warning.png" width="22" style="cursor:pointer" onclick="alertaProducto(\''.$nombre.'\',\''.$cantidad.'\')" />';
					}

				}


				echo'
				<br />
				<a>Actualizar</a>
				<a>Detalles</a>
				<a id="a-btnEditarProducto'.$i.'">Editar</a>
				<a id="a-btnBorrarProducto'.$i.'">Borrar</a>
				<a id="a-btnAgregarProveedorProducto'.$i.'">Proveedor</a>';

				if(sistemaActivo=='cerraduras')
				{
					if($row->stock<$row->stockMinimo)
					{
						echo ' <a>Alerta</a>';
					}
				}

				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarProducto'.$i.'\');
					</script>';
				}

				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarProducto'.$i.'\');
					</script>';
				}

				if($permiso[1]->activo==0 or $row->reventa==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnAgregarProveedorProducto'.$i.'\');
					</script>';
				}
			}
			
			?>
			
			</td>
		</tr>
		
		 <?php
		 $i++;
		 $c++;
	 }
	?>
	</table>	
	<?php
	
	if(count($productos)>10)
	{
		echo'
		<div style="width:90%; margin-top:0%;">
			<ul id="pagination-digg" class="ajax-pagProductosInventario">'.$this->pagination->create_links().'</ul>
		</div>';
	}
	
}
else
{
	echo '<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de productos</div>';
}