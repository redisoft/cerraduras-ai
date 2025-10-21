<div id="barraHerramientas">
<?php
	
	#$datestring = "%Y-%m-%d %H:%i:%s";
     
	#echo date("d-m-Y H:i:s");
	#echo date("h:i:s");;

?>
<a class="ajax" title="Facturación" href="<?php echo base_url()?>facturacion/cargarAgregarFactura"> 
<img src="<?php echo base_url()?>img/agregar.png" class="botonesBarra">
</a>
</div>

<div style="width:90%; padding-left:0%; margin-top:0%; margin-bottom:0%; text-align:center;" align="center">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<table class="admintabla" width="100%">
<tr>
	<th>#</th>
    <th>Nombre</th>
    <th>Fecha</th>
    <th>RFC</th>
    <th>Estado</th>
    <th>Telefono</th>
    <th>Email</th>
    <th>Folio</th>
    <th>Total</th>
    <th width="18%">Acciones</th>
</tr>
<?php
$i=1;
foreach($facturas as $row)
{
	$estilo="class=' abajo'";
	
	if($i%2>0)
	{
		$estilo="class=' arriba'";
	}
	
	echo'
	<tr '.$estilo.'>
		<td>'.$i.'</td>
		<td>'.$row->nombre.'</td>
		<td>'.$row->fecha.'</td>
		<td>'.$row->rfc.'</td>
		<td>'.$row->estado.'</td>
		<td>'.$row->telefono.'</td>
		<td>'.$row->email.'</td>
		<td>'.$row->folioInterno.'</td>
		<td>'.$row->total.'</td>
		<td class="vinculos">
		&nbsp;
			<a onclick="window.open(\''.base_url().'facturacion/imprimirFactura/'.$row->encriptacion.'\')">
				<img src="'.base_url().'img/imprimir.png" title="Imprimir" class="botonesGeneral" />
			</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a class="ajax" title="Detalles de factura de '.$row->nombre.'" href="'.base_url().'facturacion/detallesFactura/'.$row->encriptacion.'"> 
				<img src="'.base_url().'img/detalles.png" title="Ver detalles" class="botonesGeneral" />
			</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="'.base_url().'facturacion/descargarXML/'.$row->encriptacion.'">
				<img src="'.base_url().'img/xml.png" title="Imprimir" class="botonesGeneral" />
			</a>';
			
			if($row->cancelada==0)
			{
				echo'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a title="Cancelar factura" class="ajax" href="'.base_url().'facturacion/cancelacionFactura/'.$row->encriptacion.'">
				<img src="'.base_url().'img/cancelar.png" title="Cancelar factura" class="botonesGeneral" />
				</a>';
			}
			
			echo'<br />
			&nbsp;&nbsp;
			PDF &nbsp;
			Detalles &nbsp;
			XML &nbsp;';
			if($row->cancelada==0)
			{
				echo'Cancelar';
			}
		echo'</td>
	</tr>
	';
	
	$i++;
}
?>
</table>


