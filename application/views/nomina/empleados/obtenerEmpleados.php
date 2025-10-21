<?php
if(!empty($empleados))
{
	$agregar	=$this->input->post('agregar');
	
	echo'
	<script>
	$(document).ready(function()
	{
		$("#tablaEmpleados tr:even").addClass("sombreado");
		$("#tablaEmpleados tr:odd").addClass("sinSombra");  
	});
	</script>
	
	<div class="paginador">
		<ul id="pagination-digg" class="ajax-pagEmpleados">'.$this->pagination->create_links().'</ul>
	</div>
	
	<div id="procesandoInformacion"></div>
	
    <table width="100%" class="admintable" id="tablaEmpleados">
		 <tr >
			<th class="encabezadoPrincipal" width="5%" align="center" valign="middle">#</th>
			<th class="encabezadoPrincipal" align="center">Nombre</th>
			<th class="encabezadoPrincipal" align="center">Número</th>
			<th class="encabezadoPrincipal" align="center">CURP</th>
			<th class="encabezadoPrincipal" align="center">Periodicidad de pago</th>
			<th class="encabezadoPrincipal" align="center">Puesto</th>
			<th class="encabezadoPrincipal" align="center">Departamento</th>
			<th class="encabezadoPrincipal" width="15%" align="center" valign="middle">Acciones</th>
		 </tr>';

	$i=$limite;
	foreach($empleados as $row)
	{
		echo '
		<tr>
			<td align="center">'.$i.'</td>
			<td align="center" valign="middle">'.$row->nombre.'</td>
			<td align="center" valign="middle">'.$row->numeroEmpleado.'</td>
			<td align="center" valign="middle">'.$row->curp.'</td>
			<td align="center" valign="middle">'.$row->periodicidadPago.'</td>
			<td align="center" valign="middle">'.$row->puesto.'</td>
			<td align="center" valign="middle">'.$row->departamento.'</td>
			<td align="center" class="vinculos" valign="middle">
			
				<img id="btnEditarEmpleado'.$i.'" onclick="accesoEditarEmpleado('.$row->idEmpleado.')" src="'.base_url().'img/editar.png" title="Editar empleado">
				&nbsp;&nbsp;
				<img id="btnBorrarEmpleado'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar empleado '.$row->nombre.'" onclick="accesoBorrarEmpleado('.$row->idEmpleado.')" />';
				
				if($agregar==1)
				{
					echo '
					&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/fichero.png" title="Agregar empleado '.$row->nombre.' a recibo" onclick="agregarEmpleadoRecibo('.$i.')" />';
				}
				
				echo'<br />
				<a id="a-btnEditarEmpleado'.$i.'">Editar</a>
				<a id="a-btnBorrarEmpleado'.$i.'">Borrar</a>';
				
				if($agregar==1)
				{
					echo' <a>Agregar</a>';
				}
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarEmpleado'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarEmpleado'.$i.'\');
					</script>';
				}
				
				echo'
				<input type="hidden" id="idEmpleado'.$i.'" value="'.$row->idEmpleado.'" />
				<input type="hidden" id="nombreEmpleado'.$i.'" value="'.$row->nombre.'" />
				<input type="hidden" id="rfcEmpleado'.$i.'" value="'.$row->rfc.'" />
				<input type="hidden" id="puestoEmpleado'.$i.'" value="'.$row->puesto.'" />
				<input type="hidden" id="departamentoEmpleado'.$i.'" value="'.$row->departamento.'" />
				
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	
	<div class="paginador">
		<ul id="pagination-digg" class="ajax-tablaEmpleados">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'
	<div class="Error_validar" style="margin-top:2px; width:99%; margin-bottom: 5px;">
		Sin registro de empleados
	</div>';
}
?>
