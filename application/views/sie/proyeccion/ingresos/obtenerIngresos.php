<?php
#if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%" align="center">
		<ul id="pagination-digg" class="ajax-pagIngresos">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<table class="admintable" width="100%">
		<tr>
			<td colspan="5" class="sinbordeTransparente">
				<ul class="menuTabs">
					<li style="margin-top: 0px" '.($cobrado==0?'class="activado"':'').' onclick="filtroIngresos(0,0)">Ingresos</li>
					<li style="margin-top: 0px; color: green" '.($idEscenario==1?'class="activado"':'').' onclick="filtroIngresos(0,1)">Probable</li>
					<li style="margin-top: 0px; color: #FF0" '.($idEscenario==2?'class="activado"':'').' onclick="filtroIngresos(0,2)">Poco probable</li>
				</ul>
			</td>
		</tr>
		
		<tr>
			<th colspan="5" class="encabezadoPrincipal">
				Ingresos
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Concepto</th>
			<th align="right">Proyección<br />$'.number_format($total,decimales).'</th>
			<th width="19%">Acciones</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($ingresos as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').' '.($row->cobrado=='1'?'style="background: #CCC;"':'').'>
			<td width="5%" align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td>'.$row->concepto.(obtenerEscenarioIngreso($row)).'</td>
			<td align="right">$'.number_format($row->importe,decimales).'</td>
			<td align="center">
				'.($row->cobrado=='0'?'<img id="btnCobrado'.$i.'" src="'.base_url().'img/pagos.png" width="22" height="22" onclick="obtenerIngresoCobrado('.$row->idIngreso.')" />&nbsp;&nbsp;':'').'
				<img id="btnEditarIngreso'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerIngreso('.$row->idIngreso.')" />
				&nbsp;&nbsp;
				<img id="btnBorrarIngreso'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarIngreso('.$row->idIngreso.')" />
				
				<br />
				'.($row->cobrado=='0'?'<a id="a-btnCobrado'.$i.'">Cobro</a>':'').'
				<a id="a-btnEditarIngreso'.$i.'">Editar</a>
				<a id="a-btnBorrarIngreso'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnCobrado'.$i.'\');
						desactivarBotonSistema(\'btnEditarIngreso'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarIngreso'.$i.'\');
					</script>';
				}
			
			echo'
			</td>
		</tr>';

		$i++;
	}
	
	echo '</table>';
}
