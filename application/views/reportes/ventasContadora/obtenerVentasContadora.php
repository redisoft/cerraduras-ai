<?php
$cantidad	= 0;
$importe	= 0;

foreach($ventas as $row)
{
	$cantidad	+=$row->cantidad;
	$importe	+=$row->importe;
}
?>
<table class="admintable" width="100%">
    <tr>
        <th class="encabezadoPrincipal"  align="right" style="border-right:none">
            Ventas contadora 
        </th>
        <th  class="encabezadoPrincipal" style="border-right:none; border-left:none" colspan="2" align="left">
            <img id="btnExportarPdfReporte" src="<?php echo base_url()?>img/pdf.png" width="22" title="PDF" onclick="reporteVentasContadora()" />
            &nbsp;&nbsp;
            <img id="btnExportarExcelReporte" src="<?php echo base_url()?>img/excel.png" width="22" title="Excel" onclick="excelVentasContadora()" />
    
            <br />
            
            <a>PDF</a>
            <a>Excel</a>
        </th>
    </tr>
    <tr>
        <th align="center">Departamento</th>
        <th align="center">Cantidad <br /> Total: <?=number_format($cantidad,decimales)?></th>
        <th align="center">Total <br /> Total: $<?=number_format($importe,decimales)?></th>
    </tr>
	<?php
	$i=1;
    foreach($ventas as $row)
    {
        echo'
        <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
            <td align="left">'.$row->departamento.'</td>
            <td align="right">'.number_format($row->cantidad,decimales).'</td>
            <td align="right">$'.number_format($row->importe,decimales).'</td>
        </tr>';
        
        $i++;
    }
    ?>
</table>

