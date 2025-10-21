<?php
$this->load->view('inventarioProductos/PDF/encabezado');
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
		<td width="35%" style="color:#000;" align="left"><?php echo $row->nombre?></td>
		 <td width="19%" style="color:#000;" align="left"><?php echo $row->linea?></td>
		<td width="10%" style="color:#000;" align="center"><?php echo number_format($row->stock,2)?></td>
		<td width="10%" style="color:#000;" align="right">$<?php echo number_format($row->precioA,2)?></td>
	</tr>
	
	<?php
	$i++;
}
?>

</table>
