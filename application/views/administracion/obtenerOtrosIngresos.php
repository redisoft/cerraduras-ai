<?php
#if($ingresos!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;" align="center">
		<ul id="pagination-digg" class="ajax-pagIng">'.$this->pagination->create_links().'</ul>
	</div>';

	echo'
	<table class="admintable" width="100%">
		<tr>
			<th colspan="13" class="encabezadoPrincipal">
				Lista de ingresos
				<img src="'.base_url().'img/excel.png" width="22" height="22" onclick="excelIngresos()" />
			</th>
		</tr>
		<tr>
			<th># <br />'.($registros).'</th>
			<th>Fecha</th>
			<th>'.(sistemaActivo=='IEXE'?'Cliente/Alumno':'Cliente').'</th>
			<th>
				<select class="cajas" id="selectProductosBusqueda" name="selectProductosBusqueda" style="width:100px" onchange="obtenerOtrosIngresos()">
					<option value="0">Concepto</option>';
					
					foreach($productos as $row)
					{
						echo '<option '.($idProducto==$row->idProducto?'selected="selected"':'').' value="'.$row->idProducto.'">'.$row->nombre.'</option>';
					}
				
				echo'
				</select>
			</th>
			<th>Monto <br /> $'.number_format($totales,decimales).'</th>
			<th>Forma de pago</th>
			<th>Cheque / Trasferencia</th>
			<th>Nombre</th>
			<th>
				<select class="cajas" id="selectDepartamentosBusqueda" name="selectDepartamentosBusqueda" style="width:120px" onchange="obtenerOtrosIngresos()">
					<option value="0">Departamento</option>';
					
					foreach($departamentos as $row)
					{
						echo '<option '.($idDepartamento==$row->idDepartamento?'selected="selected"':'').' value="'.$row->idDepartamento.'">'.$row->nombre.'</option>';
					}
				
				echo'
				</select>
			</th>
			<th>Descripción del producto / servicio</th>
			<th>
				<select class="cajas" id="selectGastosBusqueda" name="selectGastosBusqueda" style="width:100px" onchange="obtenerOtrosIngresos()">
					<option value="0">Tipo</option>';
					
					foreach($gastos as $row)
					{
						echo '<option '.($idGasto==$row->idGasto?'selected="selected"':'').' value="'.$row->idGasto.'">'.$row->nombre.'</option>';
					}
				
				echo'
				</select>
			</th>
			<th>Comentarios</th>
			<th width="19%">Acciones</th>
		</tr>';
	
	$i=$inicio;
	
	foreach($ingresos as $row)
	{
		$estilo		= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$factura	= $this->facturacion->obtenerFacturaCancelar($row->idFactura);
		$cuenta		= $this->administracion->obtenerCuentaBancoIngreso($row->idCuenta);
		
		echo'
		<tr '.$estilo.'>
			<td>'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fecha).'</td>
			<td>';
				$cliente	= $row->cliente;
				
				if(sistemaActivo=='IEXE')
				{
					$cliente	= strlen($row->alumno)>1?$row->alumno:$row->cliente;
				}
				
				echo $cliente;
				
			echo'</td>
			<td>';
				$producto	=$this->configuracion->obtenerProducto($row->idProducto);
				echo $producto!=null?$producto->nombre:'';
				
				if(sistemaActivo=='IEXE')
				{
					echo strlen($row->campana)>0?'<br />Campaña: '.$row->campana:'';
				}
				
			echo'</td>
			<td align="right">$'.number_format($row->pago,2).' '.($row->idFactura>0?'<br /><a tarjet="_blanck" title="Ver en PDF" href="'.base_url().'pdf/crearFactura/'.$row->idFactura.'" >CFDI: '.$factura->cfdi.'</a>':'').'</td>
			<td>'.$row->formaPago; 
			
			if($cuenta!=null)
			{
				echo '<br />';
				echo strlen($cuenta->cuenta)>0?$cuenta->cuenta:$cuenta->tarjetaCredito;
				echo '<br />'.$cuenta->banco;
			}
			
			echo'</td>
			<td align="center">'.$row->cheque.$row->transferencia.'</td>
			<td>';
			$nombre	=$this->administracion->obtenerNombre($row->idNombre);
			echo $nombre!=null?$nombre->nombre:'';
			echo'</td>
			<td>';
				$departamento	=$this->configuracion->obtenerDepartamento($row->idDepartamento);
				echo $departamento!=null?$departamento->nombre:'';
			echo'</td>
			<td>'.(strlen($row->productoCatalogo)>1?$row->productoCatalogo:$row->producto).'</td>
			<td>';
				$gasto	=$this->configuracion->obtenerGasto($row->idGasto);
				echo $gasto!=null?$gasto->nombre:'';
			echo'</td>
			<td>'.nl2br($row->comentarios).'</td>
			<td align="left">';
				
				if(sistemaActivo=='IEXE')
				{
					echo'
					&nbsp;
					<img id="btnFacturar'.$i.'" src="'.base_url().'img/cfdi.png" width="22" height="22" onclick="formularioFacturaIngreso('.$row->idIngreso.')" title="Facturar" />';
					
					if($factura!=null)
					{
						if($factura->cancelada=='0')
						{
							echo '
							&nbsp;&nbsp;&nbsp;
							<img id="btnEnviarFactura'.$i.'"  src="'.base_url().'img/correo.png" title="Enviar CFDI" width="25" style="cursor:pointer" onclick="formularioCorreoFactura('.$row->idFactura.')"/>';
						}
					}
				}
				
				echo'
				&nbsp;&nbsp;&nbsp;
				<img id="btnEditar'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" onclick="acccesoEditarIngreso('.$row->idIngreso.')" />
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnComprobantes'.$i.'" src="'.base_url().'img/subir.png" width="22"  onclick="obtenerComprobantes('.$row->idIngreso.')"  title="Comprobantes" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnBorrar'.$i.'" src="'.base_url().'img/borrar.png" width="22" height="22" onclick="accesoBorrarIngreso('.$row->idIngreso.')" />
				
				<br />';
				
				if(sistemaActivo=='IEXE')
				{
					echo'
					<a id="a-btnFacturar'.$i.'">Factura</a>';
					
					if($factura!=null)
					{
						if($factura->cancelada=='0')
						{
							echo '&nbsp;<a id="a-btnEnviarFactura'.$i.'">Enviar</a>';
						}
					}
				}
				
				
				echo'
				<a id="a-btnEditar'.$i.'">Editar</a>
				<a id="a-btnComprobantes'.$i.'">Comprobantes</a>
				<a id="a-btnBorrar'.$i.'">Borrar</a>
			</td>
		</tr>';
		
		#if($permiso[1]->activo==0 or $row->idTraspaso!=0 or $row->idFactura!=0 or $row->idVenta!=0)
		if($permiso[1]->activo==0 or $row->idTraspaso!=0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnFacturar'.$i.'\');
			</script>';
		}
		
		if($permiso[2]->activo==0 or $row->idTraspaso!=0)
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
		
		if($permiso[3]->activo==0 or $row->idTraspaso!=0 or $row->idFactura>0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnBorrar'.$i.'\');
			</script>';
		}
		
		if($factura!=null)
		{
			if($factura->cancelada=='0')
			{
				echo '
				<script>
					
					desactivarBotonSistema(\'btnFacturar'.$i.'\');
				</script>';
			}
		}

		$i++;
	}
	
	echo '</table>';
}
/*else
{
	 echo'
	 <div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
		Sin registros de otros ingresos.
	 </div>';
}*/

echo '
<input type="hidden" id="txtPermisoRegistro" value="'.$permiso[1]->activo.'" />
<input type="hidden" id="txtTipoSeccion" value="1" />';