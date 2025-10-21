
<?php
echo '
<form id="frmCfdiVentas" name="frmCfdiVentas">
	
	<table class="admintable" width="100%" >
		<tr>
			<td class="etiquetaGrande">Dirección: </td>

			<td class="textoGrande" id="obtenerDireccionesCliente">
				<select style="width:550px" id="selectDireccionesCfdi" name="selectDireccionesCfdi" class="cajas" >
					<option value="0">Seleccione</option>';

					foreach($direcciones as $row)
					{
						echo '<option value="'.$row->idDireccion.'">'.$row->razonSocial.', '.$row->calle.' '.$row->numero.' '.$row->colonia.'</option>';
					}

				echo'
				</select>
			</td>
		</tr>
			
		<tr>
			<td class="etiquetaGrande">Emisor: </td>
			<td class="textoGrande">
				<select style="width:400px" id="selectEmisoresVenta" name="selectEmisoresVenta" class="cajas" onchange="obtenerFolio()">
					<option value="0">Seleccione</option>';
				
					foreach($emisores as $row)
					{
						echo '<option selected="selected" value="'.$row->idEmisor.'">(Serie '.$row->serie.') '.$row->rfc.', '.$row->nombre.'</option>';
					}
				
				echo'
				</select>
				<!--<label style="font-size:12px">Folio: </label><label id="obtenerFolio" style="font-size:12px" class="textoGrande"></label>-->
			</td>
		</tr>
		
		<tr>
			<td class="etiquetaGrande">Forma de pago: </td>
			<td class="textoGrande">
				
				<select style="width:400px" id="selectFormaPagoVenta" name="selectFormaPagoVenta" class="cajas"  >';
				
				foreach($formasSat as $row)
				{
					echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
				}
				
				echo'
				</select>
				
			</td>
		</tr>
		
		
		
		
		<tr>
			<td class="etiquetaGrande">Método de pago:</td>
			<td class="textoGrande">
				<select style="width:400px" id="selectMetodoPagoVenta" name="selectMetodoPagoVenta" class="cajas"  >';
				
				foreach($metodos as $row)
				{
					echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->concepto.'</option>';
				}
				
				echo'
				</select>
				
				
			</td>
			
		</tr>
		<tr>
			<td class="etiquetaGrande">Uso del CFDI: </td>
			<td class="textoGrande" >
				<select style="width:400px" id="selectUsoCfdiVenta" name="selectUsoCfdiVenta" class="cajas"  >';
				
				foreach($usos as $row)
				{
					echo '<option value="'.$row->clave.'">'.$row->clave.', '.$row->descripcion.'</option>';
				}
				
				echo'
				</select>
			</td>
		</tr>
		
		<!--<tr>
			<td class="etiquetaGrande">Condiciones de pago:</td>
			<td class="textoGrande">
				<input type="text" style="width:400px" class="cajas" id="txtCondicionesPagoVenta" name="txtCondicionesPagoVenta" value="Pago en una sola exhibición" />
			</td>
		</tr>-->
		
		<tr>
		<td class="etiquetaGrande">Condiciones de pago:</td>
		<td class="textoGrande">
			<select id="txtCondicionesPagoVenta" name="txtCondicionesPagoVenta" class="cajas" style="width:400px">
				<option>CONTADO</option>
				<option>CRÉDITO</option>
			</select>
		</td>
	</tr>
	</table>
	</div>
</form>';