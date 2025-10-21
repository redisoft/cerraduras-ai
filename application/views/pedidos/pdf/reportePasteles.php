
<table class="admintable" width="100%">
    <tr>
        <th colspan="2" class="encabezadoPrincipal">
            Detalles de report
        </th>
    </tr>
    
    <tr>
        <td class="key">Fecha:</td>
        <td>
            <?php echo obtenerFechaMesCorto($pedido->fechaPedido)?>
        </td>
    </tr>
    
    <tr>
        <td class="key">Pedido:</td>
        <td><?php 
        if($pedido->idLinea==2) echo frances;
        if($pedido->idLinea==3) echo bizcocho;
        echo $pedido->folio;?></td>
    </tr>
    
    <tr>
        <td class="key">Línea:</td>
       <td><?php echo $pedido->linea?></td>
    </tr>

    <tr>
        <td class="key">Usuario:</td>
        <td><?php echo $pedido->usuario?></td>
    </tr>
    
    <tr>
        <td class="key">Total:</td>
        <td id="lblTotal">$<?php echo number_format($pedido->totalPeso*($report!=null?$report->costoKg:costoKg),decimales)?></td>
    </tr>
    
    <tr>
        <td class="key">Peso en kg:</td>
        <td><?php echo number_format($pedido->totalPeso,decimales)?></td>
    </tr>
    
    <tr>
        <td class="key">Costo por kg:</td>
        <td>
            $<?php echo $report!=null?number_format($report->costoKg,decimales):costoKg?>
        </td>
    </tr>
    
    <tr>
        <td class="key">Maestro pago por kg:</td>
        <td>
           $<?php echo $report!=null?number_format($report->pagoKg,decimales):0?>
        </td>
    </tr>
    
   <tr>
        <td class="key">Pago maestro:</td>
        <td id="lblMaestro">$<?php echo number_format($report!=null?$report->maestro:0,decimales)?></td>
    </tr>
    
</table>
