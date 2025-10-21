<?php
$idPersonal =$this->input->post('idPersonal');
$dias 		=$this->input->post('dias');
$bancos		=$this->bancos->obtenerBancos();
$personal	=$this->administracion->obtenerRegistroPersonal($idPersonal);

$departamentos	=$this->administracion->obtenerDepartamentos();
$nombres		=$this->administracion->obtenerNombres();
$productos		=$this->administracion->obtenerProductos();
$gastos			=$this->administracion->obtenerTipoGasto();

echo '<div class="ui-state-error" ></div>';
echo'
<input type="hidden" id="txtIdPersonal" value="'.$idPersonal.'" />
<table class="admintable" width="100%">
	<tr>
		<td class="key">Fecha:</td>
		<td>
			<input type="text" class="cajas" id="txtFechaEngreso" value="'.date('Y-m-d H:i').'" readonly="readonly" />
			<script>
				$("#txtFechaEngreso").timepicker();
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="key">Concepto:</td>
		<td>
			<div id="obtenerProductos" style="float:left; width:300px">
				<select class="cajas" id="selectProductos" style="width:290px">
					<option value="0">Seleccione</option>';
				
					foreach($productos as $row)	
					{
						echo '<option value="'.$row->idProducto.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</div>
			
			<!--img id="btnProductos" src="'.base_url().'img/agregar.png" width="20" title="Agregar producto" height="20" /-->
			<script>
			$("#btnProductos").click(function(e)
			{
				formularioProductos();
				$("#ventanaFormularioProductos").dialog("open");
			});
			</script>

			<br />
			<br />

			<!--input type="checkbox" id="chkCajaChica" /> <label>¿Es caja chica?</label-->
		</td>
	</tr>
	<tr>
		<td class="key">Importe:</td>
		<td>
			<input type="text" class="cajas" id="txtImporte" readonly="readonly" value="'.round($personal->salario*$dias).'"/>
		</td>
	</tr>
	<tr>
		<td class="key">Iva:</td>
		<td>
			&nbsp;&nbsp;
			<input type="checkbox" id="chkIva" />
			<input readonly="readonly" type="hidden" style="width:100px" class="cajas" value="0" id="txtIva" />
		</td>
	</tr>
	
	<tr>
		<td class="key">Descripción del producto:</td>
		<td>
			<input type="text" class="cajas" id="txtDescripcionProducto" name="txtDescripcionProducto" style="width:250px" value="'.$personal->nombre.'" />
			
		</td>
	</tr>
	
	<tr>
		<td class="key">Departamento:</td>
		<td>
			<div id="obtenerDepartamentos" style="float:left; width:300px">
				<select class="cajas" id="selectDepartamento" style="width:290px">
					<option value="'.$personal->idDepartamento.'">'.$personal->departamento.'</option>
				</select>
			</div>
			<!--img id="btnDepartamentos" src="'.base_url().'img/agregar.png" width="20" title="Agregar departamento" height="20" /-->
			
			<script>
			$("#btnDepartamentos").click(function(e)
			{
				formularioDepartamentos();
				$("#ventanaFormularioDepartamentos").dialog("open");
			});
			</script>
		</td>
	</tr>
	<tr>
		<td class="key">Tipo:</td>
		<td>
			<div id="obtenerTipoGasto" style="float:left; width:300px">
				<select class="cajas" id="selectTipoGasto" style="width:290px">
					<option value="0">Seleccione</option>';
					foreach($gastos as $row)	
					{
						echo '<option value="'.$row->idGasto.'">'.$row->nombre.'</option>';
					}
					
				echo'
				</select>
			</div>
			<!--img id="btnTipoGasto" src="'.base_url().'img/agregar.png" width="20" title="Agregar Tipo de gasto" height="20" /-->
			<script>
			$("#btnTipoGasto").click(function(e)
			{
				formularioTipoGastos();
				$("#ventanaFormularioGastos").dialog("open");
			});
			</script>
		</td>
	</tr>
	
	<tr>
		<td class="key">Forma de pago:</td>
		<td>
			<select id="selectTipoPago" name="selectTipoPago" class="cajas" style="width:120px;" onchange="opcionesCuenta()">
				<option value="Efectivo">Efectivo</option>
				<option value="Cheque">Cheque</option>
				<option value="Transferencia">Transferencia</option>
				
			</select>   
		</td>
	</tr>
	
	<tr style="display:none" id="contenedorNombres">
		<td class="key">Paguese por este documento a:</td>
		<td>
			<div id="obtenerNombres" style="float:left; width:300px">

				<select class="cajas" id="selectNombres" style="width:290px">
					<option value="0">Seleccione</option>';
				
				foreach($nombres as $row)	
				{
					echo '<option value="'.$row->idNombre.'">'.$row->nombre.'</option>';
				}
					
				echo'
				</select>
			</div>
			<!--img id="btnNombres" src="'.base_url().'img/agregar.png" width="20" title="Agregar nombre" height="20" /-->
			<script>
			$("#btnNombres").click(function(e)
			{
				formularioNombres();
				$("#ventanaFormularioNombres").dialog("open");
			});
			</script>
		</td>
	</tr>
	
	<tr style="display:none;" id="filaCheques">
		<td class="key">Número cheque:</td>
		<td>
			<input type="text" class="cajas" id="txtNumeroCheque" name="txtNumeroCheque" />   
		</td>
	</tr>
	
	<tr style="display:none;" id="filaTransferencia">
		<td class="key">Número Transferencia:</td>
		<td>
			<input type="text" class="cajas" id="txtNumeroTransferencia" name="txtNumeroTransferencia" />
		</td>
	</tr>
	
	<tr style="display:none;" id="filaNombre">
		<td class="key">Nombre del receptor:</td>
		<td>
			<input type="text" class="cajas" id="txtNombreReceptor" name="txtNombreReceptor" />
		</td>
	</tr>
	
	<tr>
	<td class="key">Banco:</td>
	<td> 
	 <select id="selectBancos" name="selectBancos" class="cajas" style="width:auto;" onchange="obtenerCuentas()" >
		<option value="0">Seleccione</option>';

		   foreach($bancos as $row)
		   {
			   print('<option value="'.$row->idBanco.'" >'.$row->nombre.'</option>');
		   }
		 
		echo'
		</select>
	</td>
</tr>
<tr>
	<td class="key">Cuenta:</td>
	<td id="filaCuenta">
		<select id="selectCuentas" name="selectCuentas" class="cajas" style="width:200px;" >
		 <option value="0">Seleccione</option>
		</select>
	</td>     
</tr>
	
	<tr>
		<td class="key">Comentarios:</td>
		<td>
			<textarea class="TextArea" id="txtComentarios" style="height:35px; width:290px"></textarea>
		</td>     
	</tr>
</table>';