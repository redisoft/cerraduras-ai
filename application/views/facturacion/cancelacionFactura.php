<script type="text/javascript" src="<?php echo base_url()?>js/ocultar.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/facturacion.js"></script>
<div style="width:600px; height:300px">
<div class="barraherramientas">
<div align="center">
<img title="Cancelar factura" src="<?php echo base_url()?>img/guardar.png" class="botonesBarra" id="cancelarFactura" />
</div>
<div align="center" id="agregandoInformacion"></div>
</div>
<table class="admintabla" width="99%">
<tr>
	<th colspan="4">DATOS GENERALES DE LA FACTURA</th>
</tr>
<tr class="arriba">
	<td  class="etiquetas">Cliente:</td>
    <td>
    	<?php echo $factura->nombre?>
    </td>
</tr>

<tr class="abajo">
	<td  class="etiquetas">Folio:</td>
    <td>
    	<?php echo $factura->folioInterno?>
    </td>
</tr>

<tr class="arriba">
	<td  class="etiquetas">Total:</td>
    <td>
    	$ <?php echo number_format($factura->total,2)?>
    </td>
</tr>

<tr class="abajo">
	<td  class="etiquetas">Motivos de cancelación:</td>
    <td colspan="2">
    	<textarea class="textos" id="txtCancelacion" style="height:40px"></textarea>
        <input type="hidden" id="txtEncriptacion" value="<?php echo $factura->encriptacion?>" />
    </td>
</tr>
</table>
</div>