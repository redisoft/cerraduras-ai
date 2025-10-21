<?php
if($registros!=null)
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagPedimentos">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th width="3%">#</th>
            <th>Fecha</th>
			<th>Pedimento</th>
			<th width="18%">Acciones</th>
		</tr>';
	
	$i=$limite;
	foreach($registros as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo '
		<tr '.$estilo.' id="filaRegistro'.$row->idPedimento.'">
			<td align="right">'.$i.'</td>
            <td align="center">'.obtenerFechaMesCorto($row->fecha).'</td>
            <td align="center">
				'.$row->pedimento.'
				<input type="hidden" id="txtNumeroPedimento'.$row->idPedimento.'" value="'.$row->pedimento.'" />
			</td>
			<td align="center">
				<img id="btnAgregarPedimentos'.$i.'" src="'.base_url().'img/add.png" title="Agregar" width="22" style="cursor:pointer" onclick="agregarPedimento('.$row->idPedimento.')"/>                
                &nbsp;&nbsp;
                <img id="btnEditarPedimentos'.$i.'" src="'.base_url().'img/editar.png" title="Editar" width="22" style="cursor:pointer" onclick="formularioEditarPedimentos('.$row->idPedimento.')"/>                
                &nbsp;&nbsp;
                <img id="btnBorrarPedimentos'.$i.'" src="'.base_url().'img/borrar.png" title="Borrar" 	width="22" style="cursor:pointer" onclick="borrarRegistroPedimentos('.$row->idPedimento.')"/>
				<br />
				<a id="a-btnAgregarPedimentos'.$i.'">Agregar</a>&nbsp;
                <a id="a-btnEditarPedimentos'.$i.'">Editar</a>&nbsp;
                <a id="a-btnBorrarPedimentos'.$i.'">Borrar</a>';
				
				if($permiso[2]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnEditarPedimentos'.$i.'\');
					</script>';
				}

				if($permiso[3]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnBorrarPedimentos'.$i.'\');
					</script>';
				}

			echo'
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagPedimentos">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	echo '<div class="Error_validar">Sin registros</div>';
}
