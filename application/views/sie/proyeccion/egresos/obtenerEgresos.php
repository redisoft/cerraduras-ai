<?php
#if($egresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="ajax-pagEgresos">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<table class="admintable" width="100%">
		<tr>
			<td colspan="5" class="sinbordeTransparente">
				<ul class="menuTabs">
					<li style="margin-top: 0px" '.($pagado==0?'class="activado"':'').' onclick="filtroEgresos(0,0)">Egresos no pagados</li>
					<li style="margin-top: 0px" '.($pagado==1?'class="activado"':'').' onclick="filtroEgresos(1,0)">Egresos pagados</li>
					
					<li style="margin-top: 0px; color: green" '.($idEscenario==1?'class="activado"':'').' onclick="filtroEgresos(0,1)">Importante</li>
					<li style="margin-top: 0px; color: #FF0" '.($idEscenario==2?'class="activado"':'').' onclick="filtroEgresos(0,2)">Secundario</li>
				</ul>
			</td>
		</tr>
		<tr>
			<th colspan="5" class="encabezadoPrincipal">
				Egresos
			</th>
		</tr>
		<tr>
			<th>#</th>
			<!--<th>Fecha</th>-->
			<th>Fecha de pago</th>
			<th>Concepto</th>
			<th align="right">Proyección<br />$'.number_format($total,decimales).'</th>
			<th width="23%">Acciones</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($egresos as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').' '.($row->pagado=='1'?'style="background: #CCC;"':'').'>
			<td width="5%" align="right">'.$i.'</td>
			<!--<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>-->
			<td align="center">
				'.($permiso[2]->activo==0?obtenerFechaMesCorto($row->fechaPago):'<input type="text" class="cajas" id="txtFechaPago'.$row->idEgreso.'" value="'.$row->fechaPago.'" style="width:90px" onchange="editarFechaPago('.$row->idEgreso.')"/>').'
				<script>
					$("#txtFechaPago'.$row->idEgreso.'").datepicker()
				</script>
			</td>
			<td>'.$row->concepto.($row->pagado=='1'?'<br /><i>Pagado</i>':'').(obtenerEscenarioIngreso($row)).'</td>
			<td align="right">$'.number_format($row->importe,decimales).'</td>
			<td align="center">
				'.($row->pagado=='0'?'<img id="btnPagado'.$i.'" src="'.base_url().'img/pagos.png" width="22" height="22" onclick="obtenerEgresoPagado('.$row->idEgreso.')" />&nbsp;&nbsp;':'').'
				<img id="btnEditar'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerEgreso('.$row->idEgreso.')" />
				&nbsp;&nbsp;
				<img id="btnBorrar'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarEgreso('.$row->idEgreso.')" />
				
				<br />
				'.($row->pagado=='0'?'<a id="a-btnPagado'.$i.'">Pagado</a>':'').'
				<a id="a-btnEditar'.$i.'">Editar</a>
				<a id="a-btnBorrar'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnPagado'.$i.'\');
						desactivarBotonSistema(\'btnEditar'.$i.'\');
					</script>';
				}
				
				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrar'.$i.'\');
					</script>';
				}
			
			echo'
			</td>
		</tr>';

		$i++;
	}
	
	echo '</table>';
}
?>
<!--<div style="background: #FFC">-->
