<?php
$this->load->view('inventarioProductos/listas/pdf/encabezado');
?>

<table class="admintable" style="width:100%">

<?php
$i=1;
foreach($productos as $row)
{
	?>
	<tr>
		<td width="6%" style="color:#000;" align="right"><?php echo $i?></td>
		<td width="20%" style="color:#000;" align="center"><?php echo $row->codigoInterno?></td>
		<td width="35%" style="color:#000;" align="left"><?php echo $row->producto?></td>
		<td width="19%" style="color:#000;" align="left"><?php echo $row->linea?></td>
		<td width="10%" style="color:#000;" align="right">$<?php echo number_format($row->precioPasado,decimales)?></td>
		<td width="10%" style="color:#000;" align="right">$<?php echo number_format($row->precioNuevo,decimales)?></td>
	</tr>
	
	<?php
	$i++;
}
?>

</table>
