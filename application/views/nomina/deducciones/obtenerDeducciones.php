<?php
if(!empty($deducciones))
{
	$agregar	=$this->input->post('agregar');
	
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaDeducciones tr:even").addClass("sombreado");
		$("#tablaDeducciones tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div class="paginador">
		<ul id="pagination-digg" class="ajax-pagDeducciones">'.$this->pagination->create_links().'</ul>
	</div>
	
	<div id="procesandoInformacion"></div>
	
    <table width="100%" class="admintable" id="tablaDeducciones">
		 <tr >
			<th class="encabezadoPrincipal" width="5%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" align="center">Clave</th>
			<th class="encabezadoPrincipal" align="center">Concepto</th>
			<th class="encabezadoPrincipal" align="center">Tipo de deducción</th>
			<th class="encabezadoPrincipal" align="center">Importe gravado</th>
			<th class="encabezadoPrincipal" align="center">Importe exento</th>
			<th class="encabezadoPrincipal" width="15%" align="center" valign="middle">Acciones</th>
		 </tr>';

	$i=$limite;
	foreach($deducciones as $row)
	{
		echo '
		<tr>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->clave.'</td>
			<td align="center" valign="middle">'.$row->concepto.'</td>
			<td align="center" valign="middle">'.$row->deduccion.'</td>
			<td align="center" valign="middle">'.number_format($row->importeGravado,2).'</td>
			<td align="center" valign="middle">'.number_format($row->importeExento,2).'</td>
			<td align="center" class="vinculos" valign="middle">
			
				<img id="btnEditarDeduccion'.$i.'" onclick="accesoEditarDeduccion('.$row->idCatalogoDeduccion.')" src="'.base_url().'img/editar.png" title="Editar deducción">
				&nbsp;&nbsp;
				<img id="btnBorrarDeduccion'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar deducción '.$row->concepto.'" onclick="accesoBorrarDeduccion('.$row->idCatalogoDeduccion.')" />';
				
				if($agregar==1)
				{
					echo '
					&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/fichero.png" title="Agregar deducción '.$row->concepto.' a recibo" onclick="agregarDeduccionRecibo('.$i.')" />';
				}
				
				echo'
				<br />
				<a id="a-btnEditarDeduccion'.$i.'">Editar</a>
				<a id="a-btnBorrarDeduccion'.$i.'">Borrar</a>';
				
				if($agregar==1)
				{
					echo' <a>Agregar</a>';
				}
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarDeduccion'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarDeduccion'.$i.'\');
					</script>';
				}
				
				echo'
				<input type="hidden" id="idDeduccion'.$i.'" value="'.$row->idCatalogoDeduccion.'" />
				<input type="hidden" id="importeGravado'.$i.'" value="'.round($row->importeGravado,2).'" />
				<input type="hidden" id="importeExento'.$i.'" value="'.round($row->importeExento,2).'" />
				<input type="hidden" id="conceptoDeduccion'.$i.'" value="'.$row->concepto.'" />
				<input type="hidden" id="claveDeduccion'.$i.'" value="'.$row->clave.'" />
				<input type="hidden" id="tipoDeduccion'.$i.'" value="'.$row->claveDeduccion.'" />
				<input type="hidden" id="nombreDeduccion'.$i.'" value="'.$row->deduccion.'" />';
		
		echo '
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	
	<div class="paginador">
		<ul id="pagination-digg" class="ajax-pagDeducciones">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:99%; margin-bottom: 5px;">
		Sin registro de deducciones
	</div>';
}
?>
