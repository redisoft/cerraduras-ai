<?php
if($productos==null)
{
	echo
	'<div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		No hay registros de materia prima para este proveedor.
	</div>';
	
	return;
}

echo'
<div style="width:90%;">
	<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
</div>';

echo'
<table class="admintable" style="width:100%">
	<tr>
		<th width="3%" align="right">#</th>
		<th>'.(sistemaActivo=='IEXE'?'Insumos':'Materia prima').'</th>
		<th>Unidad</th>
		<th align="left" width="35%">Proveedor</th>
		<th width="11%">Precio</th>
		<th width="8%">Stock</th>
		<th width="8%">Cantidad</th>
	</tr>';
 
 $i = $limite;

 foreach($productos as $row)
 {
	 $precio=$row->costo;
	 
	 if($this->input->post('pagina')=='0')
	 {
		 $precio=$row->costo;
	 }
	 
	$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
	$onclick	='onclick="agregarProductoKit('.$i.',\'si\')"';
	
	echo'
	<tr '.$estilo.'>
		<td '.$onclick.' align="right">'.$i.'</td>
		<td '.$onclick.'>'.$row->nombre.' </td>
		<td '.$onclick.' align="center">'.$row->unidad.' </td>
		<td>
			<div>'.$row->empresa.'</div>
			
			<div style="text-align:center; width: 100px">
				<img title="Agregar proveedor" src="'.base_url().'img/proveedores.png" width="18" onclick="accesoAgregarProveedorCompraMaterial('.$row->idMaterial.')" style="cursor:pointer" /> 
				<br />
				<a>Asignar proveedor</a>
			</div>
		</td>
		<td align="right" valign="middle">
			$<input type="text" class="cajas" style="width:70px"  id="precio'.$i.'" value="'.round($precio,decimales).'" onkeypress="return soloDecimales(event)" onchange="editarPrecioMaterial('.$i.','.$row->idMaterial.','.$row->idProveedor.')"/> 
			&nbsp;
			<img title="Actualizar precio" src="'.base_url().'img/guardar.png" width="18" onclick="editarPrecioMaterial('.$i.','.$row->idMaterial.','.$row->idProveedor.')" style="cursor:pointer; display: none" />
		</td>
		<td '.$onclick.' align="center">'.number_format($row->inventario-$row->salidas,4).'</td>
		<td align="center">
			<input onchange="agregarProductoKit('.$i.',\'no\')" type="text" value="0" style="width:50px" class="cajas" id="cantidad'.$i.'" onkeypress="return soloDecimales(event)" maxlength="15"/>
			<input type="hidden" value="'.$row->idMaterial.'" id="agregar'.$i.'" />
			<input type="hidden"  id="txtNombreProveedor'.$i.'" value="'.$row->empresa.'"/>
			<input type="hidden"  id="descripcion'.$i.'" value="'.$row->nombre.'"/>
			<input type="hidden"  id="txtProveedor'.$i.'" value="'.$row->idProveedor.'"/>
		</td>
	</tr>';
	 
	 $i++;
 }
 
 echo'</table>';
 $i=1;
?>