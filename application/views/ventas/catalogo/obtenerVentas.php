<script src="<?php echo base_url()?>js/mostrar.js"></script>

<?php

/*if(!empty ($ventas))
{*/
	$sql="";
	
	/*echo'
	<div style="width:90%; margin-top:2%;">
		<ul id="pagination-digg" class="ajax-pagVentas">'.$this->pagination->create_links().'</ul>
	</div>';
	
	if($seccion=='ventas')
	{
		echo '
		<ul class="menuTabs">
			<li class="activado">Ventas</li>
			<li onclick="window.location.href=\''.base_url().'ventas/ventasProducto\'">Ventas por producto</li>
			<li onclick="window.location.href=\''.base_url().'ventas/ventasServicio\'">Ventas por servicio</li>
    	</ul>';
	}*/
	?>

	<table class="admintable" width="100%" >
    
    	<?php
		
		#if($registros>25)
        {
			echo'
			<tr>
                <td colspan="12" class="sinbordeTransparente">
					<div style="width:90%; margin-top:2%;">
						<ul id="pagination-digg" class="ajax-pagVentas">'.$this->pagination->create_links().'</ul>
					</div>
				</td>
			</td>';
		}
		
       /* if($seccion=='ventas')
        {
            echo '
            <tr>
                <td colspan="12" class="sinbordeTransparente">
                    <ul class="menuTabs">
                        <li class="activado sinMargen">Ventas</li>
                        <li class="sinMargen" onclick="window.location.href=\''.base_url().'ventas/ventasProducto\'">Ventas por producto</li>
                        <li class="sinMargen" onclick="window.location.href=\''.base_url().'ventas/ventasServicio\'">Ventas por servicio</li>
                    </ul>
                </td>
            </tr>';
        }*/
        ?>
        
	 	<tr>
			<th align="center" valign="middle" class="encabezadoPrincipal">#</th>
            
            	<?php
				if($seccion=='ventas')
				{
					echo '
					<th align="center" valign="middle" class="encabezadoPrincipal">
					
						<select class="cajas" id="selectClientesBusqueda" name="selectClientesBusqueda" style="width:120px" onchange="definirIdCliente()">
							<option value="0">Cliente</option>';
							foreach($clientes as $row)
							{
								echo '<option '.($row->idCliente==$idCliente?'selected="selected"':'').' value="'.$row->idCliente.'">'.$row->cliente.'</option>';
							}
						
					echo '
						</select>
					</th>';
				}
				
				
				?>
            
			<th align="center" valign="middle" class="encabezadoPrincipal">
				Nota
            	<?php
				echo '
				<select class="cajas" id="selectVentasBusqueda" style="width:70px; display: none" onchange="obtenerVentas()">
					<option value="0">Nota</option>';
					
					
				echo '</select>';
				
				echo '<img onclick="definirOrdenVentas('.($ordenVentas=='asc'?'\'desc\'':'\'asc\'').')" src="'.base_url().'img/'.($ordenVentas=='asc'?'ocultar':'mostrar').'.png" width="17" />';
				?>
            </th>
			<th align="center" valign="middle" class="encabezadoPrincipal">
            	Fecha
                
                <?php
				#
		  		?>
            </th>
            <th align="center" valign="middle" class="encabezadoPrincipal" <?=$permiso[6]->activo==0?'style="display: none"':''?>><?=$permiso[8]->activo=='1'?'$'.number_format($totales->ventas,decimales):'Total'?></th>
            <th align="center" valign="middle" class="encabezadoPrincipal">
				
            	<?php
				echo '
				<select class="cajas" id="selectFacturasBusqueda" name="selectFacturasBusqueda" style="width:100px;" onchange="obtenerVentas()">
					<option value="0">Documento</option>
					<option '.($idFactura=='1'?'selected="selected"':'').' value="1">Remisión</option>
					<option '.($idFactura=='2'?'selected="selected"':'').' value="2">Factura</option>
					<option '.($idFactura=='3'?'selected="selected"':'').' value="3">PREFACTURA</option>
					<option '.($idFactura=='4'?'selected="selected"':'').' value="4">Cancelada</option>';
					
					/*foreach($facturas as $row)
					{
						echo '<option '.($row->idFactura==$idFactura?'selected="selected"':'').' value="'.$row->idFactura.'">'.$row->factura.'</option>';
					}*/
				
				echo '</select>';
					
				?>
            </th>
			
			<th align="center" valign="middle" class="encabezadoPrincipal">
            	<?php
				echo '
				<select class="cajas" id="selectEstaciones" name="selectEstaciones" style="width:80px" onchange="obtenerVentas()">
					<option value="0">Estación</option>';
					
					foreach($estaciones as $row)
					{
						echo '<option '.($row->idEstacion==$idEstacion?'selected="selected"':'').' value="'.$row->idEstacion.'">'.$row->nombre.'</option>';
					}
				
				echo '</select>';
					
				?>
            </th>
			
			<th align="center" valign="middle" class="encabezadoPrincipal">Condición</th>
			
            <!--<th align="center" valign="middle" class="encabezadoPrincipal">Remisión <br /> $<?=$permiso[6]=='1'?number_format($totales->ventas-$totales->parciales,decimales):''?></th>-->
			
			<th align="center" valign="middle" class="encabezadoPrincipal" <?=$permiso[6]->activo==0?'style="display: none"':''?>>
				Saldo<?=$permiso[8]->activo=='1'?'<br><input type="checkbox" id="chkSaldo" name="chkSaldo" value="1" onchange="obtenerVentas()" '.($saldo=='1'?'checked="checked"':'').' /><br>$'.number_format($totales->ventas-$totales->pagado,decimales):'<br><input type="checkbox" id="chkSaldo" name="chkSaldo" '.($saldo=='1'?'checked="checked"':'').' value="1" onchange="obtenerVentas()"  />'?></th>
			
			<?php
			$totalesPermiso=true;
			
			if($permiso[6]->activo==0 or $permiso[8]->activo==0)
			{
				$totalesPermiso=false;
			}
			
			?>
			<th align="center" valign="middle" class="encabezadoPrincipal" <?=!$totalesPermiso?'style="display: none"':''?>>Total<?=$permiso[6]->activo=='1'?'<br>$'.number_format($totales->ventas,decimales):''?></th>
			
            <!--<th align="center" valign="middle" class="encabezadoPrincipal">Devolución</th>-->
			<th width="39%" align="center" valign="middle" class="encabezadoPrincipal">Acciones</th>
		</tr>
	
	<?php
	$i=1;
	$a=1;
	$c=$limite;
	foreach ($ventas as $venta)
	{
		$estilo			= $i%2>0?'class="sinSombra"':'class="sombreado"';
		$pago			= $this->clientes->sumarPagado($venta->idCotizacion);
		$cancelada		= $venta->cancelada;
		$informacion 	= 'onclick="obtenerVentaInformacion('.$venta->idCotizacion.')"';
		$productos		= $this->ventas->obtenerProductosVenta($venta->idCotizacion);
		
		$duplicada		= $this->ventasmodelo->revisarVentaDuplicada($venta,$productos);
		
		?>
		
		<tr <?php echo $estilo?> id="filaVenta<?php echo $venta->idCotizacion?>">
	 	<td align="center" <?php echo $informacion?>> <?php print($c); ?> </td>
			<?php
            if($seccion=='ventas')
            {
                echo '<td align="left" '.$informacion.'>'.$venta->cliente.(!$duplicada?'<i style="color: red"> (Venta duplicada)</i>':'').'</td>';
            }
            ?>
         
		 <td align="center" <?php echo $informacion?>> 
		 <?php 
		 	echo $venta->folio; 
			if($cancelada==1)
			echo ' (Venta cancelada)';
			echo $venta->idTienda>0?'('.$venta->tienda.')':'';
		 ?> </td>
			 <td align="center"  <?php echo $informacion?>><?php echo obtenerFechaMesCortoHora($venta->fechaCompra); ?> </td>
         
         
         <td align="center" <?php echo $informacion?> <?=$permiso[6]->activo==0?'style="display: none"':''?>>
		 <?php 
			 $parciales	=$this->reportes->sumarFacturasParciales($venta->idCotizacion);
					
			#echo '$'.number_format($parciales,2);
			 echo '$'.number_format($venta->total,2);
		 ?> 
         </td>
         
         <td align="center" <?php echo $informacion?>>
         	<?php
            	$folios			= $this->reportes->obtenerFoliosParciales($venta->idCotizacion);
				$pendientes		= $this->reportes->obtenerFoliosPendientes($venta->idCotizacion);
				$f			=1;
				
				
				foreach($folios as $fo)
				{
					if($f==1){echo $fo->pendiente==0?$fo->folio:'<i style="color: red">PREFACTURA</i>';}
					else{echo ', '.($fo->pendiente==0?$fo->folio:'<i style="color: red">PREFACTURA</i>');}
					
					$f++;
				}
		
				$f			=1;
				
				foreach($pendientes as $fo)
				{
					if($f==1){echo $fo->pendiente==0?$fo->folio:'<i style="color: red">PREFACTURA</i>';}
					else{echo ', '.($fo->pendiente==0?$fo->folio:'<i style="color: red">PREFACTURA</i>');}
					
					$f++;
				}
		
				if($folios==null and $pendientes==null)
				{
					echo $venta->prefactura=='1'?'<i style="color: red">PREFACTURA</i>':'';
				}
			?>
         </td>
			
		<td align="center" <?php echo $informacion?>><?php echo $venta->estacion ?> </td>
         
         <!--<td align="center" <?php echo $informacion?>>
		 <?php 
			 echo  '$'. number_format($venta->total-$parciales,2);
		 ?> 
         </td>-->
			
		<!-- <td align="center" <?php echo $informacion?>><?php echo obtenerCondicionPago(strlen($venta->formaPagoIngreso)>0?$venta->formaPagoIngreso:$venta->formaPagoVenta); ?> </td>-->
		 <td align="center" <?php echo $informacion?>><?php echo $venta->idForma==7?'CRÉDITO':'CONTADO' ?> </td>
         
		 <td align="center" <?php echo $informacion?> <?=$permiso[6]->activo==0?'style="display: none"':''?>><?php echo "$".number_format(($venta->cancelada=='1'?0:$venta->total-$pago),2); ?> </td>
		 <td align="center" <?php echo $informacion?> <?=!$totalesPermiso?'style="display: none"':''?>><?php echo "$".number_format($venta->total,2); ?> </td>
		 
         
        
         
		 <td align="left" valign="middle"> 
		 <?php 
		 
		$facturas	= $this->clientes->comprobarCotizacionFactura($venta->idCotizacion);
		
		$link	= base_url().'pdf/nuevaVenta/'.$venta->idCotizacion.'/'.$this->session->userdata('idLicencia');
		
		if(sistemaActivo=='olyess')
		{
			if($venta->pedido>0)
			{
				$link	= base_url().'reportes/pedidosReporte/'.$venta->idCotizacion;
			}
			else
			{
				$link	= base_url().'reportes/pedidoVenta/'.$venta->idCotizacion;
			}
		}

		echo'
		
		<!--<a title="Imprimir remisión" href="'.$link.'" target="_black">
			<img src="'.base_url().'img/pdf.png" width="22px" height="22px" border="0"/>
		</a>-->
		&nbsp;&nbsp;
		<a id="btnTicket'.$i.'" title="Imprimir ticket" href="'.base_url().'clientes/imprimirTicket/'.$venta->idCotizacion.'/1" target="_black">
			<img src="'.base_url().'img/printer.png" width="22px" height="22px" border="0"/>
		</a>
		&nbsp;
		<a id="btnTicketPdf'.$i.'" title="Imprimir ticket" href="'.base_url().'clientes/imprimirTicketPdf/'.$venta->idCotizacion.'" target="_black">
			<img src="'.base_url().'img/pdf.png" width="22px" height="22px" border="0"/>
		</a>
		
		&nbsp;&nbsp;
		
		<img id="btnPagosCliente'.$i.'" onclick="obtenerPagosClientes('.$venta->idCotizacion.')" src="'.base_url().'img/pagos.png" width="20" height="20" title="Cobros a clientes" style="cursor:pointer;"/>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<img title="Mostrar productos"  id="mostrar'.$i.'" src="'.base_url().'img/pver.png" width="18px" height="18px" style="cursor:pointer" />'.
		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<img id="btnCorreo'.$i.'" src="'.base_url().'img/correo.png" width="20" height="20" title="Enviar correo" onclick="formularioCorreo(\''.$venta->ordenCompra.'\',\'redisoft\','.$venta->idCotizacion.');" style="cursor:pointer;"/>
		&nbsp;&nbsp;';
		
		if($venta->idSucursal>0)
		{
			echo '
			<img id="btnTraslado'.$i.'" title="Traslado" src="'.base_url().'img/cfdi.png" style="width:20px; cursor:pointer" onclick="formularioTraslado('.$venta->idCotizacion.')" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		
		if($venta->prefactura=='1')
		{
			echo '
			<img id="btnFacturacion'.$i.'" title="Facturación" src="'.base_url().'img/pdf.png" style="width:20px; cursor:pointer" onclick="accesoOpcionFactura(\'ventas\','.$venta->idCotizacion.')" class="escalaGrisess" />
			&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		
		
		echo'<img id="btnDevoluciones'.$i.'" title="Devoluciones" src="'.base_url().'img/devolucion.png" style="width:22px; cursor:pointer" onclick="obtenerDevoluciones('.$venta->idCotizacion.')" />';
		
		
		if($venta->prefactura=='1' and  $venta->numeroFacturas==0 and $venta->cancelada=='0')
		{
			echo '
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src="'.base_url().'img/reporteVentas.png" width="22px" height="22px" title="Convertir a remisión" onclick="convertirPreFactura('.$venta->idCotizacion.')" />';
		}
		
		echo'
		&nbsp;&nbsp;
		<a id="btnCancelarVenta'.$i.'" onclick="accesoCancelarVenta('.$venta->idCotizacion.',\'Cancelar la venta '.$venta->ordenCompra.' tambien borrara sus cobros, ¿Desea continuar?\')" title="Cancelar orden de venta" > 
			<img src="'.base_url().'img/cancelame.png" width="22px" height="22px" />
		</a>
		
		
		
		&nbsp;
		<a id="btnBorrarVenta'.$i.'" onclick="accesoBorrarVenta('.$venta->idCotizacion.',\'Borrar la venta '.$venta->ordenCompra.' tambien borrara sus cobros, ¿Desea continuar?\')" style="cursor:pointer;" title="Borrar orden de venta" > 
			<img src="'.base_url().'img/borrar.png" width="22px" height="22px"/>
		</a>';
		
		echo '&nbsp;&nbsp;&nbsp;&nbsp;<img id="btnEditar'.$i.'" src="'.base_url().'img/editar.png" width="22px" height="22px" onclick="obtenerVentaEditar('.$venta->idCotizacion.')"/>';
		
		echo '';
		echo'
		<br />
		
		<!--<a>Venta</a>-->
		<a>Ticket</a>
		<a>Ticket</a>
		<a id="a-btnPagosCliente'.$i.'">Cobros</a>
		<a id="a-mostrar'.$i.'">Entregar</a>
		<a id="a-btnCorreo'.$i.'">Enviar</a>';
		
		if($venta->idSucursal>0)
		{
			echo '
			<a id="a-btnTraslado'.$i.'">Traslado</a>';
		}
		
		if($venta->prefactura=='1')
		{
			echo '
			<a id="a-btnFacturacion'.$i.'">Facturar</a>';
		}
		
		echo'
		<a id="a-btnDevoluciones'.$i.'">Devol.</a>';
		
		if($venta->prefactura=='1' and $venta->numeroFacturas==0 and $venta->cancelada=='0')
		{
			echo '
			<a id="a-btnDevoluciones'.$i.'">Remisión</a>';
		}
		
		
		echo'
		<a id="a-btnCancelarVenta'.$i.'">Canc.</a>
		<a id="a-btnBorrarVenta'.$i.'">Borrar</a>';
		
		echo ' <a id="a-btnEditar'.$i.'">Editar</a>';
		
		if($venta->numeroFacturas>0 or $venta->cancelada=='1' or $permiso[7]->activo=='0' or $venta->idSucursal>0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnEditar'.$i.'\');
			</script>';
		}
		
		if(sistemaActivo=='olyess')
		{
			
			
			if($venta->acrilicoDevuelto>0 or $venta->acrilico==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnAcrilico'.$i.'\');
				</script>';
			}
		}

		if($permiso[5]->activo==0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnTicket'.$i.'\');
				desactivarBotonSistema(\'btnTicketPdf'.$i.'\');
			</script>';
		}
		
		if($permiso[2]->activo==0 or $venta->cancelada=='1')
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnPagosCliente'.$i.'\');
				desactivarBotonSistema(\'mostrar'.$i.'\');
				desactivarBotonSistema(\'btnFacturacion'.$i.'\');
				desactivarBotonSistema(\'btnCorreo'.$i.'\');
				desactivarBotonSistema(\'btnDevoluciones'.$i.'\');
			</script>';
		}
		
		if($permiso[1]->activo==0 or $venta->numeroEntregados==0 or $venta->cancelada=='1' or $venta->numeroFacturas>0 or $venta->idCotizacion>0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnDevoluciones'.$i.'\');
			</script>';
		}
		
		if($permiso[3]->activo==0 or $venta->cancelada=='1')
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnCancelarVenta'.$i.'\');
			</script>';
		}
		
		if($permiso[3]->activo==0 or $facturas>0 or $venta->cancelada=='1' or $venta->idFactura>0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnBorrarVenta'.$i.'\');
			</script>';
		}

		  ?>
		 </td>
		 </tr>
			 <tr>
				<td colspan="12" style="background:#FFF; border:none">
				<div id="caja<?php echo $i?>" style="display:none; width:100%">
				<table class="admintable" width="100%">
					<tr>
						<th style="border-radius: 0px">Código</th>
						<th style="border-radius: 0px">Producto</th>
						<th style="border-radius: 0px" align="center">Cantidad</th>
						<th style="border-radius: 0px" align="center">Entregado</th>
						<th style="border-radius: 0px" align="center">Restante</th>
						<th style="border-radius: 0px" id="enviandoTodos<?php echo $venta->idCotizacion?>">
					
					<?php
                    
					if($venta->numeroEntregados==0)
					{
						if($venta->envio=='0'):
							echo '
							<img id="btnEntregarTodos'.$i.'" onclick="enviarTodosProductos('.$venta->idCotizacion.')" src="'.base_url().'img/truck.png"  title="Entregar todos" width="25" height="25" style="cursor:pointer;" />
							<br />
							Entregar todos';
							else: echo 'Entregas';
						endif;
					}
					else
					{
						echo 'Entregas';
					}
					
					?>
				
					</th>
				</tr>
				<?php
				
				if($permiso[1]->activo==0 or $venta->cancelada=='1')
				{ 
					echo '
					<script>
						desactivarBotonSistema(\'btnEntregarTodos'.$i.'\');
					</script>';
				}
				
				foreach($productos as $row)
				{
					$cantidad		= $row->cantidad;
					$entregados		= $row->entregados;
					
					if(sistemaActivo=='olyess')
					{
						if($row->rebanadas>0)
						{
							$cantidad		= $row->cantidad;
							$entregados		= $row->entregados*$row->rebanadasPastel;
						}
					}
					
					?>
					<tr <?php echo $a%2>0?"class='sinSombra'":'class="sombreado"'?>>
                        <td><?php echo $row->codigoInterno ?></td>
                        <td><?php echo $row->producto ?></td>
                        <td align="center"><?php echo number_format($cantidad,decimales) ?></td>
                        <td align="center"><?php echo number_format($entregados,decimales) ?></td>
                        <td align="center"><?php echo number_format($cantidad-$entregados,decimales) ?></td>
                        <td align="center"> 
                            
							<?php 
							if($venta->envio=='0'):
								echo '
								<img id="btnEntregarProducto'.$a.'" src="'.base_url().'img/truck.png"  onclick="obtenerProductosEntregados('.$row->idProducto.',',$row->idProduct.');"  title="Envios" width="25" height="25" />
								<br />';
								 if(($cantidad-$entregados)==0)
								 {
									 echo '<a id="a-btnEntregarProducto'.$a.'">Envio completo</a>';
								 }
								 else
								 {
									 echo '<a id="a-btnEntregarProducto'.$a.'">Envio</a>';
								 }
							 endif;
                             ?>
                        </td>
					</tr>
					<?php 
					
					if($permiso[1]->activo==0 or $venta->cancelada=='1')
					{ 
						echo '
						<script>
							desactivarBotonSistema(\'btnEntregarProducto'.$a.'\');
						</script>';
					}
					
					$a++;
				}
				?>
				</table>
				</div>
			</td>
			</tr>
		
		<?php
		
		$i++;
		$c++;
	}
	
	?>
	
	</table>

	<?php
	
	echo'
	<div style="width:90%; ">
		<ul id="pagination-digg" class="ajax-pagVentas">'.$this->pagination->create_links().'</ul>
	</div>';
/*}
else
{
	echo 
	'<div class="Error_validar" style="margin-top:2%; width:67%; margin-left:2px; margin-bottom: 5px;">
		No hay registro de ventas.
	</div>';
}*/
?>
