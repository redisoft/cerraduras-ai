<?php
#echo '<input type="hidden" id="txtIdProducto" value="'.$idProducto.'">';

#if($egresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%; text-align:center;" align="center">
		<ul id="pagination-digg" class="ajax-pagEgr">'.$this->pagination->create_links().'</ul>
	</div>';
	
	echo'
	<table class="admintable" width="111%">
		<tr>
			<th colspan="15" class="encabezadoPrincipal">
				Lista de egresos
				<img src="'.base_url().'img/excel.png" width="22" height="22" onclick="excelEgresos()" />
			</th>
		</tr>
		<tr>
			<th>#</th>
			<th>Fecha</th>
			<th>Proveedor</th>
			<th>
				<select class="cajas" id="selectNiveles1Busqueda" name="selectNiveles1Busqueda" style="width:90px" onchange="obtenerNiveles2Busqueda()">
					<option value="0">Nivel 1</option>';
					
					foreach($niveles1 as $row)
					{
						echo '<option '.($idNivel1==$row->idNivel1?'selected="selected"':'').' value="'.$row->idNivel1.'">'.$row->nombre.'</option>';
					}
				
				echo'
				</select>
			</th>
			<th>Monto<br /> $'.number_format($totales,decimales).'</th>
			<th>Forma de pago</th>
			<th>Cuenta</th>
			<th>Cheque / Trans.</th>
			<th>Nombre</th>
			<th id="obtenerNiveles2Busqueda">
				<select class="cajas" id="selectNiveles2Busqueda" name="selectNiveles2Busqueda" style="width:105px" onchange="obtenerNiveles3Busqueda()">
					<option value="0">Nivel 2</option>';
					
					foreach($niveles2 as $row)
					{
						echo '<option '.($idNivel2==$row->idNivel2?'selected="selected"':'').' value="'.$row->idNivel2.'">'.$row->nombre.'</option>';
					}
				
				echo'
				</select>
			</th>
			<th>Descripción del producto / servicio</th>
			<th id="obtenerNiveles3Busqueda">
				<select class="cajas" id="selectNiveles3Busqueda" name="selectNiveles3Busqueda" style="width:85px">
					<option value="0">Nivel 3</option>';
					
					foreach($niveles3 as $row)
					{
						echo '<option '.($idNivel3==$row->idNivel3?'selected="selected"':'').' value="'.$row->idNivel3.'">'.$row->nombre.'</option>';
					}
				
				echo'
				</select>
			</th>
			<th>
				<select class="cajas" id="selectPersonalBusqueda" name="selectPersonalBusqueda" style="width:100px" onchange="obtenerOtrosEgresos()">
					<option value="0">Responsable</option>';
					
					foreach($personal as $row)
					{
						echo '<option '.($idPersonal==$row->idPersonal?'selected="selected"':'').' value="'.$row->idPersonal.'">'.$row->nombre.'</option>';
					}
				
				echo'
				</select>
			</th>
			<th>Comentarios</th>
			<th width="22%">Acciones</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($egresos as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$proveedor		=$this->proveedores->obtenerProveedor($row->idProveedor);
		
		echo'
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td>';
				echo $proveedor!=null?$proveedor->empresa:'';
			echo'</td>
			<td>'.$row->nivel1.'</td>
			<td align="right">$'.number_format($row->pago,2).'</td>
			<td>'.$row->formaPago.'</td>
			<td>';
				if($row->idCuenta>0)
				{
					$cuenta	= $this->bancos->obtenerCuenta($row->idCuenta);
					echo (strlen($cuenta->cuenta)>0?$cuenta->cuenta:$cuenta->tarjetaCredito).'<br />'.$cuenta->banco;
				}
			echo'
			</td>
			<td align="center">'.$row->cheque.$row->transferencia.'</td>
			<td>';
				$nombre	=$this->administracion->obtenerNombre($row->idNombre);
				echo $nombre!=null?$nombre->nombre:'';
				
			echo'</td>
			<td>'.$row->nivel2.'</td>
			<td>'.(strlen($row->productoCatalogo)>1?$row->productoCatalogo:$row->producto).'</td>
			<td>'.$row->nivel3.'</td>
			<td>'.$row->personal.'</td>
			<td>'.nl2br($row->comentarios).'</td>
			<td align="left">
			
				&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnCajaChica'.$i.'" src="'.base_url().'img/caja.png" title="Caja chica" width="22" height="22" onclick="obtenerCajaChica('.$row->idEgreso.')" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				&nbsp;
				<img id="btnEditar'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="accesoEditarEgreso('.$row->idEgreso.')" />
				
				&nbsp;&nbsp;
				<img id="btnComprobantes'.$i.'" src="'.base_url().'img/subir.png" width="22"  onclick="obtenerComprobantesEgresos('.$row->idEgreso.')"  title="Comprobantes" />';
				
				if($row->formaPago=='Cheque')
				{
					echo'&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/poliza.png" width="22"  onclick="window.open(\''.base_url().'administracion/poliza/'.$row->idEgreso.'\')"  title="Póliza " />';
				}
				
				if($row->formaPago!='Cheque')
				{
					echo'&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/recibo.png" width="22"  onclick="window.open(\''.base_url().'administracion/recibo/'.$row->idEgreso.'\')"  title="Póliza " />';
				}
				
				echo'
				&nbsp;
				<img id="btnBorrar'.$i.'"  src="'.base_url().'img/borrar.png" width="22" height="22" onclick="accesoBorrarEgreso('.$row->idEgreso.')" />
				
				<br />
				<a id="a-btnCajaChica'.$i.'">Caja chica</a>
				<a id="a-btnEditar'.$i.'">Editar</a>
				<a id="a-btnComprobantes'.$i.'">Compr.</a>';
				
				if($row->formaPago=='Cheque')
				{
					echo '&nbsp;<a>Póliza</a>';
				}
				
				if($row->formaPago!='Cheque')
				{
					echo '&nbsp;<a>Recibo</a>';
				}
				
				echo '
				<a id="a-btnBorrar'.$i.'">Borrar</a>';
			echo'
			</td>
		</tr>';
		
		if($row->cajaChica==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnCajaChica'.$i.'\');
			</script>';
		}
		
		if($permiso[2]->activo==0 or $row->devolucion=='1' or $row->idTraspaso!=0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnEditar'.$i.'\');
			</script>';
		}
		
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnComprobantes'.$i.'\');
			</script>';
		}
		
		if($permiso[3]->activo==0 or $row->idTraspaso!=0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnBorrar'.$i.'\');
			</script>';
		}
		
		
		$i++;
	}
	
	echo '</table>';
}
/*else
{
	 echo'
	 <div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		Sin registros de gastos.
	 </div>';
}*/

echo '
<input type="hidden" id="txtPermisoRegistro" value="'.$permiso[1]->activo.'" />
<input type="hidden" id="txtTipoSeccion" value="2" />';