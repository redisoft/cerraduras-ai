<?php
/*$config['center'] 				= 'Puebla, Mexico';
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

$map 					= $this->googlemaps->create_map();

echo $map['js'];
echo $map['html'];*/

 echo'
<script>
$(document).ready(function()
{
	//loadScript();
	
	$("#empresa").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerClientes",
		
		select:function( event, ui)
		{
			notify("El cliente ya esta registrado",500,5000,"error",5,5);
			document.getElementById("empresa").reset();
		}
	});
	
	$("#txtBanco").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerBancosRepetidos",
		
		select:function( event, ui)
		{
			$("#txtIdBanco").val(ui.item.idBanco)
		}
	});
	
	$("#txtNombrePadre").autocomplete(
	{
		source:"'.base_url().'catalogos/obtenerPadres",
		
		select:function( event, ui)
		{
			$("#txtIdPadre").val(ui.item.idPadre)
			$("#txtApellidoPaternoPadre").val(ui.item.apellidoPaternoPadre)
			$("#txtApellidoMaternoPadre").val(ui.item.apellidoMaternoPadre)
			$("#txtFechaNacimientoPadre").val(ui.item.fechaNacimientoPadre)
			$("#txtTelefonoPadre").val(ui.item.telefonoPadre)
			$("#txtCelularPadre").val(ui.item.celularPadre)
			$("#txtEmailPadre").val(ui.item.emailPadre)
			$("#txtOcupacionPadre").val(ui.item.ocupacionPadre)
			$("#txtNombreMadre").val(ui.item.nombreMadre)
			$("#txtApellidoPaternoMadre").val(ui.item.apellidoPaternoMadre)
			$("#txtApellidoMaternoMadre").val(ui.item.apellidoMaternoMadre)
			$("#txtFechaNacimientoMadre").val(ui.item.fechaNacimientoMadre)
			$("#txtTelefonoMadre").val(ui.item.telefonoMadre)
			$("#txtCelularMadre").val(ui.item.celularMadre)
			$("#txtEmailMadre").val(ui.item.emailMadre)
			$("#txtOcupacionMadre").val(ui.item.ocupacionMadre)
			
			setTimeout(function()
			{
				$("#txtNombrePadre").val(ui.item.nombrePadre)
			},300);
		}
	});
	
	$("#txtFechaNacimiento,#txtFechaNacimientoPadre,#txtFechaNacimientoMadre").datepicker({changeYear: true});
	
});
</script>
<ul class="menuTabsCliente">
	<li id="generales" class="cliente activado" onclick="configurarTabsCliente(\'generales\')">Generales</li>';
	
	if($tipoRegistro=='clientes')
	{
		#echo '<li id="datosPadres" class="cliente" onclick="configurarTabsCliente(\'datosPadres\')">Padres</li>';
	}
	
	echo'
	<li id="datosFiscales" class="cliente" onclick="configurarTabsCliente(\'datosFiscales\')">Datos fiscales</li>
	<li id="contacto" class="cliente" onclick="configurarTabsCliente(\'contacto\')">Contacto</li>';
	
	if($tipoRegistro=='clientes')
	{
		echo'
		
		<li id="cuentasBanco" style="display: none" class="cliente" onclick="configurarTabsCliente(\'cuentasBanco\')">Cuentas banco</li>';
	}
	

echo'
</ul>

<form id="frmClientes">
	<div id="recargarMapa"></div>
	
	<div id="div-generales" class="divCliente visible">
		<table class="admintable" width="100%;">
			
			<tr style="display: none">
				<td class="key">Cuenta contable:</td>
				<td>
					<input type="text" class="cajas" id="txtBuscarCuentaContable" name="txtBuscarCuentaContable" style="width:300px" placeholder="Clientes nacionales"  readonly="readonly"/>
					<input type="hidden" id="txtIdCuentaCatalogo" name="txtIdCuentaCatalogo" value="13" />
					
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
					<input type="text" class="cajas" id="txtSaldoInicial" name="txtSaldoInicial" style="width:100px" onkeypress="return soloDecimales(event)" value="0" />
				</td>
			</tr>
			
			<tr style="display: none">
				<td class="key">Registro:</td>
				<td>
					<select class="cajas" id="selectRegistro" name="selectRegistro" style="width:120px">
						<option value="0">Cliente</option>
					</select>
				</td>
			</tr>';
			
			if(sistemaActivo=='IEXE')
			{
				echo '
				<tr '.($tipoRegistro=='prospectos'?'style="display:none"':'').'>
					<td class="key">Tipo:</td>
					<td>
						<select class="cajas" id="selectTipoCliente" name="selectTipoCliente" style="width:120px">
							<option value="1">Normal</option>
							<option value="2">VIP</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="key">Promotor:</td>
					<td>
						<select class="cajas" id="selectPromotores" name="selectPromotores" style="width:200px">';
							
							foreach($promotores as $row)
							{
								echo '<option value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
							}
						
						echo'
						</select>
						
					</td>
				</tr>
				
				<tr '.($tipoRegistro=='clientes'?'style="display:none"':'').'>
					<td class="key">Campaña:</td>
					<td>
						<select class="cajas" id="selectCampana" name="selectCampana" style="width:200px">
							<option value="0">Seleccione</option>';
							
							foreach($campanas as $row)
							{
								echo '<option value="'.$row->idCampana.'">'.$row->nombre.'</option>';
							}
						
						echo'
						</select>
						
					</td>
				</tr>
				
				
				<tr>
					<td class="key">'.(sistemaActivo=='IEXE'?'*':'').'Alumno/Prospecto:</td>
					<td>
						<input type="text" class="cajas" id="txtNombreAlumno"		name="txtNombreAlumno" style="width:200px" placeholder="Nombre" />
						<input type="text" class="cajas" id="txtApellidoPaterno" 	name="txtApellidoPaterno" style="width:200px" placeholder="Apellido paterno" />
						<input type="text" class="cajas" id="txtApellidoMaterno" 	name="txtApellidoMaterno" style="width:200px" placeholder="Apellido materno" />
						<br />
						&nbsp;<input type="checkbox"  id="chkDuplicarProspecto"	name="chkDuplicarProspecto" value="1"/> Duplicar prospecto
					</td>
				</tr>';
			}
			
			echo'
			<tr>
				<td class="key">'.(sistemaActivo=='IEXE'?'':'*').' Cliente:</td>
				<td>
					<input type="text" class="cajas" id="empresa" name="empresa" style="width:619px" />
				</td>
			</tr>
			
			 <tr>
				<td class="key">* Teléfono:</td>
				<td>
					<input placeholder="Lada" type="text" class="cajas" name="txtLada" id="txtLada" style="width:50px"/>
					<input placeholder="Teléfono" type="text" class="cajas" name="telefono" id="telefono" style="width:140px"/>
				</td>
			</tr>';
			
			if(sistemaActivo=='IEXE')
			{
				echo '
				<tr>
					<td class="key">Fecha cumpleaños:</td>
					<td>
						<input type="text" class="cajas" id="txtFechaNacimiento" name="txtFechaNacimiento" style="width:100px" value="'.date('Y-m-d').'" />
					</td>
				</tr>';
			}
			
			echo'
			<tr>
				<td class="key">Móvil:</td>
				<td>
					<input placeholder="Lada" type="text" class="cajas" name="txtLadaMovilCliente" id="txtLadaMovilCliente" style="width:50px"/>
					<input placeholder="Movil" type="text" class="cajas" name="txtMovilCliente" id="txtMovilCliente" style="width:140px"/>
				</td>
			</tr>
			
			 <tr style="display: none">
				<td class="key">Fax:</td>
				<td>
					<input placeholder="Lada" type="text" class="cajas" name="txtLadaFax" id="txtLadaFax" style="width:50px"/>
					<input placeholder="Fax" type="text" class="cajas" name="fax" id="fax" style="width:140px"/>
				</td>
			</tr>
			
				<tr>
					<td class="key">Email:</td>
					<td>
						<input type="text" class="cajas" name="email" id="email" style="width:200px" placeholder=""/>
						<input type="text" class="cajas" name="email2" id="email2" style="width:200px; display: none" />
						<input type="text" class="cajas" name="email3" id="email3" style="width:200px; display: none" />
						<input type="text" class="cajas" name="email4" id="email4" style="width:200px; display: none" />
						<input type="text" class="cajas" name="email5" id="email5" style="width:200px; display: none" />
					</td>
				</tr>
			 
			 <tr style="display: none">
				<td class="key">Páginas web:</td>
				<td>
					<input type="text" class="cajas" name="pagina" id="pagina" style="width:450px" placeholder="Página web 1"/>
					<input type="text" class="cajas" name="pagina2" id="pagina2" style="width:450px" placeholder="Página web 2"/>
					<input type="text" class="cajas" name="pagina3" id="pagina3" style="width:450px" placeholder="Página web 3"/>
				</td>
			</tr>
	
			<tr style="display: none">
				<td class="key">Competencia:</td>
				<td>
					Confirmar &nbsp;
					<input type="checkbox" id="chkCompetencia" name="chkCompetencia" value="1"/>
				</td>
			</tr>
			
			<tr style="display: none">
				<td class="key">Servicios/Productos:</td>
				<td>
					<input type="text" class="cajas" id="txtServiciosProductos" name="txtServiciosProductos" style="width:619px" />
				</td>
			</tr>
	
			<tr> 	 
				<td class="key"># Cliente:</td>
				<td>
					<input type="text" class="cajas" name="txtAlias" id="txtAlias" style="width:200px" value="'.$numeroCliente.'" />
				</td>
			</tr>
			
			<tr style="display: none">
				<td class="key">¿Como nos contactó?</td>
				<td>
					<div id="obtenerFuentesContacto" style="float:left; width:300px">
						<select class="cajas" id="selectFuente" name="selectFuente" style="width:290px">
							<option value="0">Seleccione</option>';
						echo'
						</select>
					</div>
					<img onclick="formularioFuentesContacto()" src="'.base_url().'img/agregar.png" width="20" title="Agregar fuente de contacto" height="20" />
				</td>
				</tr>
			<tr>
			
			<tr style="display: none">
				<td class="key">Grupo:</td>
				<td>
					<input type="text" class="cajas" name="txtGrupo" id="txtGrupo" style="width:200px" />
					
				</td>
			</tr>
			
			<tr style="display: none">
				<td class="key">Tipo de precio:</td>
				<td>
					<select name="txtPrecioCliente" id="txtPrecioCliente" class="cajas" style="width:200px">
						<option value="1">'.obtenerNombrePrecio(1).'</option>
						<option value="2">'.obtenerNombrePrecio(2).'</option>
						<option value="3">'.obtenerNombrePrecio(3).'</option>
						<option value="4">'.obtenerNombrePrecio(4).'</option>
						<option value="5">'.obtenerNombrePrecio(5).'</option>
					</select>
				</td>
			</tr>
			<tr style="display: none">
				<td class="key" align="right">* '.$this->session->userdata('identificador').':</td>
				<td>
					<input type="hidden" id="txtIdentificador" value="'.$this->session->userdata('identificador').'" />
					
					<div id="obtenerRegistrosZona" style="width:220px; float: left">
						<select name="selectZonas" id="selectZonas" class="cajas" style="width:200px">
							<option value="0">Seleccione</option>';
							
							foreach($zonas as $zona) 
							{ 
								$seleccionado	= $zona['idZona']==1?'selected="selected"':'';
								
								if(sistemaActivo=='IEXE')
								{
									if($tipoRegistro=='prospectos' and $zona['idZona']==5)
									{
										$seleccionado='selected="selected"';
									}
								}
								
								echo'<option '.$seleccionado.' value="'.$zona['idZona'].'">'.$zona['descripcion'].'</option>';
							} 
							
					   echo'
					   </select> 
				   
				   </div>
				   
				   <img onclick="obtenerCatalogoZonas()" src="'.base_url().'img/agregar.png" width="20" height="20" title="'.$this->session->userdata('identificador').'" style="float:left" />
				   
				</td>
			</tr>
			
			<tr style="display: none">
				<td class="key">Latitud:</td>
				<td>
					<input type="text" class="cajas" value=""  name="txtLatitud" id="txtLatitud" style="width:200px"/>
				</td>
			</tr>
			
			 <tr style="display: none">
				<td class="key">Longitud:</td>
				<td>
					<input type="text" class="cajas" value=""  name="txtLongitud" id="txtLongitud" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Días de crédito:</td>
				<td>
					<input type="text" class="cajas" name="limiteCredito" value="0" id="limiteCredito" style="width:200px" onkeypress="return soloNumerico(event)" maxlength="5"/>
				</td>
			</tr>
			
			<tr '.(!$clienteSucursal?'style="display: none"':'').'>
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
			</tr>
			
			<tr>
				<td class="key">Comentarios:</td>
				<td>
					<textarea class="TextArea" id="txtComentariosCliente" name="txtComentariosCliente" style="height:70px; width:400px; " placeholder=""></textarea>
				</td>
			</tr>
			
			
			
			<tbody style="display: none">
				<tr>
					<th colspan="2" align="center" class="key">Dirección de envío</th>
				 </tr>
				 
				 <tr style="display:none">
					<td class="key">¿Es la misma que la anterior?:</td>
					<td>
						Confirmar
						<input type="checkbox"  id="chkConfirmar" onchange="copiarDireccion()" />
					</td>
				</tr>
				
				<tr>
					<td class="key">Calle:</td>
					<td>
						<input type="text" class="cajas" name="txtCalleEnvio" id="txtCalleEnvio" style="width:500px" />
					</td>
				</tr>
				
				<tr>
					<td class="key">Número:</td>
					<td>
						<input type="text" class="cajas" name="txtNumeroEnvio" id="txtNumeroEnvio" style="width:500px" />
					</td>
				</tr>
				
				<tr>
					<td class="key">Colonia:</td>
					<td>
						<input type="text" class="cajas" name="txtColoniaEnvio" id="txtColoniaEnvio" style="width:500px" />
					</td>
				</tr>
				
				<tr>
					<td class="key">Código postal:</td>
					<td>
						<input type="text" class="cajas" name="txtCodigoPostalEnvio" id="txtCodigoPostalEnvio" style="width:200px" maxlength="6" />
					</td>
				</tr>
				
				<tr>
					<td class="key">Localidad:</td>
					<td>
						<input type="text" class="cajas" name="txtLocalidadEnvio" id="txtLocalidadEnvio" style="width:500px" />
					</td>
				</tr>
				
				 <tr>
					<td class="key">Municipio:</td>
					<td>
						<input type="text" class="cajas" name="txtMunicipioEnvio" id="txtMunicipioEnvio" style="width:500px" />
					</td>
				</tr>
				
				<tr>
					<td class="key">Estado:</td>
					<td>
						<input type="text" class="cajas" name="txtEstadoEnvio" id="txtEstadoEnvio" style="width:500px" >
					</td>
				</tr>
				
				 <tr>
					<td class="key">País:</td>
					<td>
						<input type="text" class="cajas" name="txtPaisEnvio" id="txtPaisEnvio" style="width:500px" />
					</td>
				</tr>
				
				
				 
				
			</tbody>
		</table>
	</div>
	
	<div id="div-datosPadres" class="divCliente">
		<table class="admintable" width="100%;">
			<tr>
				<th colspan="2" align="center" class="key">Padres</th>
			 </tr>
			 
			 <tr>
				<td class="key">Padre:</td>
				<td>
					<input type="text" class="cajas" name="txtNombrePadre" id="txtNombrePadre" style="width:200px" placeholder="Nombre"/>
					<input type="text" class="cajas" name="txtApellidoPaternoPadre" id="txtApellidoPaternoPadre" style="width:200px" placeholder="Apellido paterno"/>
					<input type="text" class="cajas" name="txtApellidoPaternoMadre" id="txtNombrePadre" style="width:200px" placeholder="Apellido materno"/>
					
					<input type="hidden" name="txtIdPadre" id="txtIdPadre" value="0"/>
				</td>
			</tr>
			<tr>
				<td class="key">Cumpleaños:</td>
				<td>
					<input placeholder="Fecha" type="text" class="cajas" name="txtFechaNacimientoPadre" id="txtFechaNacimientoPadre" style="width:100px"/>
				</td>
			</tr>
			<tr>
				<td class="key">Teléfono:</td>
				<td>
					<input type="text" class="cajas" name="txtTelefonoPadre" id="txtTelefonoPadre" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Móbil:</td>
				<td>
					<input type="text" class="cajas" name="txtCelularPadre" id="txtCelularPadre" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Email:</td>
				<td>
					<input type="text" class="cajas" name="txtEmailPadre" id="txtEmailPadre" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Ocupación:</td>
				<td>
					<input type="text" class="cajas" name="txtOcupacionPadre" id="txtOcupacionPadre" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Madre:</td>
				<td>
					<input type="text" class="cajas" name="txtNombreMadre" id="txtNombreMadre" style="width:200px" placeholder="Nombre"/>
					<input type="text" class="cajas" name="txtApellidoPaternoMadre" id="txtApellidoPaternoMadre" style="width:200px" placeholder="Apellido paterno"/>
					<input type="text" class="cajas" name="txtApellidoMaternoMadre" id="txtApellidoMaternoMadre" style="width:200px" placeholder="Apellido materno"/>
				</td>
			</tr>
			<tr>
				<td class="key">Cumpleaños:</td>
				<td>
					<input placeholder="Fecha" type="text" class="cajas" name="txtFechaNacimientoMadre" id="txtFechaNacimientoMadre" style="width:100px"/>
				</td>
			</tr>
			<tr>
				<td class="key">Teléfono:</td>
				<td>
					<input type="text" class="cajas" name="txtTelefonoMadre" id="txtTelefonoMadre" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Móbil:</td>
				<td>
					<input type="text" class="cajas" name="txtCelularMadre" id="txtCelularMadre" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Email:</td>
				<td>
					<input type="text" class="cajas" name="txtEmailMadre" id="txtEmailMadre" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Ocupación:</td>
				<td>
					<input type="text" class="cajas" name="txtOcupacionMadre" id="txtOcupacionMadre" style="width:200px"/>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="div-contacto" class="divCliente">
		<table class="admintable" width="100%;">
			<tr>
				<th colspan="2" align="center" class="key">Contacto</th>
			 </tr>
			 
			 <tr>
				<td class="key">Nombre:</td>
				<td>
				<input type="text" class="cajas" name="txtNombreContacto" id="txtNombreContacto" style="width:200px"/>
				</td>
			</tr>
			<tr>
				<td class="key">Teléfono:</td>
				<td>
					<input placeholder="Lada" type="text" class="cajas" name="txtLadaTelefonoContacto" id="txtLadaTelefonoContacto" style="width:50px"/>
					<input placeholder="Teléfono" type="text" class="cajas" name="txtTelefonoContacto" id="txtTelefonoContacto" style="width:140px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Extensión:</td>
				<td>
					<input type="text" class="cajas" name="txtExtension" id="txtExtension" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Móvil:</td>
				<td>
					<input placeholder="Lada" type="text" class="cajas" name="txtLadaMovil" id="txtLadaMovil" style="width:50px"/>
					<input placeholder="Móvil" type="text" class="cajas" name="txtMovil" id="txtMovil" style="width:140px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Móvil 2:</td>
				<td>
					<input placeholder="Lada" type="text" class="cajas" name="txtLadaMovil2" id="txtLadaMovil2" style="width:50px"/>
					<input placeholder="Móvil 2" type="text" class="cajas" name="txtMovil2" id="txtMovil2" style="width:140px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Nextel:</td>
				<td>
					<input placeholder="Lada" type="text" class="cajas" name="txtLadaNextel" id="txtLadaNextel" style="width:50px"/>
					<input placeholder="Nextel" type="text" class="cajas" name="txtNextel" id="txtNextel" style="width:140px"/>
				</td>
			</tr>
			
			 <tr>
				<td class="key">Email:</td>
				<td>
				<input type="text" class="cajas" name="txtEmailContacto" id="txtEmailContacto" style="width:200px"/>
				</td>
			</tr>
			<tr>
				<td class="key">Departamento:</td>
				<td>
					<input type="text" class="cajas" name="txtDepartamento" id="txtDepartamento" style="width:200px"/>
				</td>
			</tr>
			<tr>
				<td class="key">Puesto:</td>
				<td>
					<input type="text" class="cajas" name="txtPuesto" id="txtPuesto" style="width:200px"/>
				</td>
			</tr>
	
		</table>
	</div>
	
	<div id="div-datosFiscales" class="divCliente">
		<table class="admintable" width="100%;">
			<tr>
				<td class="key">Razón social:</td>
				<td>
					<input type="text" class="cajas" name="txtRazonSocial" id="txtRazonSocial" style="width:200px" />
				</td>
			</tr>
			
			<tr>
				<td class="key">RFC:</td>
				<td>
					<input type="text" class="cajas" name="rfc" id="rfc" style="width:200px" />
				</td>
			</tr>
			<tr>
				<td class="key">Calle:</td>
				<td>
					<textarea class="TextArea" name="direccion" id="direccion" style="width:200px"></textarea>
				</td>
			</tr>
			
			 <tr>
				<td class="key">Número:</td>
				<td>
					<input type="text" class="cajas" name="numero" id="numero" style="width:200px"/>
				</td>
			</tr>
			
			 <tr>
				<td class="key">Colonia:</td>
				<td>
					<input type="text" class="cajas" name="colonia" id="colonia" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Código Postal:</td>
				<td>
					<input type="text" class="cajas" name="codigoPostal" id="codigoPostal" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Localidad:</td>
				<td>
					<input type="text" class="cajas" name="localidad" id="localidad" style="width:200px" />
				</td>
			</tr>
			
			 <tr>
				<td class="key">Municipio:</td>
				<td>
					<input type="text" class="cajas" name="txtMunicipio" id="txtMunicipio" style="width:200px" />
				</td>
			</tr>
			
			 <tr>
				<td class="key">Estado:</td>
				<td>
					<input type="text" class="cajas" name="estado" id="estado" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">País:</td>
				<td>
					<input type="text" class="cajas" name="txtPais" id="txtPais" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Régimen fiscal:</td>
				<td>
					<select class="cajas" id="selectRegimenFiscal" name="selectRegimenFiscal" style="width:500px">';

						foreach($regimen as $row)
						{
							echo '<option value="'.$row->idRegimen.'">'.$row->clave.', '.$row->nombre.'</option>';
						}

					echo'
					</select>

				</td>
			</tr>
			
			<tbody style="display: none">
				<tr>
					<th colspan="2">Facturación</th>
				</tr>

				<tr>
					<td class="key">Método de pago:</td>
					<td>
						<select class="cajas" id="selectMetodoPagoCliente" name="selectMetodoPagoCliente" style="width:200px">';

						foreach($metodos as $row)
						{
							echo '<option value="'.$row->idMetodo.'">'.$row->clave.', '.$row->concepto.'</option>';
						}

						echo'
						</select>
					</td>
				</tr>

				<tr>
					<td class="key">Forma de pago:</td>
					<td>
						<input type="text" class="cajas" name="txtFormaPagoCliente" id="txtFormaPagoCliente" style="width:200px" value="Pago en una sola exhibición"/>
					</td>
				</tr>
			</tbody>
			
		</table>
	</div>
	
	<div id="div-cuentasBanco" class="divCliente">
		<table class="admintable" width="100%;">
			<tr>
				<th colspan="2" align="center" class="key">Cuenta</th>
			</tr>
		
			<tr>
				<td class="key">Banco:</td>
				<td>
					<!--<input type="text" class="cajas" name="txtBanco" id="txtBanco" style="width:200px" maxlength="200" placeholder="Seleccione"/>
					<input type="hidden" name="txtIdBanco" id="txtIdBanco" value="0"/>-->
					
					<select class="cajas" id="txtIdBanco" name="txtIdBanco" style="width:200px">
						<option value="0">Seleccione</option>';
						foreach($bancos as $row)
						{
							echo '<option value="'.$row->idBanco.'">'.$row->nombre.'</option>';	
						}
						
					echo'
					</select>
					
				</td>
			</tr>
		
			<tr>
				<td class="key">Emisor:</td>
				<td>
					<select class="cajas" id="selectEmisores" name="selectEmisores" style="width:400px">
						<option value="0">Seleccione</option>';
						foreach($emisores as $row)
						{
							echo '<option value="'.$row->idEmisor.'">('.$row->rfc.')'.$row->nombre.'</option>';	
						}
						
					echo'
					</select>
				</td>
			</tr>
			
			<tr>
				<td class="key">Cuenta:</td>
				<td>
					<input type="text" class="cajas" name="txtCuenta" id="txtCuenta" style="width:200px" maxlength="60"/>
				</td>
			</tr>
			<tr>
				<td class="key">Clabe:</td>
				<td>
					<input type="text" class="cajas" name="txtClabe" id="txtClabe" style="width:200px" maxlength="18" onkeypress="return soloNumerico(event)" maxlength="5"/>
				</td>
			</tr>
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
				<th>Localidad</th>
				<th>Estado</th>
				<th>Referencia</th>
			</tr>';
			
			
			for($i=1;$i<=5;$i++)
			{
				echo '
				<tr>
					<td>'.$i.'</td>
					<td align="center"><input type="text" class="cajas" name="txtCalleEntrega'.$i.'" id="txtCalleEntrega'.$i.'" style="width:200px"/></td>
					<td align="center"><input type="text" class="cajas" name="txtNumeroEntrega'.$i.'" id="txtNumeroEntrega'.$i.'" style="width:70px"/></td>
					<td align="center"><input type="text" class="cajas" name="txtColoniaEntrega'.$i.'" id="txtColoniaEntrega'.$i.'" style="width:100px"/></td>
					<td align="center"><input type="text" class="cajas" name="txtCodigoPostalEntrega'.$i.'" id="txtCodigoPostalEntrega'.$i.'" style="width:80px" maxlength="5"/></td>
					<td align="center"><input type="text" class="cajas" name="txtLocalidadEntrega'.$i.'" id="txtLocalidadEntrega'.$i.'" style="width:140px"/></td>
					<td align="center"><input type="text" class="cajas" name="txtEstadoEntrega'.$i.'" id="txtEstadoEntrega'.$i.'" style="width:140px"/></td>
					<td align="center"><input type="text" class="cajas" name="txtReferenciaEntrega'.$i.'" id="txtReferenciaEntrega'.$i.'" style="width:140px"/></td>
				</tr>';
			}
			
			echo'
			
			
		</table>
	</div>
	
	
	<div id="div-academicos" class="divCliente">
		<table class="admintable" width="100%;">
			<tr>
				<td class="key">Programa:</td>
				<td>
					<div id="obtenerProgramasRegistro" style="width:220px; float: left">
						<select class="cajas" id="selectProgramas" name="selectProgramas" style="width:200px" onchange="sugerirCantidadesAcademico(1)">
							<option value="0">Seleccione</option>';
						
						foreach($programas as $row)
						{
							echo '<option value="'.$row->idPrograma.'|'.$row->cantidadInscripcion.'|'.$row->cantidadColegiatura.'|'.$row->cantidadReinscripcion.'">'.$row->nombre.'</option>';
						}
						
						echo'
						</select>
					</div>
                
                	 <img onclick="obtenerCatalogoProgramas()" src="'.base_url().'img/agregar.png" width="20" height="20" title="Estatus" style="float:left" />
				</td>
			</tr>
			
			<tr>
				<td class="key">Matrícula:</td>
				<td>
					<input type="text" class="cajas" name="txtMatricula" id="txtMatricula" style="width:200px" />
				</td>
			</tr>
			<tr>
				<td class="key">Usuario:</td>
				<td>
					<input type="text" class="cajas" name="txtUsuarioAcademico" id="txtUsuarioAcademico" style="width:200px" />
				</td>
			
			</tr>
			
			 <tr>
				<td class="key">Contraseña:</td>
				<td>
					<input type="password" class="cajas" name="txtPasswordAcademico" id="txtPasswordAcademico" style="width:200px"/>
				</td>
			</tr>
			
			 <tr>
				<td class="key">Incripción:</td>
				<td>
					<input type="text" class="cajas" name="txtInscripcion" id="txtInscripcion" style="width:200px" onkeypress="return soloDecimales(event)" maxlength="7" onchange="calcularTotalesAcademicos()"/>
					&nbsp;&nbsp;
					<label>Periodicidad: </label>
					<input type="text" class="cajas" name="txtCantidadInscripcion" id="txtCantidadInscripcion" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
					&nbsp;&nbsp;
					<label id="lblTotalInscripcion">$0.00</label>
					
				</td>
			</tr>
			
			<tr>
				<td class="key">Colegiatura:</td>
				<td>
					<input type="text" class="cajas" name="txtColegiatura" id="txtColegiatura" style="width:200px" onkeypress="return soloDecimales(event)" maxlength="7" onchange="calcularTotalesAcademicos()"/>
					&nbsp;&nbsp;
					<label>Periodicidad: </label>
					<input type="text" class="cajas" name="txtCantidadColegiatura" id="txtCantidadColegiatura" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
					&nbsp;&nbsp;
					<label id="lblTotalColegiatura">$0.00</label>
				</td>
			</tr>
			
			<tr>
				<td class="key">Reinscripción:</td>
				<td>
					<input type="text" class="cajas" name="txtReinscripcion" id="txtReinscripcion" style="width:200px" onkeypress="return soloDecimales(event)" maxlength="7" onchange="calcularTotalesAcademicos()" />
					&nbsp;&nbsp;
					<label>Periodicidad: </label>
					<input type="text" class="cajas" name="txtCantidadReinscripcion" id="txtCantidadReinscripcion" style="width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
					&nbsp;&nbsp;
					<label id="lblTotalReinscripcion">$0.00</label>
				</td>
			</tr>
			
			 <tr>
				<td class="key">Titulación:</td>
				<td>
					<input type="text" class="cajas" name="txtTitulacion" id="txtTitulacion" style="width:200px" onkeypress="return soloDecimales(event)" maxlength="7" />
				</td>
			</tr>
			
			 <tr>
				<td class="key">Periodo:</td>
				<td>
					<input type="text" class="cajas" name="txtPeriodo" id="txtPeriodo" style="width:200px"/>
				</td>
			</tr>
			
			<tr>
				<td class="key">Trimestre:</td>
				<td>
					<input type="text" class="cajas" name="txtPeriodoActual" id="txtPeriodoActual" style="width:200px" value="1" onkeypress="return soloDecimales(event)" maxlength="2"/>
				</td>
			</tr>

		</table>
	</div>
	
	
	<input type="hidden" id="txtIdClienteDocumentos" name="txtIdClienteDocumentos" value="'.$idCliente.'" />

	<div id="div-documentos" class="divCliente">
		<table class="admintable" width="99%">';
			
			foreach($tiposDocumentos as $row)
			{
				echo '
				<tr>
					<td class="key" style="width:40%">'.$row->nombre.'</td>
					<td>
						<input type="file" id="txtComprobanteCliente'.$row->idTipo.'" name="txtComprobanteCliente'.$row->idTipo.'" />
						
						<div id="listaArchivos'.$row->idTipo.'"></div>
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
						url:				 "'.base_url().'clientes/subirArchivoCliente/'.$row->idTipo.'/'.$idCliente.'/1",
						maxSize:			25,
						sizeError:			"El archivo es demasiado grande",
						invalidExtError:	"No se permite ese tipo de archivos",
						onFileSuccess:function(file,data)
						{
							data	= eval(data);
							
							switch(data[0])
							{
								case "1":
	
									$("#listaArchivos'.$row->idTipo.'").append("<div id=\"documento"+data[3]+"\" class=\"contenidoArchivo\"> <img src=\"'.base_url().'img/borrado.png\" onclick=\"borrarDocumentoTemporal("+data[3]+",1)\" />  "+data[1]+"</div>")
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
		</table>
	</div>
	
	
</form>';