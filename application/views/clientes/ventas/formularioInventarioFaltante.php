<?php
echo '
<script>
	$("#tablaFaltantes tr:even").addClass("sombreado");
	$("#tablaFaltantes tr:odd").addClass("sinSombra");
</script>
<div id="registrandoTraspaso"></div>
<form id="frmTraspasos" name="frmTraspasos">
	
	<input type="hidden" id="txtNumeroProductosTraspaso" name="txtNumeroProductosTraspaso" value="'.$this->input->post('numeroProductos').'"  />
	
	<table class="admintable" width="100%" id="tablaFaltantes">
		<tr>
			<th>#</th>
			<th>Producto</th>
			<th>Cantidad requerida</th>
			<th>Cantidad disponible</th>
			<th>Cantidad faltante</th>
			<th>Tienda salida</th>
		</tr>';
	
	$f=0;
	for($i=1;$i<$this->input->post('numeroProductos');$i++)
	{
		$cantidad	= $this->input->post('txtCantidadProducto'.$i);
		$disponible	= $this->input->post('txtStockDisponible'.$i);
		$faltante	= $cantidad-$disponible;
		
		if(strlen($this->input->post('txtCantidadProducto'.$i))>0)
		{
			if($cantidad>$disponible)
			{
				
				echo'
				<tr>
					<td>'.($f+1).'</td>
					<td>'.$this->input->post('txtNombreProducto'.$i).'</td>
					<td align="center">'.number_format($cantidad,2).'</td>
					<td align="center">'.number_format($disponible,2).'</td>
					<td align="center">'.number_format($cantidad-$disponible,2).'</td>
					<td align="center">
						<input type="hidden" id="txtIdProductoTraspaso'.$f.'" name="txtIdProductoTraspaso'.$f.'" value="'.$this->input->post('txtIdProducto'.$i).'"  />
						<input type="hidden" id="txtCantidadDisponibleTraspaso'.$f.'" name="txtCantidadDisponibleTraspaso'.$f.'" value="0"  />
						
						<input type="hidden" id="txtFilaProducto'.$f.'" name="txtFilaProducto'.$f.'" value="'.$i.'"  />
						
						<input type="hidden" id="txtCantidadTraspaso'.$f.'" name="txtCantidadTraspaso'.$f.'" value="'.($cantidad-$disponible).'"  />
						<select class="cajas" id="selectTiendaOrigen'.$f.'" name="selectTiendaOrigen'.$f.'" style="width:120px" onchange="obtenerInventarioProductoTraspaso('.$f.')">';
							
							if($idTienda!=0)
							{
								echo' <option value="0">Matríz</option>';
							}
							
							foreach($tiendas as $row)
							{
								if($row->idTienda!=$idTienda)
								{
									echo' <option value="'.$row->idTienda.'">'.$row->nombre.'</option>';
								}
							}
						
						echo'
						</select>
						
						<div id="cantidadDisponible'.$f.'"></div>
						
						<script>
						$(document).ready(function()
						{
							obtenerInventarioProductoTraspaso('.$f.')
						});
						</script>
						
					</td>
				</tr>';
				
				$f++;
			}
		}
	}

echo '
	</table>
</form>';