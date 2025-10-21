<?php
echo'
<script>
$(document).ready(function()
{
	calcularTotalesAcademicos()
});
</script>
<input type="hidden" id="txtIdClienteFicha" value="'.$cliente->idCliente.'" />
<table class="admintable" width="100%;">
	<tr>
		<th colspan="4"> Datos del '.(sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente').'</th>
	</tr>';
	
	if(sistemaActivo=='IEXE')
	{
		echo '
			<tr>
				<td class="key">Alumno:</td>
				<td colspan="3">'.$cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno.'</td>
			</tr>';
	}
	
	echo'
	<tr>
		<td style="width:15%" class="key">Empresa:</td>
		<td style="width:35%" >'.$cliente->empresa.'</td>
		<td style="width:15%" class="key">Grupo:</td>
		<td>'.$cliente->grupo.'</td>
	</tr>
	
	<tr>
		<td class="key">Calle:</td>
		<td>'.$cliente->calle.'</td>
		<td class="key">Número:</td>
		<td>'.$cliente->numero.'</td>
	</tr>
	
	<tr>
		<td class="key">Colonia:</td>
		<td>'.$cliente->colonia.' </td>
		<td class="key">Localidad:</td>
		<td>'.$cliente->localidad.' </td>
	</tr>
	
	<tr>
		<td class="key">Municipio:</td>
		<td>'.$cliente->municipio.' </td>
		<td class="key">Estado:</td>
		<td>'.$cliente->estado.' </td>
	</tr>

	<tr>
		<td class="key">Teléfono:</td>
		<td>'.$cliente->telefono.'</td>
		<td class="key">Email:</td>
		<td>'.$cliente->email.'</td>
	</tr>
	
	<tr>
		<td class="key">Página 1:</td>
		<td>'.$cliente->web.'</td>
		<td class="key">Página 2:</td>
		<td>'.$cliente->web2.'</td>
	</tr>
	
	<tr>
		<td class="key">Página 3:</td>
		<td>'.$cliente->web3.'</td>
		<td class="key">Alias:</td>
		<td>'.$cliente->alias.'</td>
	</tr>
	
	<tr>
		<td class="key">Comentarios:</td>
		<td colspan="3">'.$cliente->comentarios.'</td>
	</tr>
	
	<tr>
		<td class="key">Dirección envío:</td>
		<td colspan="3">'.$cliente->direccionEnvio.' '.$cliente->localidadEnvio.' '.$cliente->estadoEnvio.' '.$cliente->codigoPostalEnvio.'</td>
	</tr>
	
</table>';

if(sistemaActivo=='IEXE')
{
	echo '
	<table class="admintable" width="50%;" style="float: left">
		<tr>
			<th colspan="2">Académicos</th>
		</tr>
		<tr>
			<td class="key" style="width:30%">Programa:</td>
			<td>'.$academico->programa.'</td>
		</tr>
		
		<tr>
			<td class="key">Matrícula:</td>
			<td>
				'.$academico->matricula.'
			</td>
		</tr>
		<tr>
			<td class="key">Usuario:</td>
			<td>
				'.$academico->usuario.'
			</td>
		
		</tr>
		
		 <tr>
			<td class="key">Contraseña:</td>
			<td>
				'.$academico->password.'
			</td>
		</tr>
		
		 <tr>
			<td class="key">Incripción:</td>
			<td>
				<input type="text" readonly="readonly" class="cajasTransparentes" name="txtInscripcion" id="txtInscripcion" style="text-align: left; width:80px" value="'.$academico->inscripcion.'" onchange="calcularTotalesAcademicos()" onkeypress="return soloDecimales(event)" maxlength="7"/>
				&nbsp;&nbsp;
				<label>Periodicidad: </label>
				<input type="text" readonly="readonly" class="cajasTransparentes" name="txtCantidadInscripcion" id="txtCantidadInscripcion" value="'.$academico->cantidadInscripcion.'" style="text-align: left; width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
				&nbsp;&nbsp;
				<label id="lblTotalInscripcion">$0.00</label>
			</td>
		</tr>
		
		<tr>
			<td class="key">Colegiatura:</td>
			<td>
				<input type="text" readonly="readonly" class="cajasTransparentes" name="txtColegiatura" id="txtColegiatura" style="text-align: left; width:80px" value="'.$academico->colegiatura.'" onchange="calcularTotalesAcademicos()" onkeypress="return soloDecimales(event)" maxlength="7"/>
				&nbsp;&nbsp;
				<label>Periodicidad: </label>
				<input type="text" readonly="readonly" class="cajasTransparentes" name="txtCantidadColegiatura" id="txtCantidadColegiatura" value="'.$academico->cantidadColegiatura.'" style="text-align: left; width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
				&nbsp;&nbsp;
				<label id="lblTotalColegiatura">$0.00</label>
			</td>
		</tr>
		
		<tr>
			<td class="key">Reinscripción:</td>
			<td>
				<input type="text" readonly="readonly" class="cajasTransparentes" name="txtReinscripcion" id="txtReinscripcion" style="text-align: left; width:80px" value="'.$academico->reinscripcion.'" onchange="calcularTotalesAcademicos()" onkeypress="return soloDecimales(event)" maxlength="7"/>
				&nbsp;&nbsp;
				<label>Periodicidad: </label>
				<input type="text" readonly="readonly" class="cajasTransparentes" name="txtCantidadReinscripcion" id="txtCantidadReinscripcion" value="'.$academico->cantidadReinscripcion.'" style="text-align: left; width:50px" onkeypress="return soloDecimales(event)" maxlength="2" onchange="calcularTotalesAcademicos()"/>
				&nbsp;&nbsp;
				<label id="lblTotalReinscripcion">$0.00</label>
			</td>
		</tr>
		
		 <tr>
			<td class="key">Titulación:</td>
			<td>
				'.$academico->titulacion.'
			</td>
		</tr>
		
		 <tr>
			<td class="key">Periodo:</td>
			<td>
				'.$academico->periodo.'
			</td>
		</tr>
	</table>
	
	
	<table class="admintable" width="50%;" style="float: left">
		<tr>
			<th colspan="2">Documentos</th>
		</tr>';
		
		foreach($documentos as $row)	
		{
			echo '
			<tr>
				<td class="key" style="width:30%">'.$row->nombre.':</td>
				<td>'.($row->numero>0?'Si':'No').'</td>
			</tr>';
		}
		
	echo'
	</table>';
}
	
echo'
<table class="admintable" width="100%;">
	<tr>
		<th colspan="5"> Contactos</th>
	</tr>
	<tr>
		<th align="center">Nombre</th>
		<th align="center">Email</th>
		<th align="center">Teléfono</th>
		<th align="center">Departamento</th>
		<th align="center">Extensión</th>
	</tr>';
	
	$i=1;
	foreach($contactos as $contacto)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo'
		<tr '.$estilo.'>
		 <td align="left">'.$contacto->nombre.'</td>
		 <td align="left">'.$contacto->email.'</td>
		 <td align="left">'.$contacto->telefono.'</td>
		 <td align="left">'.$contacto->direccion.'</td>
		 <td align="center">'.$contacto->extension.'</td>
		</tr>';
		
		$i++;
	}
	
echo'</table>';

echo'
<table class="admintable" width="100%;">
	<tr>
		<th colspan="4" align="center" style="border-right:none">Cuentas de banco</th>
		
	 </tr>
	 <tr>
		<th>#</th>
		<th>Cuenta</th>
		<th>Clabe</th>
		<th>Banco</th>
	<!--	<th>Acciones</th>-->
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
			<td width="30%" align="center">'.$row->banco.'</td>
			<!--<td align="center" width="15%">
				<img src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerCuenta('.$row->idCuenta.')" />
				&nbsp;&nbsp;
				<img src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarCuentaCliente('.$row->idCuenta.')" />
				<br />
				<a>Editar</a>
				<a>Borrar</a>
			</td>-->
		 </tr>';
		 
		 $i++;
	 }
	 
 echo'
 </table>';