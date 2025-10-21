
<?php
$empresa	= $cliente->empresa;

if(sistemaActivo=='IEXE')
{
	if(strlen($cliente->nombre)>0)
	{
		$empresa	= $cliente->nombre.' '.$cliente->paterno.' '.$cliente->materno;
	}
}

echo '
<form id="frmEditarResponsable" name="frmEditarResponsable">
	<input type="hidden" name="txtIdSeguimiento" value="'.$seguimiento->idSeguimiento.'" id="txtIdSeguimiento" />
	<div id="enviandoBitacora"></div>
	<table class="admintable" width="100%;">
		<tr>
			<td class="key">'.(sistemaActivo=='IEXE'?'Alumno/Cliente':'Cliente').':</td>
			<td>'.$empresa.'</td>
		</tr>
		
		<tr>
			<td class="key">Folio:</td>
			<td>'.obtenerFolioSeguimiento($seguimiento->folio).'</td>
		</tr>
	
		<tr>
			<td class="key">Responsable:</td>
			<td>
				<select id="selectResponsable" name="selectResponsable" class="cajas" onchange="sugerirCorreo()">';
				
				foreach($responsables as $row)
				{
					$seleccionado=$row->idResponsable==$seguimiento->idResponsable?'selected="selected"':'';
					
					echo '<option '.$seleccionado.' value="'.$row->idResponsable.'|'.$row->correo.'">'.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</td>
		</tr>
	</table>
</form>';