
<?php

$ordenes	= '';
$i			= 0;
foreach($ordenesVenta as $row)
{
	$ordenes.=$i==0?$row->ordenCompra:', '.$row->ordenCompra;
	$i++;
}

$concepto	= $tipo=='Fechas'?'Periodo: De '.obtenerFechaMesCorto($inicio).' al '.obtenerFechaMesCorto($fin).'
Ventas: '.$ordenes:'Ventas: '.$ordenes;

echo'
<table class="admintable" style="width:100%">
    <tr style="display:none">
        <td class="key">Concepto:</td>
        <td>
			<textarea class="TextArea" id="txtConceptoGlobal" name="txtConceptoGlobal">'.$concepto.'</textarea>
			
			<input type="hidden" id="txtTotalesFacturaGlobal" name="txtTotalesFacturaGlobal" value="'.$totales->total.'"  />
		</td>
    </tr>
    
    <tr>
        <td class="key">Subtotal:</td>
        <td id="lblSubTotal">'.number_format($totales->subTotal,decimales).'</td>
    </tr>
    
     <tr>
        <td class="key">Descuento</td>
        <td>$'.number_format($totales->descuento,decimales).'</td>
    </tr>
	
    <tr>
        <td class="key" >Impuestos:</td>
        <td id="lblIva">$'.number_format($totales->iva,decimales).'</td>
    </tr>
    <tr>
        <td class="key">Total:</td>
        <td id="lblTotal">$'.number_format($totales->total,2).'</td>
    </tr>
</table>';
?>