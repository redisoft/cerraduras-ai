<?php
$proveedor	=$proveedor!=null?$proveedor->empresa:'';

echo'
<div class="ui-state-error" ></div>
<table class="admintable" width="100%">
	<tr>
		<td class="key">Fecha:</td>
		<td>'.obtenerFechaMesCortoHora($egreso->fecha).'</td>
	</tr>
	<tr>
		<td class="key">Proveedor:</td>
		<td>'.$proveedor.'</td>     
	</tr>';
	
	if($egreso->idMaterial>0)
	{
		$material	=$this->materiales->obtenerMaterial($egreso->idMaterial);
		
		echo'
		<tr>
			<td class="key">Materia prima</td>
			<td>'.$material->nombre.'</td>
		</tr>';
	}
	
	echo'
	<tr>
		<td class="key">Descripción del producto:</td>
		<td>'.$egreso->producto.'</td>
	</tr>
	
	<tr>
		<td class="key">Cantidad</td>
		<td>'.round($egreso->cantidad,2).'</td>
	</tr>
	<tr>
		<td class="key">Importe</td>
		<td>'.round($egreso->pago,2).'</td>
	</tr>
	<tr>
		<td class="key">Iva</td>
		<td>';
			echo $egreso->incluyeIva==1?'Si':'No';
			echo'
		</td>
	</tr>

	<tr>
		<td class="key">Concepto</td>
		<td>';
			
			$producto	=$this->configuracion->obtenerProducto($egreso->idProducto);
			echo $producto!=null?$producto->nombre:'';
			
			echo'
		</td>
	</tr>
	
	<tr>
		<td class="key">Tipo de gasto:</td>
		<td>';
					
			$gasto	=$this->configuracion->obtenerGasto($egreso->idGasto);
			echo $gasto!=null?$gasto->nombre:'';
			
		echo'
		</td>
	</tr>
	<tr>
		<td class="key">Departamento</td>
		<td>';
				
			$departamento	=$this->configuracion->obtenerDepartamento($egreso->idDepartamento);
			echo $departamento!=null?$departamento->nombre:'';
					
			echo'
		</td>
	</tr>
	<tr>
		<td class="key">Forma de pago:</td>
		<td>'.$egreso->formaPago.'</td>
	</tr>';
	
	$mostrado	=$egreso->formaPago=='Cheque'?'':' style="display:none" ';
	echo'
	<tr '.$mostrado.' id="contenedorNombres">
		<td class="key">Paguese por este documento a:</td>
		<td>';
					
			$nombre	=$this->configuracion->obtenerNombre($egreso->idNombre);
			echo $nombre!=null?$nombre->nombre:'';
			
		echo'
		</td>
	</tr>';
	
	$activo=$egreso->formaPago=="Cheque"?'  ':' style="display:none;" ';
	
	echo'
	<tr '.$activo.' id="filaCheques">
		<td class="key">Número cheque:</td>
		<td>'.$egreso->cheque.'</td>
	</tr>';
	
	$activo=$egreso->formaPago=="Transferencia"?' ':' style="display:none;" ';
	
	echo'
	<tr '.$activo.' id="filaTransferencia">
		<td class="key">Número Transferencia:</td>
		<td>'.$egreso->transferencia.'</td>
	</tr>';
	
	$activo='style="display:none;" ';
	
	if($egreso->formaPago=="Transferencia" or $egreso->formaPago=="Cheque")
	{
		$activo='';
	}
	
	
	echo'
	<tr '.$activo.' id="filaNombre">
		<td class="key">Nombre del receptor:</td>
		<td>'.$egreso->nombreReceptor.'</td>
	</tr>
	
	<tr>
	<td class="key">Banco:</td>
	<td> ';

		  echo $banco!=null?$banco->nombre:'';
		 
		echo'
		</select>
	</td>
</tr>
<tr>
	<td class="key">Cuenta:</td>
	<td id="filaCuenta">';
		  
		echo $cuenta!=null?$cuenta->cuenta:'';
		   
		echo'
	</td>     
</tr>
<tr>
	<td class="key">Factura:</td>
	<td>'.$egreso->factura.'</td>     
</tr>
<tr>
	<td class="key">Remisión:</td>
	<td>'.$egreso->remision.'</td>     
</tr>

<tr>
	<td class="key">Comentarios:</td>
	<td>'.$egreso->comentarios.'</td>     
</tr>
</table>';