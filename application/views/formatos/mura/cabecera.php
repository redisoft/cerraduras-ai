
<htmlpageheader name="myHTMLHeader1">
	<div class="filaEmpresa">
		<?=$empresa->nombre?>
	</div>
    
	<div style="float:left; width:50%;">
		<table class="tablaEncabezados">
			<tr>
				<th>Original</th>
				<th colspan="2" class="plecaNaranja">OFERTA DE VENTA</th>
			</tr>
			<tr>
				<td class="normal">Número de documento</td>
				<td class="normal">Fecha documento</td>
				<td class="normal">Página</td>
			</tr>
			<tr>
				<td align="center"><?=$cotizacion->folioCotizacion?></td>
				<td align="center"><?=obtenerFechaMesCorto($cotizacion->fecha)?></td>
				<td align="center"><?='<label>{PAGENO}/{nb}';?></td>
			</tr>

			<tr>
				<td colspan="3" class="plecaNaranja">&nbsp;</td>
			</tr>
		</table>
	</div>

	<table class="tablaEncabezados" style="margin-top: -25px;">
		<tr>
			<td colspan="2" class="pleca">&nbsp;</td>
		</tr>
		<tr>
			<td width="94%;" style="text-align: right; font-weight: bold;">Moneda:</td>
			<td> MXP </td>
		</tr>
	</table>

</htmlpageheader>

<htmlpageheader name="myHTMLHeader1Even">
	<div class="filaEmpresa">
		<?=$empresa->nombre?>
	</div>
    
	<div style="float:left; width:50%;">
		<table class="tablaEncabezados">
			<tr>
				<th>Original</th>
				<th colspan="2" class="plecaNaranja">OFERTA DE VENTA</th>
			</tr>
			<tr>
				<td class="normal">Número de documento</td>
				<td class="normal">Fecha documento</td>
				<td class="normal">Página</td>
			</tr>
			<tr>
				<td align="center"><?=$cotizacion->folioCotizacion?></td>
				<td align="center"><?=obtenerFechaMesCorto($cotizacion->fecha)?></td>
				<td align="center"><?='<label>{PAGENO}/{nb}';?></td>
			</tr>

			<tr>
				<td colspan="3" class="plecaNaranja">&nbsp;</td>
			</tr>
		</table>
	</div>

	<table class="tablaEncabezados" style="margin-top: -25px;">
		<tr>
			<td colspan="2" class="pleca">&nbsp;</td>
		</tr>
		<tr>
			<td width="94%;" style="text-align: right; font-weight: bold;">Moneda:</td>
			<td> MXP </td>
		</tr>
	</table>
</htmlpageheader>

mpdf-->
<!-- set the headers/footers - they will occur from here on in the document -->
<!--mpdf
<sethtmlpageheader name="myHTMLHeader1" page="O" value="on" show-this-page="1" />
<sethtmlpageheader name="myHTMLHeader1Even" page="E" value="on" />
mpdf-->
