<?php
/*$pais		=$cliente->pais=='México'?"Mexico":$cliente->pais;
		
#$config['center'] 				= $cliente->numero.', '.$cliente->calle.', '.$cliente->localidad.', '.$cliente->municipio.', '.$cliente->estado.', '.$pais.', '.$cliente->codigoPostal;
$config['center'] 				= $cliente->latitud.', '.$cliente->longitud;
$config['zoom'] 				= '13';
$config['loadAsynchronously'] 	= true;
$config['https'] 				= true;
$config['map_height'] 			= '300px';
$config['map_width'] 			= '500px';
$config['posicionY'] 			= '24%';
$config['posicionX'] 			= '47%';
$config['posicion'] 			= 'absolute';
$config['map_div_id'] 			= 'mapaClientes';

$this->googlemaps->initialize($config);

$marker['icon'] 				= 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|9999FF|000000';
$marker['position'] 			= $config['center'];
$this->googlemaps->add_marker($marker);
$map 							= $this->googlemaps->create_map();

echo $map['js'];
echo $map['html'];*/

 echo'
<script>
$(document).ready(function()
{
	//loadScript();
	
	obtenerCatalogoContactos();
	calcularTotalesAcademicos()
	
	$("#txtFechaNacimiento").datepicker();
});
</script>';

echo
'<div id="recargarMapa"></div>

<ul class="menuTabsCliente">
	<li id="generales" class="cliente activado" onclick="configurarTabsCliente(\'generales\')">Generales</li>
	<li id="contacto" class="cliente" onclick="configurarTabsCliente(\'contacto\')">Contactos</li>';
	
	/*if($tipoRegistro=='prospectos' and isset($venta->idVenta) and sistemaActivo=='IEXE')
	{
		echo '<li id="datosVentas" class="cliente" onclick="configurarTabsCliente(\'datosVentas\')">Venta</li>';
	}*/
	
	if($tipoRegistro=='clientes')
	{
		echo'
		<li id="datosFiscales" style="display: none" class="cliente" onclick="configurarTabsCliente(\'datosFiscales\')">Datos fiscales</li>
		<li id="cuentasBanco" style="display: none" class="cliente" onclick="configurarTabsCliente(\'cuentasBanco\')">Cuentas banco</li>';	
	}

echo'
</ul>

<form id="frmEditarCliente">
	<div id="div-generales" class="divCliente visible">
		<table class="admintable" width="100%;">
		
			<tr style="display: none">
				<td class="key">Cuenta contable:</td>
				<td>
					<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="'.$cliente->cuenta.'" value="'.$cliente->cuenta.'" />
					<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="'.$cliente->idCuentaCatalogo.'" />
					
					<label style="cursor:pointer; float:right; margin-right: 300px" onclick="formularioAsociarCuenta()" title="Agregar cuenta" >
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<img src="'.base_url().'img/contabilidad.png" width="28"  /><br />
						Agregar cuenta
					</label>
				</td>
			</tr>
			
			<tr style="display: none">
				<td class="key">Saldo inicial:</td>
				<td>
					<input type="text" class="cajas" id="txtSaldoInicial" name="txtSaldoInicial" style="width:100px" onkeypress="return soloDecimales(event)" value="'.round($cliente->saldoInicial,decimales).'" />
				</td>
			</tr>
			
			<tr style="display: none">
				<td class="key">Registro:</td>
				<td>
					<select class="cajas" id="selectTipoProspecto" name="selectTipoProspecto" style="width:120px">
						<option  value="0">Cliente</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="key">Cliente</td>
				<td>
					<input type="text" class="cajas" id="empresa" name="empresa" style="width:650px" value="'.$cliente->empresa.'" />
					<input type="hidden" id="txtClienteId"  name="txtClienteId"  value="'.$cliente->idCliente.'" />
				</td>
				</tr>
			<tr>
			
			<tr>
				<td class="key">Teléfono</td>
				<td>
					<input placeholder="Lada" type="text" value="'.$cliente->lada.'" class="cajas" name="txtLada" id="txtLada" style="width:50px"/>
					<input placeholder="Teléfono" type="text" class="cajas"  value="'.$cliente->telefono.'" name="telefono" id="telefono" style="width:140px"/>
				</td>
			</tr>';
			
			

			echo'
			<tr>
				<td class="key">Móvil</td>
				<td>
					<input placeholder="Lada" type="text" value="'.$cliente->ladaMovil.'" class="cajas" name="txtLadaMovilCliente" id="txtLadaMovilCliente" style="width:50px"/>
					<input placeholder="Teléfono" type="text" class="cajas"  value="'.$cliente->movil.'" name="txtMovilCliente" id="txtMovilCliente" style="width:140px"/>
				</td>
			</tr>
			
			 <tr style="display: none">
				<td class="key">Fax</td>
				<td>
					<input placeholder="Lada" type="text" value="'.$cliente->ladaFax.'" class="cajas" name="txtLadaFax" id="txtLadaFax" style="width:50px"/>
					<input placeholder="Fax" type="text" class="cajas"  value="'.$cliente->fax.'" name="fax" id="fax" style="width:140px"/>
				</td>
			</tr>
			 <tr>
				<td class="key">Email:</td>
				<td>
					<input type="text" class="cajas"  value="'.$cliente->email.'" name="email" id="email" style="width:200px" placeholder="Email 1"/>
					<input type="text" class="cajas"  value="'.$cliente->email2.'" name="email2" id="email2" style="width:200px; display: none" placeholder="Email 2"/>
					<input type="text" class="cajas"  value="'.$cliente->email3.'" name="email3" id="email3" style="width:200px; display: none" placeholder="Email 3"/>
					<input type="text" class="cajas"  value="'.$cliente->email4.'" name="email4" id="email4" style="width:200px; display: none" placeholder="Email 4"/>
					<input type="text" class="cajas"  value="'.$cliente->email5.'" name="email5" id="email5" style="width:200px; display: none" placeholder="Email 5"/>
				</td>
			</tr>
			
			  <tr style="display: none">
				<td class="key">Páginas web</td>
				<td>
					<input type="text" class="cajas"  value="'.$cliente->web.'" name="pagina" id="pagina" style="width:450px" placeholder="Página web 1"/>
					<input type="text" class="cajas"  value="'.$cliente->web2.'" name="pagina2" id="pagina2" style="width:450px" placeholder="Página web 2"/>
					<input type="text" class="cajas"  value="'.$cliente->web3.'" name="pagina3" id="pagina3" style="width:450px" placeholder="Página web 3"/>
				</td>
			</tr>
			
			
			 <tr style="display:none">
				<td class="key">Competencia</td>
				<td>';
					
					$seleccionado=$cliente->competencia==1?'checked="checked"':'';
					echo'Confirmar &nbsp;
					<input '.$seleccionado.' type="checkbox" id="chkCompetencia" name="chkCompetencia" value="1"/>
				</td>
			</tr>
			
			 <tr style="display:none">
				<td class="key">Servicios/Productos</td>
				<td>
					<input value="'.$cliente->serviciosProductos.'" type="text" class="cajas" id="txtServiciosProductos" name="txtServiciosProductos" style="width:650px" />
				</td>
			</tr>
			 <tr >
				<td class="key"># Cliente</td>
				<td>
					<input type="text" value="'.$cliente->alias.'" class="cajas" name="txtAlias" id="txtAlias" style="width:200px" />
				</td>
			</tr>
			<tr style="display:none">
				<td class="key">¿Como nos contactó?</td>
				<td>
					<div id="obtenerFuentesContacto" style="float:left; width:300px">
						<select class="cajas" id="selectFuente" name="selectFuente" style="width:290px">
							<option value="0">Seleccione</option>';
						
						foreach($fuentes as $row)
						{
							$seleccionado=$row->idFuente==$cliente->idFuente?'selected="selected"':'';
							echo '<option '.$seleccionado.' value="'.$row->idFuente.'">'.$row->nombre.'</option>';
						}
						echo'
						</select>
					</div>
					<img onclick="formularioFuentesContacto()" src="'.base_url().'img/agregar.png" width="20" title="Agregar fuente de contacto" height="20" />
				</td>
				</tr>
			<tr>
			<tr style="display:none">
				<td class="key">Grupo</td>
				<td>
					<input type="text" class="cajas" value="'.$cliente->grupo.'" name="txtGrupo" id="txtGrupo" style="width:200px" />
				</td>
			</tr>
			<tr style="display:none">
				<td class="key">Usuario responsable</td>
				<td>
					<select class="cajas" id="selectResponsableCliente" name="selectResponsableCliente" style="width:200px">';
						
					foreach($responsables as $row)
					{
						$seleccionado	=$cliente->idUsuario==$row->idResponsable?'selected="selected""':'';
						echo '<option '.$seleccionado.' value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
					}
					
					echo'
					</select>
				</td>
				</tr>
			<tr style="display:none">
	
			<td class="key">Tipo de precio</td>
				<td>
					<select name="txtPrecioClienteEditar" id="txtPrecioClienteEditar" class="cajas" style="width:200px">';
						
						$a='';
						$b='';
						$c='';
						$d='';
						$e='';
						
						if($cliente->precio=="1")
							$a='selected="selected"';
						
						if($cliente->precio=="2")
							$b='selected="selected"';
						
						if($cliente->precio=="3")
							$c='selected="selected"';
						
						if($cliente->precio=="4")
							$d='selected="selected"';
						
						if($cliente->precio=="5")
							$e='selected="selected"';
					
						echo'
						<option value="1" '.$a.'>'.obtenerNombrePrecio(1).'</option>
						<option value="2" '.$b.'>'.obtenerNombrePrecio(2).'</option>
						<option value="3" '.$c.'>'.obtenerNombrePrecio(3).'</option>
						<option value="4" '.$d.'>'.obtenerNombrePrecio(4).'</option>
						<option value="5" '.$e.'>'.obtenerNombrePrecio(5).'</option>
					</select>
				
				</td>
			</tr>
				
				<tr>
				<td class="key" align="right">'.$this->session->userdata('identificador').':</td>
			
				<td>
				 <select name="selectZonas" id="selectZonas" class="cajasSelect" style="width:200px">';
			
					if(count($zonas) > 0)
					{ 
						foreach($zonas as $zona) 
						{ 
							echo' <option  value="'.$zona['idZona'].'"';
								
								if($cliente->idZona==$zona['idZona']) echo 'selected="selected"';
							
							echo'>'.$zona['descripcion'].'</option>';
						} 
					} 
					
			   echo'
					</select> 
				</td>
			</tr>
			   
		   <tr style="display:none">
				<td class="key">Latitud</td>
				<td>
					<input type="text" class="cajas" value="'.$cliente->latitud.'"  name="txtLatitud" id="txtLatitud" style="width:200px"/>
				</td>
			</tr>
			
			 <tr style="display:none">
				<td class="key">Longitud</td>
				<td>
					<input type="text" class="cajas" value="'.$cliente->longitud.'"  name="txtLongitud" id="txtLongitud" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Días de crédito</td>
				<td>
					<input type="text" class="cajas"  value="'.number_format($cliente->limiteCredito,0).'" name="txtLimiteCreditoCliente" id="txtLimiteCreditoCliente" style="width:200px" onkeypress="return soloNumerico(event)" maxlength="5"/>
				</td>
			</tr>';

			echo '<input type="hidden"  id="txtIdLicenciaTraspaso" name="txtIdLicenciaTraspaso" value="'.$cliente->idLicenciaTraspaso.'"/>';
			
			/*if(!$clienteSucursal and $sucursalCliente==null)
			{
				echo '<input type="hidden"  id="selectSucursal" name="selectSucursal" value="0"/>';
			}

			if($clienteSucursal or $sucursalCliente!=null)
			{
				echo'
				<tr>
					<td class="key">Sucursal traspasos:</td>
					<td>
						<select class="cajas" id="selectSucursal" name="selectSucursal" style="width:200px">
							<option value="0">Seleccione</option>';
						
							foreach($licencias as $row)
							{
								if($sucursalCliente!=null)
								{
									if($row->idLicencia!=$idLicencia)
									{
										echo '<option '.($row->idLicencia==$sucursalCliente->idSucursal?'selected="selected"':'').' value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
									}
								}
								else
								{
									if($row->idLicencia!=$idLicencia)
									{
										echo '<option value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
									}
								}
							}

						echo'
						</select>

					</td>
				</tr>';
			}*/

			if(!$clienteSucursal)
			{
				if($sucursalCliente!=null)
				{
					echo'
					<tr>
						<td class="key">Sucursal traspasos:</td>
						<td>
							<select class="cajas" id="selectSucursal" name="selectSucursal" style="width:200px">
								<option value="0">Seleccione</option>';

								foreach($licencias as $row)
								{
									if($row->idLicencia!=$idLicencia)
									{
										echo '<option '.($row->idLicencia==$sucursalCliente->idSucursal?'selected="selected"':'').' value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
									}
								}

							echo'
							</select>

						</td>
					</tr>';
				}
				else
				{
					echo '<input type="hidden"  id="selectSucursal" name="selectSucursal" value="0"/>';
				}
				
			}
			else
			{
				if($clienteSucursal)
				{
					if($registroSucursal)
					{
						echo'
						<tr>
							<td class="key">Sucursal traspasos:</td>
							<td>
								<select class="cajas" id="selectSucursal" name="selectSucursal" style="width:200px">
									<option value="0">Seleccione</option>';

									foreach($licencias as $row)
									{
										if($row->idLicencia!=$idLicencia)
										{
											echo '<option value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
										}
									}

								echo'
								</select>

							</td>
						</tr>';
					}
					else
					{
						echo '<input type="hidden"  id="selectSucursal" name="selectSucursal" value="0"/>';
					}
					
				}
			}

			echo'
			<tr>
				<td class="key">Comentarios</d>
				<td>
					<textarea class="TextArea" id="txtComentariosCliente" name="txtComentariosCliente" style="height:70px; width:400px; " placeholder="Comentarios">'.$cliente->comentarios.'</textarea>
				</td>
			</tr>
			
			<tbody style="display:none">
				<tr>
					<th colspan="2" align="center" class="key">Dirección de envio</th>
				</tr>

				<tr>
					<td class="key">Calle:</td>
					<td>
						<input type="text" class="cajas" name="txtCalleEnvio" id="txtCalleEnvio" style="width:500px" value="'.$cliente->direccionEnvio.'" onchange="copiarDireccionCliente()"/>
					</td>
				</tr>
				
				<tr>
					<td class="key">Número:</td>
					<td>
						<input type="text" class="cajas" name="txtNumeroEnvio" id="txtNumeroEnvio" style="width:500px" value="'.$cliente->numeroEnvio.'"/>
					</td>
				</tr>
				
				<tr>
					<td class="key">Colonia:</td>
					<td>
						<input type="text" class="cajas" name="txtColoniaEnvio" id="txtColoniaEnvio" style="width:500px" value="'.$cliente->coloniaEnvio.'"/>
					</td>
				</tr>
				
				<tr>
					<td class="key">Código postal:</td>
					<td>
						<input type="text" class="cajas" name="txtCodigoPostalEnvio" id="txtCodigoPostalEnvio" style="width:200px" maxlength="6" value="'.$cliente->codigoPostalEnvio.'"/>
					</td>
				</tr>
				
				<tr>
					<td class="key">Localidad:</td>
					<td>
						<input type="text" class="cajas" name="txtLocalidadEnvio" id="txtLocalidadEnvio" style="width:500px" value="'.$cliente->localidadEnvio.'"/>
					</td>
				</tr>
				
				 <tr>
					<td class="key">Municipio:</td>
					<td>
						<input type="text" class="cajas" name="txtMunicipioEnvio" id="txtMunicipioEnvio" style="width:500px" value="'.$cliente->municipioEnvio.'"/>
					</td>
				</tr>
				
				<tr>
					<td class="key">Estado:</td>
					<td>
						<input type="text" class="cajas" name="txtEstadoEnvio" id="txtEstadoEnvio" style="width:500px" value="'.$cliente->estadoEnvio.'"/>
					</td>
				</tr>
				
				 <tr>
					<td class="key">País:</td>
					<td>
						<input type="text" class="cajas" name="txtPaisEnvio" id="txtPaisEnvio" style="width:500px" value="'.$cliente->paisEnvio.'"/>
					</td>
				</tr>
				
				
			</tbody>
		</table>
	</div>
	
	<div id="div-contacto" class="divCliente">
		<div id="obtenerCatalogoContactos"></div>
	</div>
	
	<div id="div-datosFiscales" class="divCliente">
		<table class="admintable" width="100%;">
			<tr>
				<td class="key">Razón social:</td>
				<td>
					<input type="text" class="cajas" name="txtRazonSocial" id="txtRazonSocial" style="width:200px" value="'.$cliente->razonSocial.'" />
				</td>
			</tr>
			<tr>
				<td class="key">RFC</td>
				<td>
				<input type="text" class="cajas" value="'.$cliente->rfc.'"  name="rfc" id="rfc" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Calle</td>
				<td>
					<textarea class="TextArea" name="direccion" id="direccion" style="width:205px">'.$cliente->calle.'</textarea>
				</td>
			</tr>
			
			 <tr>
				<td class="key">Número</td>
				<td>
					<input type="text" class="cajas" value="'.$cliente->numero.'" name="numero" id="numero" style="width:200px"/>
				</td>
			</tr>
			 <tr>
				<td class="key">Colonia</td>
				<td>
					<input type="text" class="cajas"  value="'.$cliente->colonia.'" name="colonia" id="colonia" style="width:200px"/>
				</td>
			</tr>
			
			 <tr>
				<td class="key">Código Postal</td>
				<td>
					<input type="text" class="cajas"  value="'.$cliente->codigoPostal.'" name="codigoPostal" id="codigoPostal" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Localidad</td>
				<td>
					<input type="text" class="cajas" value="'.$cliente->localidad.'" name="localidad" id="localidad" style="width:200px" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Municipio</td>
				<td>
					<input type="text" class="cajas" value="'.$cliente->municipio.'" name="txtMunicipio" id="txtMunicipio" style="width:200px" />
				</td>
			</tr>
			
			 <tr>
				<td class="key">Estado</td>
				<td>
					<input type="text" class="cajas" value="'.$cliente->estado.'" name="estado" id="estado" style="width:200px"/>
				</td>
			</tr>
			<tr>
				<td class="key">País</td>
				<td>
					<input type="text" class="cajas" value="'.$cliente->pais.'" name="txtPais" id="txtPais" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<th colspan="2">Facturación</th>
			</tr>
			
			<tr>
				<td class="key">Método de pago:</td>
				<td>
					<select class="cajas" id="selectMetodoPagoCliente" name="selectMetodoPagoCliente" style="width:200px">';
					
					foreach($metodos as $row)
					{
						echo '<option '.($row->idMetodo==$cliente->idMetodo?'selected="selected"':'').' value="'.$row->idMetodo.'">'.$row->clave.', '.$row->concepto.'</option>';
					}
					
					echo'
					</select>
				</td>
			</tr>
			
			<tr>
				<td class="key">Forma de pago:</td>
				<td>
					<input type="text" class="cajas" name="txtFormaPagoCliente" id="txtFormaPagoCliente" style="width:200px" value="'.$cliente->formaPago.'"/>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="div-cuentasBanco" class="divCliente">
		<table class="admintable" width="100%;">
			<tr>
				<th colspan="4" align="right" style="border-right:none">Cuentas de banco</th>
				<th align="left" style="border-right:none; border-left:none">
					<!--&nbsp;&nbsp;
					<img src="'.base_url().'img/add.png" width="22" height="22" title="Agregar banco" onclick="formularioBancos()" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
					&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/add.png" width="22" height="22" title="Agregar cuenta" onclick="formularioCuentas()" />
					<br />
					<!--<a>Banco</a>-->
					<a>Cuenta</a>
				</th>
				<th style="border-left:none"></th>
			 </tr>
			 <tr>
				<th>#</th>
				<th>Cuenta</th>
				<th>Clabe</th>
				<th>Banco</th>
				<th>Emisor</th>
				<th>Acciones</th>
			 </tr>';
			 
			 $i=1;
			 foreach($cuentas as $row)
			 {
				 $estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
				 
				 echo'
				 <tr '.$estilo.'>
					<td align="right">'.$i.'</td>
					<td align="center">'.$row->cuenta.'</td>
					<td align="center">'.$row->clabe.'</td>
					<td align="center">'.$row->banco.'</td>
					<td align="center">'.$row->emisor.'</td>
					<td align="center" width="15%">
						<img src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerCuenta('.$row->idCuenta.')" />
						&nbsp;&nbsp;
						<img src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarCuentaCliente('.$row->idCuenta.')" />
						<br />
						<a>Editar</a>
						<a>Borrar</a>
					</td>
				 </tr>';
				 
				 $i++;
			 }
			 
		 echo'
		 </table>
	</div>
	
	<div id="div-direccionesEntrega" class="divCliente">
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
	</div>';
	
	if(sistemaActivo=='IEXE')
	{
		echo'
		<div id="div-academicos" class="divCliente">
			<table class="admintable" width="100%;">';
				
				 if($tipoRegistro!='prospectos')
				 {
					 echo '
					 <tr>
						<td class="key">Programa:</td>
						<td>
							<div id="obtenerProgramasRegistro" style="width:220px; float: left">
								<select class="cajas" id="selectProgramas" name="selectProgramas" style="width:200px" onchange="sugerirCantidadesAcademico(0)">
									<option value="0">Seleccione</option>';
								
								foreach($programas as $row)
								{
									echo '<option '.($row->idPrograma==$academico->idPrograma?'selected="selected"':'').' value="'.$row->idPrograma.'|'.$row->cantidadInscripcion.'|'.$row->cantidadColegiatura.'|'.$row->cantidadReinscripcion.'">'.$row->nombre.'</option>';
								}
								
								echo'
								</select>
							</div>
						
							 <img onclick="obtenerCatalogoProgramas()" src="'.base_url().'img/agregar.png" width="20" height="20" title="Estatus" style="float:left" />
						</td>
					</tr>';
				 }
				
				echo'
				<tr>
					<td class="key">Matrícula:</td>
					<td>
						<input type="text" class="cajas" name="txtMatricula" id="txtMatricula" style="width:200px" value="'.$academico->matricula.'" />
					</td>
				</tr>
				<tr>
					<td class="key">Usuario:</td>
					<td>
						<input type="text" class="cajas" name="txtUsuarioAcademico" id="txtUsuarioAcademico" style="width:200px" value="'.$academico->usuario.'"/>
					</td>
				
				</tr>
				
				 <tr>
					<td class="key">Contraseña:</td>
					<td>
					<input type="password" class="cajas" name="txtPasswordAcademico" id="txtPasswordAcademico" style="width:200px" value="'.$academico->password.'"/>
					</td>
				</tr>
				
				 <tr>
					<td class="key">Incripción:</td>
					<td>
						<input type="text" class="cajas" name="txtInscripcion" id="txtInscripcion" style="width:200px" value="'.$academico->inscripcion.'" onchange="calcularTotalesAcademicos()" onkeypress="return soloDecimales(event)" maxlength="7"/>
						&nbsp;&nbsp;
						<label>Periodicidad: </label>
						<input type="text" class="cajas" name="txtCantidadInscripcion" id="txtCantidadInscripcion" value="'.$academico->cantidadInscripcion.'" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
						&nbsp;&nbsp;
						<label id="lblTotalInscripcion">$0.00</label>
					</td>
				</tr>
				
				<tr>
					<td class="key">Colegiatura:</td>
					<td>
						<input type="text" class="cajas" name="txtColegiatura" id="txtColegiatura" style="width:200px" value="'.$academico->colegiatura.'" onchange="calcularTotalesAcademicos()" onkeypress="return soloDecimales(event)" maxlength="7"/>
						&nbsp;&nbsp;
						<label>Periodicidad: </label>
						<input type="text" class="cajas" name="txtCantidadColegiatura" id="txtCantidadColegiatura" value="'.$academico->cantidadColegiatura.'" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
						&nbsp;&nbsp;
						<label id="lblTotalColegiatura">$0.00</label>
					</td>
				</tr>
				
				<tr>
					<td class="key">Reinscripción:</td>
					<td>
						<input type="text" class="cajas" name="txtReinscripcion" id="txtReinscripcion" style="width:200px" value="'.$academico->reinscripcion.'" onchange="calcularTotalesAcademicos()" onkeypress="return soloDecimales(event)" maxlength="7"/>
						&nbsp;&nbsp;
						<label>Periodicidad: </label>
						<input type="text" class="cajas" name="txtCantidadReinscripcion" id="txtCantidadReinscripcion" value="'.$academico->cantidadReinscripcion.'" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
						&nbsp;&nbsp;
						<label id="lblTotalReinscripcion">$0.00</label>
					</td>
				</tr>
				
				 <tr>
					<td class="key">Titulación:</td>
					<td>
						<input type="text" class="cajas" name="txtTitulacion" id="txtTitulacion" style="width:200px" value="'.$academico->titulacion.'" onkeypress="return soloDecimales(event)" maxlength="7" />
					</td>
				</tr>
				
				 <tr>
					<td class="key">Periodo:</td>
					<td>
						<input type="text" class="cajas" name="txtPeriodo" id="txtPeriodo" style="width:200px" value="'.$academico->periodo.'"/>
					</td>
				</tr>
				
				<tr>
					<td class="key">Trimestre:</td>
					<td>
						<input type="text" class="cajas" name="txtPeriodoActual" id="txtPeriodoActual" style="width:200px" value="'.$academico->periodoActual.'" onkeypress="return soloDecimales(event)" maxlength="2"/>
					</td>
				</tr>';
				
				#if(isset($venta->idVenta) and $tipoRegistro!='prospectos')
				{
					echo'
					<tr style="display:none">
						<td class="key">Venta:</td>
						<td>
							<input type="text" class="cajas" name="txtVentaProspecto" id="txtVentaProspecto" style="width:100px" value="'.round(isset($venta->venta)?$venta->venta:0,decimales).'" onkeypress="return soloDecimales(event)" maxlength="10"  />
							<input type="hidden" name="txtIdVenta" id="txtIdVenta" value="'.(isset($venta->idVenta)?$venta->idVenta:0).'" />
							
							&nbsp;
							
							<label id="lblTotalAcademicos">$0.00</label>
						</td>
					</tr>';
				}
				
	
			echo'
			</table>
		</div>';
		
		
		
		echo'
		<div id="div-documentos" class="divCliente">
			<table class="admintable" width="99%">';
			
			foreach($tiposDocumentos as $row)
			{
				$documentos		= $this->clientes->obtenerDocumentosTipoCliente($row->idTipo,$cliente->idCliente);
				
				echo '
				<tr>
					<td class="key" style="width:40%">'.$row->nombre.'</td>
					<td>
						<input type="file" id="txtComprobanteCliente'.$row->idTipo.'" name="txtComprobanteCliente'.$row->idTipo.'" />
						
						<div id="listaArchivos'.$row->idTipo.'">';
						
						foreach($documentos as $doc)
						{
							echo '
							<div id="documento'.$doc->idDocumento.'" class="contenidoArchivo"> <img src="'.base_url().'img/borrado.png" onclick="borrarDocumentoTemporal('.$doc->idDocumento.',0)" /><a title="Descargar documento" href="'.base_url().'clientes/descargarDocumentoCliente/'.$doc->idDocumento.'">'.$doc->nombre.'</a></div>';
						}
						
						echo'
						</div>
					</td>
				</tr>
				
				<script type="text/javascript">
				$(document).ready(function()
				{ 
					$("#txtComprobanteCliente'.$row->idTipo.'").pekeUpload(
					{
						theme:				"bootstrap",
						btnText:			"Seleccione",
						allowedExtensions:	"jpeg|jpg|png|gif|tif|bmp|pdf|doc|docx|xls|xlsx|txt|rar|zip|xps|oxps|xml|PDF",
						url:				 "'.base_url().'clientes/subirArchivoCliente/'.$row->idTipo.'/'.$cliente->idCliente.'/0",
						maxSize:			25,
						sizeError:			"El archivo es demasiado grande",
						invalidExtError:	"No se permite ese tipo de archivos",
						onFileSuccess:function(file,data)
						{
							data	= eval(data);
							
							switch(data[0])
							{
								case "1":
	
									$("#listaArchivos'.$row->idTipo.'").append("<div id=\"documento"+data[3]+"\" class=\"contenidoArchivo\"> <img src=\"'.base_url().'img/borrado.png\" onclick=\"borrarDocumentoTemporal("+data[3]+",0)\" />  "+data[1]+"</div>")
									notify("El documento se ha cargado correctamento",500,4000,""); 
								break;	
								
								case "0":
									notify("El documento es inválido",500,4000,"error"); 
								break;	
							}
						}
					});
				});
			</script>';
			}
				
		echo'
		</div>';
		
		
	}

echo'
</form>';