  <table class="admintable" style="width:99%">
  <tr>
      <th colspan="8">
      Ingresos 
      <?php
        if($this->session->userdata('ingresoInicio')!="")
        {
            print('del '.$this->session->userdata('ingresoInicio')." al ".$this->session->userdata('ingresoFin'));
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
      <th>Orden de venta</th>
      <th>Monto</th>
  </tr>
  
  <?php
  	$i=1;
	$total=0;
	
   if($ingresos!=null)
   {
	   foreach($ingresos as $row)
	   {
		   $sql="select a.ordenCompra, b.empresa
		   from cotizaciones as a
		   inner join clientes as b
		   on a.idCliente=b.id
		   where a.idCotizacion='$row->idVenta'";
		   
		   $query=$this->db->query($sql);
		   
		   $query=$query->row();
		   
		   $total+=$row->pago;
		   
		    $orden 	= $this->reportes->obtenerOrdenVenta($row->idVenta);
			
		?>
			<tr>
                <td align="right"><?php echo $i ?></td>
                <td align="center"><?php echo $row->fecha?></td>
                <td align="center"><?php echo $row->producto?></td>
                <td align="center"><?php echo $row->banco?></td>
                <td align="center"><?php echo $row->cuenta?></td>
                <td align="center"><?php echo $row->formaPago?></td>
                <td align="center"><?php echo $orden?></td>
                <td align="right">$ <?php echo number_format($row->pago,2)?></td>
			</tr>
		<?php
		$i++;   
	   }
   }
  ?>
  <tr>
      <th align="right" colspan="8">
      Total $ <?php echo number_format($total,2)?>
      </th>
  </tr>
  </table>
