 <table class="admintable" style="width:99%">
  <tr>
  <th colspan="5">Compras</th>
  </tr>
  <tr>
  <th align="right">#</th>
  <th>Fecha compra</th>
  <th>Proveedor</th>
  <th>Descripcion</th>
  <th>Total</th>
  </tr>
  
  <?php
  	$i=1;
	$total=0;
	
   if($compras!=null)
   {
	   foreach($compras as $compra)
	   {
		   $total+=$compra->total;
		?>
			<tr>
			<td align="right"><?php echo $i ?></td>
			<td align="center"><?php echo $compra->fechaCompra?></td>
			<td align="center"><?php echo $compra->empresa?></td>
			<td align="center"><?php echo $compra->nombre?></td>
			<td align="right">$ <?php echo number_format($compra->total,2)?></td>
			</tr>
		<?php
		$i++;   
	   }
   }
  ?>
  <tr>
  <th align="right" colspan="5">
   	 Total $ <?php echo number_format($total,2)?>
  </th>
 
  </tr>
  </table>