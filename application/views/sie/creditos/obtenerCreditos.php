<?php
#if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="ajax-pagCreditos">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<div class="table-responsive">
    <table class="table table-striped">
		<tr>
			<th colspan="9" class="encabezadoPrincipal">
				Créditos
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fuente</th>
			<th>Monto</th>
			<th>Interés anual</th>
			<th>Adeudo actual</th>
			<th>Frecuencia</th>
			<th>Fecha de pago</th>
			<th>Pago</th>
			<th width="15%">Acciones</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($creditos as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
			<td width="5%" align="right">'.$i.'</td>
			<td>'.$row->fuente.'</td>
			<td align="right">$'.number_format($row->monto,decimales).'</td>
			<td align="center">$'.number_format($row->interesAnual,decimales).'%</td>
			<td align="right">$'.number_format($row->adeudoActual,decimales).'</td>
			<td>'.$row->frecuencia.'</td>
			<td align="center">'.obtenerFechaMesCorto($row->fechaPago).'</td>
			<td align="right">$'.number_format($row->pago,decimales).'</td>
			<td align="center">
				<img id="btnEditar'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="obtenerCredito('.$row->idCredito.')" />
				&nbsp;&nbsp;
				<img id="btnBorrar'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onclick="borrarCredito('.$row->idCredito.')" />
				
				<br />
				
				<a id="a-btnEditar'.$i.'">Editar</a>
				<a id="a-btnBorrar'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
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
	
	echo '</table></div>';
}
