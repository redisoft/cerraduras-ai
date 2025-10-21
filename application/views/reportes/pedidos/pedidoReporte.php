
<div style="width:100%">
	<div align="center">
    	<?php
        if(strlen($empresa->logotipo)>0 and file_exists('img/logos/'.$empresa->id.'_'.$empresa->logotipo))
		{
			echo '<img src="'.base_url().'img/logos/'.$empresa->id.'_'.$empresa->logotipo.'" style="max-width:200px; max-height:120px;" />';
		}
		?>
    </div>
    
    
    <table class="tablaPedidos">
        <tr>
            <td width="80%">
            <?php
            echo ($tienda!=null?$tienda->calle:$empresa->direccion).' '.($tienda!=null?$tienda->numero:$empresa->numero).' ';
            echo 'Col.'.($tienda!=null?$tienda->colonia:$empresa->colonia).' C.P.'.($tienda!=null?$tienda->codigoPostal:$empresa->codigoPostal).' ';
            echo ($tienda!=null?$tienda->municipio:$empresa->municipio).', '.($tienda!=null?$tienda->estado:$empresa->estado).'';

            ?>
            
            Tel. <?php echo ($tienda!=null?$tienda->telefono:$empresa->telefono)?>
           
            </td>
            <td>
                <table class="tablaPedidos">
                    <tr>
                        <td align="center" class="bordes sombreadoFila">PEDIDO</td>
                    </tr>
                    <tr>
                        <td align="center" class="bordes"><?php echo $pedido->folio?></td>
                    </tr>
                    
                    <tr>
                        <td align="center">a <?php echo obtenerFechaMesLargo(date('Y-m-d'),0)?></td>
                    </tr>
                </table>
                
            </td>
        </tr>
     </table>
         
         
     <table class="tablaPedidos">
        <tr>
            <td width="15%" class="totales">Nombre</td>
            <td><?php echo $cliente->empresa?></td>
        </tr>
        <tr>
            <td class="totales">Teléfono</td>
            <td><?php echo $cliente->telefono.' '.$cliente->email?></td>
        </tr>
        
        <tr>
            <td class="totales">Dirección</td>
            <td><?php echo $cliente->calle.' '.$cliente->numero.' '.$cliente->colonia.' '.$cliente->localidad.' '.$cliente->municipio?></td>
        </tr>
        
        <tr>
            <td class="totales">Cajero</td>
            <td><?php echo $pedido->usuario?></td>
        </tr>
        
        <tr>
            <td class="totales">Sucursal</td>
            <!--<td>Almacen de producción</td>-->
            <td><?php echo $empresa->nombre?></td>
        </tr>
     </table>
     
     <table class="tablaPedidos">
        <tr>
            <th width="25%" class="bordes">CANT</th>
            <th width="25%" class="bordes">DESCRIPCIÓN</th>
            <th width="25%" class="bordes">TAMAÑO</th>
            <th width="12.5%" class="bordes">P.U.</th>
            <th width="12.5%" class="bordes">IMPORTE</th>
            
            <?php
            foreach($productos as $row)
            {
				if($row->idLinea!=15)
				{
					echo '
					<tr>
						<td align="center" class="bordeIzquierda">'.number_format($row->cantidad,decimales).'</td>
						<td>'.$row->producto.'</td>
						<td align="center">'.($row->idLinea!=15?number_format($pedido->peso,decimales).' kg':'').'</td>
						<td align="right" class="bordeIzquierda bordeDerecha">$'.number_format($row->precio,decimales).'</td>
						<td align="right" class="bordeDerecha">$'.number_format($row->importe,decimales).'</td>
					</tr>';
				}
                
            }
            ?>
        </tr>
        
        
        <tr>
            <td class="bordeIzquierda"><strong>Cobertura</strong> <?php echo $pedido->cobertura?></td>
            <td colspan="2"><strong>Sabor </strong><?php echo $pedido->cobertura?></td>
            <td class="bordeIzquierda bordeDerecha"></td>
            <td class="bordeIzquierda bordeDerecha"></td>
        </tr>
        
         <tr>
            <td class="bordeIzquierda"><strong>Forma</strong> <?php echo $pedido->forma?></td>
            <td colspan="2"><strong>Relleno</strong> <?php echo $pedido->relleno?></td>
            <td class="bordeIzquierda bordeDerecha"></td>
            <td class="bordeIzquierda bordeDerecha"></td>
        </tr>
        
         <tr>
            <td class="bordeIzquierda bordeAbajo" colspan="3"><strong>Decoración</strong> <?php echo nl2br($pedido->decoracion)?></td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo"></td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo"></td>
        </tr>
        
        <tr>
            <td class="bordeIzquierda bordeAbajo" colspan="3" rowspan="6" valign="top">
			<?php 
				echo '<strong>'.($pedido->idDireccion>0?'Servicio a domicilio':'').'</strong><br />
				
				<strong>Dirección: </strong> '.($pedido->idDireccion==0?'Recoleccion en susursal':$direccion->calle.' '.$direccion->numero.' 
				'.$direccion->colonia.' '.$direccion->ciudad.' '.$direccion->estado.' '.$direccion->codigoPostal.' '.(strlen($direccion->referencia)>0?' Refencia: '.$direccion->referencia:'')).'<br />'.
				'<strong>'.$cantidadLetra.'</strong>';
				
				if($pedido->especial=='1')
				{
					#echo '<br />'.nl2br($pedido->descripcion);
				}
			?>
            </td>
            <td class="totales bordeIzquierda bordeDerecha bordeAbajo ">Depósito</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($pedido->acrilico,decimales)?></td>
        </tr>
        
        <tr>
            <td class="bordeIzquierda bordeDerecha bordeAbajo totales">Domicilio</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($domicilio,decimales)?></td>
        </tr>
        
        <tr>
            <td class="bordeIzquierda bordeDerecha bordeAbajo totales">Total</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($pedido->total+$pedido->acrilico,decimales)?></td>
        </tr>
        
        <tr>
            <td class="bordeIzquierda bordeDerecha bordeAbajo totales">I.V.A.</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($pedido->iva,decimales)?></td>
        </tr>
        
        <tr>
            <td class="bordeIzquierda bordeDerecha bordeAbajo totales">Anticipo</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($pedido->pagado,decimales)?></td>
        </tr>
        
        <tr>
            <td class="bordeIzquierda bordeDerecha bordeAbajo totales">Resta</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($pedido->total-$pedido->pagado+$pedido->acrilico,decimales)?></td>
        </tr>
        
     </table>

	<br />
    <table class="tablaPedidos">
        <tr>
            <th width="50%">ENTREGA</th>
            <th align="center" width="50%">
                Firma
            </th>
        </tr>
        
        <tr>
            <td align="center">Fecha: <?php echo obtenerFechaMesLargo($pedido->fechaEntrega,0);?></td>
            <td align="center">_____________________________________</td>
        </tr>
    </table>
    
    <br />
	
    NOTA: Los pasteles de 3 leches necesitan refrigeración. En cancelaciones se cobrara el 10% del total.
	
    
</div>


