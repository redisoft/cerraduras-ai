<?php
if($requisiciones!=null)
{
	echo '
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagProcesadas">'.$this->pagination->create_links().'</ul>
	 </div>
	<table class="admintable" width="100%">
		<tr>
			<th>#</th>
			<th>Fecha requisición</th>
			<th>Requisición</th>
			<th>Orden de compra</th>
			<th>Comentarios</th>
			<th>Estatus</th>
			<th width="10%">Acciones</th>
		</tr>';
	
	$i	= $limite;
	foreach($requisiciones as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$onclick	= '';
		$compras	= $this->requisiciones->obtenerComprasRequisicion($row->idRequisicion);
		
		echo '
		<tr '.$estilo.' id="filaRequisicion'.$row->idRequisicion.'">
			<td align="right" '.$onclick.'>'.$i.'</td>
			<td align="center" '.$onclick.'>'.obtenerFechaMesCorto($row->fechaRequisicion).'</td>
			<td align="left"  '.$onclick.'>'.requisicion.$row->folio.'</td>
			<td align="center" '.$onclick.'>';
			$c=1;
			$cerrada	= '0';
			foreach($compras as $com)
			{
				echo $c==1?$com->nombre:', '.$com->nombre;
				$cerrada	= $com->cerrada;
				$c++;
			}
			
			echo'
			</td>
			<td align="left"  '.$onclick.'>'.substr($row->comentarios,0,20).'</td>
			<td align="center">';
				echo $cerrada=='0'?'Procesada':'Cerrada';
			echo'
			</td>
			<td align="center">
				<a href="'.base_url().'requisiciones/formatoRequisicion/'.$row->idRequisicion.'">
					<img src="'.base_url().'img/pdf.png" title="PDF" width="22"/>
				</a>
				<br />
				<a>PDF</a>
			</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	<div style="width:90%">
		<ul id="pagination-digg" class="ajax-pagProcesadas">'.$this->pagination->create_links().'</ul>
	 </div>';
}
else
{
	echo '<div class="Error_validar">Sin registro de requisiciones procesadas</div>';
}