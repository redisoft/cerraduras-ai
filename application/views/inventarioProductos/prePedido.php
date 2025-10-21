<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet"  href="<?php echo base_url();?>css/adm/tablas.css" />
</head>

<body>
<?php
$this->load->view('clientes/formatos/pedido/encabezado');
?>

  <table class="admintable" style="width:99%">
    <tr>
      <th style="background-color:#FFF; color:#000; width:30%">DESCRIPCIÓN</th>
      <th style="background-color:#FFF; color:#000;">COLOR</th>
      <th style="background-color:#FFF; color:#000;">DISEÑO</th>
      <th style="background-color:#FFF; color:#000;" >UPC</th>
      <th style="background-color:#FFF; color:#000;" align="right">COSTO</th>
      <th style="background-color:#FFF; color:#000; width:8%" align="center">PEDIDO PZAS</th>
      <th style="background-color:#FFF; color:#000; width:12%" align="center">PEDIDO PESOS</th>
      <th style="background-color:#FFF; color:#000;" align="center">ENTREGA</th>
    </tr>
    <?php
	$unidades=0;
	$total=0;
	foreach($productos as $row)
	{
		$unidades+=$row->cantidad;
		$total+=$row->precio*$row->cantidad;
		?>
        <tr>
          <td style="color:#000;" align="left"><?php echo $row->descripcion?></td>
          <td style="color:#000; "><?php echo $row->color?></td>
          <td style="color:#000; " align="center"><?php echo $row->diseno?></td>
          <td style="color:#000; " align="right"><?php echo $row->codigoInterno?></td>
          <td style="color:#000; " align="right">$ <?php echo number_format($row->precio,2)?></td>
          <td style="color:#000; " align="center"><?php echo number_format($row->cantidad,0)?></td>
          <td style="color:#000; " align="right">$ <?php echo number_format($row->precio*$row->cantidad,2)?></td>
          <td style="color:#000; " align="center"><?php echo $row->fecha_entrega?></td>
        </tr>
        <?php
	}
    ?>
    <tr>
    	<td colspan="5">&nbsp;</td>
        <td align="center"><?php echo number_format($unidades,0)?></td>
        <td align="right">$ <?php echo number_format($total,2)?></td>
        <td></td>
    </tr>
  </table>
  </body>
  </html>
