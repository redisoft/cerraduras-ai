<?php
#if($movimientos!=null)
{
	echo '
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagHistorialMovimientos">'.$this->pagination->create_links().'</ul>
	</div>
	
	<div id="generandoReporte"></div>
	<table class="admintable" width="100%">
	<tr>
		<th class="encabezadoPrincipal" colspan="7" style="border-right:none">
			<img id="btnExportarPdfReporte" onclick="reporteHistorialMovimientos()" src="'.base_url().'img/pdf.png" width="22" title="Pdf" />
			&nbsp;&nbsp;&nbsp;
			<img id="btnExportarExcelReporte" onclick="excelHistorialMovimientos()" src="'.base_url().'img/excel.png" width="22" title="Excel" />
			<br />
			PDF
			&nbsp;&nbsp;
			Excel';
			
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnExportarPdfReporte\');
					desactivarBotonSistema(\'btnExportarExcelReporte\');
				</script>';
			}
			
		echo'
		</th>
		
	</tr>
	<tr>
		<th width="4%">#</th>
		<th width="10%">Fecha</th>
		<th width="6%">Hora</th>
		<th width="17%">
			<select class="cajas" id="selectUsuario" name="selectUsuario" onchange="obtenerHistorialMovimientos()" style="width:150px">
				<option value="">Usuario</option>';
				
				foreach($usuarios as $row)
				{
					echo '<option '.($row->usuario==$usuario?'selected="selected"':'').' value="'.$row->usuario.'">'.$row->nombre.' ('.$row->usuario.')</option>';
				}
			
			echo'
			</select>
		</th>
		<th width="15%">
			<select class="cajas" id="selectModulo" name="selectModulo" onchange="obtenerHistorialMovimientos()" style="width:150px">
				<option value="">Módulo</option>';
				
				foreach($modulos as $row)
				{
					echo '<option '.($row->modulo==$modulo?'selected="selected"':'').'>'.$row->modulo.'</option>';
				}
			
			echo'
			</select>
		</th>
		<th>Acción</th>
		<th width="30%">Descripción</th>
	</tr>';

	$i=$limite;
	foreach($movimientos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
			<td align="center">'.obtenerHora($row->fecha).'</td>
			<td align="center">'.$row->nombre.'('.$row->usuario.')</td>
			<td align="center">'.$row->modulo.'</td>
			<td align="left">'.$row->accion.'</td>
			<td align="left">'.$row->descripcion.'</td>
		</tr>';
		
		$i++;
		
	}
	
	echo '</table>
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagHistorialMovimientos">'.$this->pagination->create_links().'</ul>
	</div>';
}
/*else
{
	echo '<div class="Error_validar">Sin registro de movimientos</div>';
}*/