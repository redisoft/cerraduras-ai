<?php
echo '
<div style="width:310px; float: left">
	<select id="selectDirecciones" name="selectDirecciones" class="cajas" style="width:300px">
		<option value="0">Recoleccion en susursal</option>';
		
		foreach($direcciones as $row)
		{
			if(strlen($row->calle)>0 and strlen($row->numero)>0 )
			{
				echo '<option value="'.$row->idDireccion.'">'.$row->calle.' '.$row->numero.' '.$row->colonia.'</option>';
			}
		}
	
	echo'
	</select>
</div>

<div style="width:100px; float: left" align="center">
	<img src="'.base_url().'img/direccion.png" onclick="obtenerDireccionesCliente()" title="Agregar dirección" width="22" /><br />
	Agregar dirección
</div>';
