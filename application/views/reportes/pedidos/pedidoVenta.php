
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
                        <td align="center" class="bordes sombreadoFila">NOTA DE VENTA</td>
                    </tr>
                    <tr>
                        <td align="center" class="bordes"><?php echo $venta->folio?></td>
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
            <td><?php echo $venta->usuario?></td>
        </tr>
        
        <tr>
            <td class="totales">Sucursal</td>
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
				
				echo '
				<tr>
					<td align="center" class="bordeIzquierda">'.number_format($row->cantidad,decimales).'</td>
					<td>'.$row->producto.'</td>
					<td align="center"></td>
					<td align="right" class="bordeIzquierda bordeDerecha">$'.number_format($row->precio,decimales).'</td>
					<td align="right" class="bordeDerecha">$'.number_format($row->importe,decimales).'</td>
				</tr>';
				
            }
            ?>
        </tr>

        <tr>
        	<td colspan="3" class="bordeArriba" ></td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo bordeArriba totales">Total</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo bordeArriba" align="right">$<?php echo number_format($venta->total,decimales)?></td>
        </tr>
        
        <tr>
        	<td colspan="3"></td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo totales">I.V.A.</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($venta->iva,decimales)?></td>
        </tr>
        
        <tr>
        	<td colspan="3"></td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo totales">Pagado</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($venta->pagado,decimales)?></td>
        </tr>
        
        <tr>
        	<td colspan="3"></td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo totales">Resta</td>
            <td class="bordeIzquierda bordeDerecha bordeAbajo" align="right">$<?php echo number_format($venta->total-$venta->pagado,decimales)?></td>
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
            <td align="center">Fecha: <?php echo obtenerFechaMesLargo($venta->fechaEntrega,0);?></td>
            <td align="center">_____________________________________</td>
        </tr>
    </table>
    
    <br />
	
    NOTA: Los pasteles de 3 leches necesitan refrigeración. En cancelaciones se cobrara el 10% del total.
	
    
</div>


