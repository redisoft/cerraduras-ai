<!--script src="<?php echo base_url()?>js/mostrar.js"></script-->

<?php
#if($ventas!=null)
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagEnvios">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	
	<table class="admintable" width="100%" >
		<tr>
			<th class="encabezadoPrincipal" colspan="4" style="border-right:none" align="right">
				Reporte de envíos
			</th>
			<th style="border-right:none; border-left:none" class="encabezadoPrincipal" colspan="5">
                <img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/pdf.png" width="22" title="PDF" onclick="pdfReporte()" />
                &nbsp;&nbsp;
                <img id="btnExportarExcelReporte" src="<?php echo base_url()?>img/excel.png" width="22" title="Excel" onclick="excelReporte()" />
				
				 &nbsp;&nbsp;
                <img id="btnExportarExcelReporte" src="<?php echo base_url()?>img/printer.png" width="22" title="Ticket" onclick="ticketReporte()" />

				<?php
				if($idPersonal>0)
				{
					#echo '
					#&nbsp;&nbsp;
					#<img id="btnExportarExcelReporte" src="'.base_url().'img/pver.png" width="22" title="Ticket" onclick="formularioEntregas()" />';
				}
				?>
                
				<br />
                <a>PDF</a>
                <a>Excel</a> 
				<a>Ticket</a>
				
                
                 <?php
				if($idPersonal>0)
				{
					#echo ' <a>Entregas</a>';
				}

				if($permiso[1]->activo==0)
				{
					
					 echo '
					<script>
						desactivarBotonSistema(\'btnExportarPdfReporte\');
						desactivarBotonSistema(\'btnExportarExcelReporte\');
					</script>';
				}
				?>           
			</th>
			
			<th class="encabezadoPrincipal" align="right" colspan="4"  style="border-left:none">
				Total: $<?php echo number_format($totalCobranza,2)?>
			</th>
		</tr>
		<tr>
			<th class="" width="3%" align="right">#
			<input type="checkbox" id="chkTodos" name="chkTodos" onchange="checarRegistrosGenerico(<?=count($ventas)?>,'chkTodos','chkVenta')" value="1" />
			<input type="hidden" id="txtNumeroRegistros" name="txtNumeroRegistros" value="<?=count($ventas)?>" />
			</th>
			<th class="">Fecha venta</th>
			<th class="">Fecha entrega</th>
			<th class="" align="center">Cliente</th>
			<th class="" align="center">
				<select class="cajas" id="selectRutas" name="selectRutas" onchange="obtenerReporte()" style="width: 98%; margin-left: 0px">
					<option value="0">Ruta</option>
					
					<?php
					foreach($rutas as $row)
					{
						echo '<option '.($row->idRuta==$idRuta?'selected="selected"':'').' value="'.$row->idRuta.'">'.$row->nombre.'</option>';
					}
					?>
				</select>
			</th>

			<th class="" align="center">
				<select class="cajas" id="selectChofer" name="selectChofer" onchange="obtenerReporte()" style="width: 98%; margin-left: 0px">
					<option value="0">Chofer</option>
					
					<?php
					foreach($personal as $row)
					{
						echo '<option '.($row->idPersonal==$idPersonal?'selected="selected"':'').' value="'.$row->idPersonal.'">'.$row->nombre.'</option>';
					}
					?>
				</select>
			</th>
			<th align="center">Teléfono</th>
			<th class="" align="center">Nota</th>
			<th class="" align="center">Folio</th>
			<th class="" align="center">Factura</th>
			<th class="" align="center">Importe</th>
			<th class="" align="center">Saldo</th>
			<th class="" align="center" width="16%">Acciones</th>
		</tr>
        
    <?php
	    
	$i		= 0;
    $a		= 1;
	$total	= 0;
	foreach($ventas as $row)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		$dias		=0;
		
		if($row->idFactura>0)
		{
			$dias	=$this->reportes->obtenerDiasRestantes($row->fechaVencimiento);
		}

		$dias	=$dias<0?'<label style="color:red">'.($dias*-1).'</label>':$dias;
        
        $productos		= $this->ventas->obtenerProductosVenta($row->idVenta);
		
		?>
		<tr <?php echo $estilo?>>
			<td align="right">
				<?php echo $i+1?>
				<?php
				if($row->idTicket==0)
				{
					echo '<input type="checkbox" id="chkVenta'.$i.'" name="chkVenta'.$i.'" value="'.$row->idVenta.'" />';
				}
				?>
				
			</td>
			<td align="center"><?php echo obtenerFechaMesCortoHora($row->fechaCompra)?></td>
			<td align="center"><?php echo obtenerFechaMesCortoHora($row->fechaEntrega)?></td>
			
			<td align="left"><?php echo $row->empresa.(strlen($row->observaciones)>1?'<br>'.$row->observaciones:'')?></td>
			<td align="left"><?php echo $row->ruta?></td>
			<td align="left"><?php echo $row->personal?></td>
			<td align="left"><?php echo $row->telefono?></td>
			<td align="left">
				<?php 
					echo $row->estacion.$row->folio;
					echo ' <img src="'.base_url().'img/ventas.png" width="22" height="22" title="Ver detalles" onclick="obtenerVentaInformacion('.$row->idVenta.')" />';
				?>
			</td>
			<td align="center"><?php echo $row->folioTicket?></td>
			<td align="center"><?php echo $row->factura?></td>
			<!--<td align="center"><?php echo obtenerFechaMesCorto($row->fechaVencimiento)?></td>
			<td align="center"><?php echo $dias?></td>-->
			<td align="right">$ <?php echo number_format($row->total,2)?></td>
			<td align="right">$ <?php echo number_format($row->saldo,2)?></td>
			<td align="left">
			<?php
				echo '
				&nbsp;&nbsp;&nbsp;
                <img title="Envíos"  id="mostrar'.$i.'" src="'.base_url().'img/pver.png" width="18px" height="18px" style="cursor:pointer" />'.
		        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnTraslado'.$i.'" onclick="formularioTraslado('.$row->idVenta.')" src="'.base_url().'img/cfdi.png" width="20" height="20" title="Traslado"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<img id="btnCobrosCliente'.$i.'" onclick="obtenerPagosClientes('.$row->idVenta.')" src="'.base_url().'img/pagos.png" width="20" height="20" title="Cobros a clientes"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<img src="'.base_url().'img/correo.png" width="20" height="20" title="Enviar correo" onclick="formularioCorreo(\''.$row->ordenCompra.'\',\''.$row->email.'\','.$row->idVenta.');" />';

				if(strlen($row->entregados)==0 and strlen($row->folioTicket)>0 and $permiso[1]->activo==1)
				{
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img id="btnBorrarFolioEntregas'.$i.'" onclick="borrarFolioEntregas('.$row->folioTicket.')"  src="'.base_url().'img/borrar.png" width="20" height="20" title="Borrar folio"/>';
				}
				
				echo'
				<br />
                <a id="a-mostrar'.$i.'">Detalles</a>
				<a id="a-btnTraslado'.$i.'">Traslado</a>
				<a id="a-btnCobrosCliente'.$i.'">Cobros</a>
				<a>Enviar</a>';

				if(strlen($row->entregados)==0 and strlen($row->folioTicket)>0 and $permiso[1]->activo==1)
				{
					echo '&nbsp;<a id="a-btnBorrarFolioEntregas'.$i.'">Borrar folio</a>';
				}
			
           		if($permiso[0]->activo==0)
				{
					echo '
					<script>
						desactivarBotonSistema(\'btnCobrosCliente'.$i.'\');
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
                    <th style="border-radius: 0px; display: none" id="enviandoTodos<?php echo $row->idVenta?>">

                <?php

                if($row->numeroEntregados==0)
                {
                    echo '
                    <img id="btnEntregarTodos'.$i.'" onclick="enviarTodosProductos('.$row->idVenta.')" src="'.base_url().'img/truck.png"  title="Entregar todos" width="25" height="25" style="cursor:pointer;" />
                    <br />
                    Entregar todos';
                }
                else
                {
                    echo 'Entregas';
                }

                ?>

                </th>
            </tr>
            <?php

            if($permiso[1]->activo==0 or $row->cancelada=='1')
            { 
                echo '
                <script>
                    desactivarBotonSistema(\'btnEntregarTodos'.$i.'\');
                </script>';
            }

            foreach($productos as $pro)
            {
                $cantidad		= $pro->cantidad;
                $entregados		= $pro->entregados;


                ?>
                <tr <?php echo $a%2>0?"class='sinSombra'":'class="sombreado"'?>>
                    <td><?php echo $pro->codigoInterno ?></td>
                    <td><?php echo $pro->producto ?></td>
                    <td align="center"><?php echo number_format($cantidad,decimales) ?></td>
                    <td align="center"><?php echo number_format($entregados,decimales) ?></td>
                    <td align="center"><?php echo number_format($cantidad-$entregados,decimales) ?></td>
                    <td align="center" style="display: none"> 
                        <img id="btnEntregarProducto<?php echo $a?>" src="<?php echo base_url().'img/truck.png'; ?>"  onclick="obtenerProductosEntregados('<?php echo $pro->idProducto?>','<?php echo $pro->idProduct?>');"  title="Envios" width="25" height="25" />
                       <br />
                         <?php 
                         if(($cantidad-$entregados)==0)
                         {
                             echo '<a id="a-btnEntregarProducto'.$a.'">Envio completo</a>';
                         }
                         else
                         {
                             echo '<a id="a-btnEntregarProducto'.$a.'">Envio</a>';
                         }
                         ?>
                    </td>
                </tr>
                <?php 

                if($permiso[1]->activo==0 or $row->cancelada=='1')
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

			<script>
				$(document).ready(function()
				{
					$("#mostrar<?=$i?>").click(function(event) 
					{
						event.preventDefault();
						$("#caja<?=$i?>").slideToggle();
					});
	
					
				});
			</script>

            </div>

        </td>
        </tr>

		<?php
		$i++;
	}
	
	?>
    </table>
	
    <?php
	
	echo'
	<div style="width:90%; margin-top:2px;">
		<ul id="pagination-digg" class="ajax-pagEnvios">'.$this->pagination->create_links().'</ul>
	</div>';
}
/*else
{
	echo '<div class="Error_validar">Sin registros</div>';
}*/
?>
