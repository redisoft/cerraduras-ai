<?php
echo'
<form id="frmDireccionesCliente">
	<table class="admintable" width="100%;">
		<tr>
			<th>#</th>
			<th>Calle</th>
			<th>Número</th>
			<th>Colonia</th>
			<th>Código postal</th>
			<th>Ciudad</th>
			<th>Estado</th>
			<th>Referencia</th>
		</tr>';
		
		$i=1;
		foreach($direcciones as $row)
		{
			echo '
			
			<input type="hidden" class="cajas" name="txtIdDireccion'.$i.'" id="txtIdDireccion'.$i.'" value="'.$row->idDireccion.'"/>
			<tr>
				<td>'.$i.'</td>
				<td align="center"><input type="text" class="cajas" name="txtCalleEntrega'.$i.'" id="txtCalleEntrega'.$i.'" style="width:200px" value="'.$row->calle.'"/></td>
				<td align="center"><input type="text" class="cajas" name="txtNumeroEntrega'.$i.'" id="txtNumeroEntrega'.$i.'" style="width:70px" value="'.$row->numero.'"/></td>
				<td align="center"><input type="text" class="cajas" name="txtColoniaEntrega'.$i.'" id="txtColoniaEntrega'.$i.'" style="width:100px" value="'.$row->colonia.'"/></td>
				<td align="center"><input type="text" class="cajas" name="txtCodigoPostalEntrega'.$i.'" id="txtCodigoPostalEntrega'.$i.'" style="width:80px" value="'.$row->codigoPostal.'" maxlength="5"/></td>
				<td align="center"><input type="text" class="cajas" name="txtLocalidadEntrega'.$i.'" id="txtLocalidadEntrega'.$i.'" style="width:140px" value="'.$row->ciudad.'"/></td>
				<td align="center"><input type="text" class="cajas" name="txtEstadoEntrega'.$i.'" id="txtEstadoEntrega'.$i.'" style="width:140px" value="'.$row->estado.'"/></td>
				<td align="center"><input type="text" class="cajas" name="txtReferenciaEntrega'.$i.'" id="txtReferenciaEntrega'.$i.'" style="width:140px" value="'.$row->referencia.'"/></td>
			</tr>';
			
			$i++;
		}
		
		echo'
		
		
	</table>
</form>';