<?php
if(!empty ($percepciones))
{
	$agregar	=$this->input->post('agregar');
	
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaPercepciones tr:even").addClass("sombreado");
		$("#tablaPercepciones tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div class="paginador">
		<ul id="pagination-digg" class="ajax-pagPercepciones">'.$this->pagination->create_links().'</ul>
	</div>
	
	<div id="procesandoInformacion"></div>
	
    <table width="100%" class="admintable" id="tablaPercepciones">
		 <tr >
			<th class="encabezadoPrincipal" width="5%" align="center" valign="middle">No.</th>
			<th class="encabezadoPrincipal" align="center">Clave</th>
			<th class="encabezadoPrincipal" align="center">Concepto</th>
			<th class="encabezadoPrincipal" align="center">Tipo de percepción</th>
			<th class="encabezadoPrincipal" align="center">Importe gravado</th>
			<th class="encabezadoPrincipal" align="center">Importe exento</th>
			<th class="encabezadoPrincipal" width="15%" align="center" valign="middle">Acciones</th>
		 </tr>';

	$i=$limite;
	foreach($percepciones as $row)
	{
		echo '
		<tr>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->clave.'</td>
			<td align="center" valign="middle">'.$row->concepto.'</td>
			<td align="center" valign="middle">'.$row->percepcion.'</td>
			<td align="center" valign="middle">'.number_format($row->importeGravado,2).'</td>
			<td align="center" valign="middle">'.number_format($row->importeExento,2).'</td>
			<td align="center" class="vinculos" valign="middle">
			
				<img id="btnEditarPercepcion'.$i.'" onclick="accesoEditarPercepcion('.$row->idCatalogoPercepcion.')" src="'.base_url().'img/editar.png" title="Editar percepción">
				&nbsp;&nbsp;
				<img id="btnBorrarPercepcion'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar percepción '.$row->concepto.'" onclick="accesoBorrarPercepcion('.$row->idCatalogoPercepcion.')" />';
				
				if($agregar==1)
				{
					echo '
					&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/fichero.png" title="Agregar percepción '.$row->concepto.' a recibo" onclick="agregarPercepcionRecibo('.$i.')" />';
				}
	
				echo'
				<br />
				<a id="a-btnEditarPercepcion'.$i.'">Editar</a>
				<a id="a-btnBorrarPercepcion'.$i.'">Borrar</a>';
				
				if($agregar==1)
				{
					echo' <a>Agregar</a>';
				}
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarPercepcion'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarPercepcion'.$i.'\');
					</script>';
				}
					
				echo'
				<input type="hidden" id="idPercepcion'.$i.'" value="'.$row->idCatalogoPercepcion.'" />
				<input type="hidden" id="importeGravado'.$i.'" value="'.round($row->importeGravado,2).'" />
				<input type="hidden" id="importeExento'.$i.'" value="'.round($row->importeExento,2).'" />
				<input type="hidden" id="conceptoPercepcion'.$i.'" value="'.$row->concepto.'" />
				<input type="hidden" id="clavePercepcion'.$i.'" value="'.$row->clave.'" />
				<input type="hidden" id="tipoPercepcion'.$i.'" value="'.$row->clavePercepcion.'" />
				<input type="hidden" id="nombrePercepcion'.$i.'" value="'.$row->percepcion.'" />
			
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	
	<div class="paginador">
		<ul id="pagination-digg" class="ajax-pagPercepciones">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:99%; margin-bottom: 5px;">
		Sin registro de percepciones
	</div>';
}
?>
