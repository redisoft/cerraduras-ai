
<table class="admintable" style="width:99%">
  <tr>
  <th colspan="7">
  Egresos 
  <?php
	if($this->session->userdata('egresoInicio')!="")
	{
		print('del '.$this->session->userdata('egresoInicio')." al ".$this->session->userdata('egresoFin'));
	}
  ?>
  </th>
  </tr>
  <tr>
  <th align="right">#</th>
  <th>Fecha</th>
  <th>Concepto</th>
  <th>Banco</th>
  <th>Cuenta</th>
  <th>Forma de pago</th>
  <th>Monto</th>
  </tr>
  
  <?php
  	$i=1;
	$total=0;
	
   if($egresos!=null)
   {
	   foreach($egresos as $row)
	   {
		   $total+=$row->pago;
		?>
			<tr>
			<td align="right"><?php echo $i ?></td>
			<td align="center"><?php echo $row->fecha?></td>
            <td align="center"><?php echo $row->producto?></td>
			<td align="center"><?php echo $row->banco?></td>
			<td align="center"><?php echo $row->cuenta?></td>
            <td align="center"><?php echo $row->formaPago?></td>
			<td align="right">$ <?php echo number_format($row->pago,2)?></td>
			</tr>
		<?php
		$i++;   
	   }
   }
  ?>
  <tr>
  <th align="right" colspan="7">
   &nbsp; Total $ <?php echo number_format($total,2)?>
  </th>
 
  </tr>
  </table>