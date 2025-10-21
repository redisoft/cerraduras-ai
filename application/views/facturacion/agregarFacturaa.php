<script type="text/javascript" src="<?php echo base_url()?>js/facturacion.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/ocultar.js"></script>
<div style="width:1000px; height:600px">
<div class="barraherramientas">
<div align="center">
<img title="Guardar factura" src="<?php echo base_url()?>img/guardar.png" class="botonesBarra" id="agregarFactura" />
</div>
<div align="center" id="agregandoInformacion"></div>
</div>
<form id="frmFactura" name="frmFactura" >
<table class="admintabla" width="99%">

<tr>
	<th colspan="4">DATOS DE LA EMPRESA</th>
</tr>
<tr class="arriba">
	<td  class="etiquetas">Nombre:</td>
    <td>
    	<input type="text" class="textos" name="txtNombre" id="txtNombre" value="Mauricio Flores de Jesus"  />
    </td>
	<td  class="etiquetas">RFC:</td>
    <td>
    	<input type="text" class="textos" name="txtRfc" id="txtRfc" value="CIE030604NC1"  />
    </td>
</tr>
<tr class="abajo">
	<td  class="etiquetas">Dirección:</td>
    <td>
    	<input type="text" class="textos" name="txtDireccion" id="txtDireccion" value="Privada Jacarandas"  />
    </td>
	<td  class="etiquetas">Numero:</td>
    <td>
    	<input type="text" class="textos" name="txtNumero" id="txtNumero" value="3"  />
    </td>
</tr>
<tr class="arriba">
	<td  class="etiquetas">Colonia:</td>
    <td>
    	<input type="text" class="textos" name="txtColonia" id="txtColonia" value="Buena vista"  />
    </td>
	<td  class="etiquetas">Codigo postal:</td>
    <td>
    	<input type="text" class="textos" name="txtCodigo" id="txtCodigo" value="72000"  />
    </td>
</tr>
<tr class="abajo">
	<td  class="etiquetas">Ciudad:</td>
    <td>
    	<input type="text" class="textos" name="txtCiudad" id="txtCiudad" value="Puebla"  />
    </td>
	<td  class="etiquetas">Estado:</td>
    <td>
    	<input type="text" class="textos" name="txtEstado" id="txtEstado" value="Puebla"  />
    </td>
</tr>
<tr class="arriba">
	<td  class="etiquetas">Pais:</td>
    <td>
    	<input type="text" class="textos" name="txtPais" id="txtPais" value="México"  />
    </td>
	<td  class="etiquetas">Teléfono:</td>
    <td>
    	<input type="text" class="textos" name="txtTelefono" id="txtTelefono" value="223287635"   />
    </td>
</tr>
<tr class="abajo">
	<td  class="etiquetas">Email:</td>
    <td>
    	<input type="text" class="textos" name="txtEmail" id="txtEmail" value="licfloresdejesus@gmail.com"  />
    </td>
    <td colspan="2"></td>
</tr>

</table>

<table class="admintabla" width="99%" id="tablaFactura">
<tr>
	<th colspan="5">
    CONCEPTOS
		<img onclick="agregarConcepto()" src="<?php echo base_url()?>img/agregar.png" 
        	class="botonesGeneral" style="cursor:pointer" title="Agregar Concepto">
            
            <img onclick="actualizarPreciosFactura()" src="<?php echo base_url()?>img/actualizar.png" 
        	class="botonesGeneral" style="cursor:pointer" title="Actualizar precios">
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            SUBTOTAL $
            <input style="width:100px" value="0" type="text" class="textos" name="txtSubTotal" id="txtSubTotal" readonly="readonly"  />&nbsp;
            DESCUENTO %
            <input style="width:50px" value="0" type="text" class="textos" name="txtDescuento" id="txtDescuento"  />&nbsp;
            IVA %
            <input style="width:50px" value="16" type="text" class="textos" name="txtIva" id="txtIva"  />&nbsp;
            TOTAL $
            <input style="width:100px" value="0" type="text" class="textos" name="txtTotal" id="txtTotal" readonly="readonly"  />
            <input type="hidden" class="textos" name="contadorConceptos" value="1" id="contadorConceptos"  />
	    </th>
</tr>
<tr>
	<th width="3%"></th>
    <th width="30%">Descripción</th>
    <th width="10%">Cantidad</th>
    <th width="15%">Precio unitario</th>
    <th width="15%">Importe</th>
</tr>
</table>
</form>
</div>