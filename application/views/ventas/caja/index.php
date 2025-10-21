<script src="<?php echo base_url()?>js/ventas/catalogo/caja.js?v=<?php echo(rand());?>"></script>


<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
   
	
    <table class="toolbar" width="100%">
		<?php
		#onclick="formularioVentas()"
		echo '
		<td class="button" width="6%">
			<a onclick="formularioValesRetiros(2)" id="btnVales">
				<img src="'.base_url().'img/traspasos.png" width="30px;" height="30px;" title="Vales" alt="Vales" /><br />
			   Vales    
			</a>     
			
			<a onclick="formularioValesRetiros(1)" id="btnRetiros">
				<img src="'.base_url().'img/cobros.png" width="30px;" height="30px;" title="Vales" alt="Vales" /><br />
			   Retiros    
			</a>  
			<a onclick="formularioCorte()" id="btnCorte">
				<img src="'.base_url().'img/caja.png" width="30px;" height="30px;" title="Corte" alt="Corte" /><br />
			   Corte    
			</a>  
			
			<a onclick="obtenerReporteRetiros()" id="btnReporte">
				<img src="'.base_url().'img/categorias.png" width="30px;" height="30px;" title="Reporte entradas/salidas" alt="Reporte entradas/salidas" /><br />
			   Reporte E/S    
			</a>  
			
			<a onclick="formularioSaldoInicial()" id="btnSaldoInicial">
				<img src="'.base_url().'img/saldo.png" width="30px;" height="30px;" title="Saldo inicial" alt="Saldo inicial" /><br />
			   	Saldo inicial  
			</a>  
			
			<a onclick="obtenerVentasEfectivo()" id="btnReporteEfectivo">
				<img src="'.base_url().'img/dinero.png" width="30px;" height="30px;" title="Ventas efectivo" alt="Ventas efectivo" /><br />
			   	Ventas efectivo
			</a>  
			
		</td>';
		
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnVales\');
			</script>';
		}
		
		if($permiso[2]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnRetiros\');
			</script>';
		}
		
		if($permiso[3]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnCorte\');
			</script>';
		}
		
		if($permiso[4]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnReporte\');
				desactivarBotonSistema(\'btnSaldoInicial\');
				desactivarBotonSistema(\'btnReporteEfectivo\');
			</script>';
		}	
		
		?>
        </tr>
        
    </table>
    
   
</div>
<div class="listproyectos" style="float:none">
	<div id="buscandoTicket"></div>
	
	<div class="ventasCaja">
		<label style="font-weight: normal; display: none">Prefactura <input type="checkbox" id="chkPrefactura" /></label><br>
		<input type="text" id="txtBuscarTicket" name="txtBuscarTicket" class="cajasCaja"/>
		<input type="hidden" id="txtIdRol" name="txtIdRol" value="<?=$idRol?>" />
		<input type="hidden" id="txtIdUsuarioRegistro" name="txtIdUsuarioRegistro" value="<?=$idUsuario?>" />
		<br>
		Escanear ticket
	</div>

	<div id="ventanaCobrarVenta" title="Pagar venta">
		<div id="registrandoPagoCaja"></div>
		<form id="frmCobroCaja" name="frmCobroCaja" action="javascript:registrarPagoCaja()" >

			<input type="hidden" id="txtSaldoCaja" 		name="txtSaldoCaja" 		value="0"/>
			<input type="hidden" id="txtIdCotizacion" 	name="txtIdCotizacion" 		value="0"/>
			<input type="hidden" id="txtIdCliente" 		name="txtIdCliente" 		value="0"/>
			<input type="hidden" id="txtIdForma" 		name="txtIdForma" 			value="0"/>
			<input type="hidden" id="txtConcepto" 		name="txtConcepto" 			value=""/>
			<input type="hidden" id="txtNumeroFormas" 	name="txtNumeroFormas" 		value="1"/>
			<input type="hidden" id="txtNumeroPagos" 	name="txtNumeroPagos" 		value="1"/>
			
			<table class="admintable" width="100%">
				<tr>
					<td class="etiquetaGrande">Folio:</td>
					<td class="textoGrande" id="lblFolio"></td>
				</tr>
				<tr>
					<td class="etiquetaGrande">Total:</td>
					<td class="textoGrande" id="lblTotal">$0.00</td>
				</tr>

				<tr>
					<td class="etiquetaGrande">
					<img src="<?=base_url()?>img/add.png" width="22" onclick="cargarFormaPago()" id="btnCargarForma"/>
					Forma de pago:</td>
					<td class="textoGrande" >
						<div>
							<select class="cajas" id="selectFormasPago0" name="selectFormasPago0" style="width: 200px">
								<?php
								foreach($formas as $row)
								{
									if($row->idForma!=4)
									{
										echo '<option value="'.$row->idForma.'">'.$row->nombre.'</option>';
									}
								}
								?>
							</select>

							<input type="number" id="txtImporteCaja0" name="txtImporteCaja0" class="cajas" onKeyUp="calcularCambioCaja(); sumarFormasPagoCaja()" min="0.1" max="99999999" step="any" maxlength="8" placeholder="$ Importe" required="true"/>
					
						</div>
						<div id="lblForma"></div>

						<div id="lblTotalFormas" style="font-weight: bold; margin-top: 4px; display: none">Total: $ 0.00</div>
					</td>
				</tr>

				<!--tr>
					<td class="etiquetaGrande">Pagar:</td>
					<td class="textoGrande"></td>
				</tr-->

				<tr id="filaCambio">
					<td class="etiquetaGrande">Cambio:</td>
					<td class="textoGrande" id="lblCambio">$0.00</td>
				</tr>
				
				<tr>
					<td class="etiquetaGrande">Estatus:</td>
					<td class="textoGrande" id="lblEstatus">--</td>
				</tr>

			</table>
		</form>
	</div>
	
	<div id="ventanaValesRetiro" title="Vales y retiros">
		<div id="procesandoValesRetiro"></div>
		<div id="formularioValesRetiros"></div>
	</div>
	
	<div id="ventanaCorteCaja" title="Corte">
		<div id="procesandoCorteCaja"></div>
		<form id="frmCorte" action="<?=base_url()?>ventas/imprimirCorte" target="_blank" method="post">
			<table class="admintable" width="100%">
				<tr>
					<td class="key">Fecha:</td>
					<td>
						<span id="spnFechaCorte"><?=obtenerFechaMesCorto(date('Y-m-d'))?></span>
						<input type="text" id="txtFechaCorte" name="txtFechaCorte" value="<?=date('Y-m-d')?>" class="cajas" onChange="formularioCorte()" style="width: 100px; display: none " />
						
						<?php
						if($permiso[5]->activo=='1')
						{
							echo '<img src="'.base_url().'img/editar.png" width="22px" title="Editar fecha" onClick="accesoEditarFechaCorte(1)" id="btnFechaCorte" />';
						}
						?>
					</td>
				</tr>
				<tr>
					<td class="key">Estación:</td>
					<td>
						<select class="cajas" id="selectEstaciones" name="selectEstaciones" style="width: 200px" onChange="formularioCorte()">
							<option value="0">Caja</option>
							<?php
							foreach($estaciones as $row)
							{
								echo '<option value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
							}
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td class="key">Cajero:</td>
					<td>
						<?php
						if($idRol!=1)
						{
							echo $usuario->nombre.' '.$usuario->apellidoPaterno.''.$usuario->apellidoMaterno.'
							<input type="hidden" id="selectCajeros" name="selectCajeros" value="'.$usuario->idUsuario.'" />';
						}
						else
						{
							echo '
							<select class="cajas" id="selectCajeros" name="selectCajeros" style="width: 200px" onChange="opcionesCortes()">
							<option value="0">Seleccione</option>';
								
								foreach($usuarios as $row)
								{
									echo '<option value="'.$row->idUsuario.'">'.$row->usuario.'</option>';
								}
							
							echo'
							</select>';
						}
						?>
					</td>
				</tr>

			</table>
			<div id="formularioCorte"></div>
		</form>
	</div>
	
	<div id="ventanaReporteRetiros" title="Reporte retiros">
		<div id="procesandoReporte"></div>
		<table class="admintable" width="100%">
			<tr>
				
				<td align="center">
					De
					<input type="text" id="txtInicio" name="txtInicio" value="<?=date('Y-m-d')?>" class="cajas" onChange="obtenerReporteRetiros()" style="width: 100px" />
					a
					<input type="text" id="txtFin" name="txtFin" value="<?=date('Y-m-d')?>" class="cajas" onChange="obtenerReporteRetiros()" style="width: 100px" />
					
					&nbsp;&nbsp;&nbsp;
					
					<select class="cajas" id="selectEstacionesReporte" name="selectEstacionesReporte" style="width: 200px" onChange="obtenerReporteRetiros()">
						<option value="0">Estación</option>
						<?php
						foreach($estaciones as $row)
						{
							echo '<option value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
						}
						?>
					</select>
				</td>
			</tr>

		</table>
		
		<div id="obtenerReporteRetiros"></div>
		
	</div>
	
	<div id="ventanaVentasEfectivo" title="Ventas efectivo">
		<div id="procesandoReporteEfectivo"></div>
		<table class="admintable" width="100%">
			<tr>
				
				<td align="center">
					De
					<input type="text" id="txtInicioEfectivo" name="txtInicioEfectivo" value="<?=date('Y-m-d')?>" class="cajas" onChange="obtenerVentasEfectivo()" style="width: 100px" />
					a
					<input type="text" id="txtFinEfectivo" name="txtFinEfectivo" value="<?=date('Y-m-d')?>" class="cajas" onChange="obtenerVentasEfectivo()" style="width: 100px" />
					
					&nbsp;&nbsp;&nbsp;
					
					<select class="cajas" id="selectEstacionesEfectivo" name="selectEstacionesEfectivo" style="width: 200px" onChange="obtenerVentasEfectivo()">
						<option value="0">Estación</option>
						<?php
						foreach($estaciones as $row)
						{
							echo '<option value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
						}
						?>
					</select>
				</td>
			</tr>

		</table>
		
		<div id="obtenerVentasEfectivo"></div>
		
	</div>
	
	
	<div id="ventanaSaldoInicial" title="Saldo inicial">
		<div id="procesandoSaldoInicial"></div>
		<div id="formularioSaldoInicial"></div>
	</div>
	
	<div id="ventanaDetallesPago" title="Detalles de ventas">
		<div id="obtenerDetallesPagos"></div>
	</div>
	

</div>
