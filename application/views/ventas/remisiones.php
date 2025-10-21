<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/adm/dialog_box.css" />
<script>

	$(document).ready(function()
	{
		idFactura='<?php echo $this->session->userdata('idFacturaImpresion');?>';
		
		if(idFactura!="0")
		{
			$('#cargandoImpresion').fadeIn();
			$('#cargandoImpresion').html('<img src="'+ img_loader +'"/> Generando impresion de la factura...')
		}
		setTimeout('facturaPDF()',2000);
	});
	
	function facturaPDF()
	{
		$('#cargandoImpresion').fadeOut();
		
		idFactura='<?php echo $this->session->userdata('idFacturaImpresion');?>';
		//alert(idCotizacion);
		if(idFactura!="0")
		{
			window.location.href='http://'+base_url+'pdf/generarPDF/'+idFactura;
		}
	}
	
	function busqueda()
	{
		div = document.getElementById('bus_id');
		filtro=div.value;
		
		if(filtro=='')
		{
			showDialog('ERROR','Escriba el nombre del cliente a buscar','error',2);
			return;
		}
		direccion="<?php echo base_url()?>ventas/prebusqueda/"+filtro;
		
		window.location.href=direccion;
	}
	
	function busquedaZona()
	{
		div = document.getElementById('zonas');
		filtro=div.value;
		
		direccion="<?php echo base_url()?>ventas/busquedaZona/"+filtro;
		
		window.location.href=direccion;
	}
</script>


<div class="derecha">
<div class="barra">Remisiones</div>

<div class="submenu">
<table>
<tr>
<td width="50%" align="left" valign="middle" style="border:none";>
         Buscar por cliente
        <input type="text" id="bus_id" name="bus_id" value=""  class="cajas" maxlength="20" style="width:200px; background-color:#FFF;" />
        <?php print('<img src="'.base_url().'img/search_32.png" width="24px;" height="24px;" onclick="busqueda()" title="Buscar cliente" style="cursor:pointer;">'); ?> 
         </td>  
         
         <td width="30%" align="left" valign="middle" style="border:none";>
        Zona
        <select id="zonas" name="zonas" onchange="busquedaZona()" class="cajasSelect">
        <option value="todos">Todos</option>
			<?php 
			foreach($zonas as $zona)
			{
            ?>
             	<option value="<?php echo $zona['idZona']?>" 
                <?php
				
				if($this->session->userdata("idZona")==$zona['idZona']) 
				{
					?>
                    selected="selected"
                    <?php
				}
				?>
                
                ><?php echo $zona['descripcion']?></option> 
            <?php 
			}
		?>
        </select>
      
         </td>  
         <td width="15%" align="left" valign="middle" style="border:none";> 
         <a href="<?php echo base_url()?>clientes/prebusqueda/nada"
         <?php
		 if($this->session->userdata('busquedaCliente')=="")
		 {
			 print('style="display:none"');
		 }
		 else
		 {
			  print('style="display:block"');
		 }
		 ?>
         >
         Borrar busqueda
         <?php 
		 print
		 ('
			 <img src="'.base_url().'img/borrar.png" width="24px;" height="24px;" 
			 title="Borrar busqueda" style="cursor:pointer;"> 
		 '); 
		 ?> 
   		 </a>
        </td>    
</tr>
</table>
<div class="toolbar" id="toolbar" >
</div>
</div>
<?php

if(!empty ($Cotizaciones))
{
?>
<div class="listproyectos" style="padding-left:10px">

<div class="Error_validar" id="registroError" style="display:none; margin-top:2px; margin-bottom: 5px;"></div>

<div style="width:30%; padding-left:2%; margin-top:1%; margin-bottom:2%; text-align:center;" align="center">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>
<div id="cargandoImpresion" style="float:right; padding-right:700px">

</div>

<table class="admintable" width="99%;" >
 <tr>
   <th colspan="9" align="center" valign="middle">
   Remisiones
   <div align="right">
   <div id="facturando"></div>
   Facturar
   <img style="cursor:pointer" src="<?php echo base_url()?>img/acrobat.png" width="20" title="Facturar"  id="remision" />
    Total $
   <!--input type="text" name="totalFactura" id="totalFactura"  class="cajas" style="width:150px" readonly="readonly" value="0"/-->
   <label  name="totalFactura" id="totalFactura" >0</label>
   </div>
   </th>
  </tr>
 <tr>
        <th width="30px" align="center" valign="middle">#</th>
        <th width="250px" align="center" valign="middle">Remisión</th>
        <th width="250px" align="left" valign="middle">Empresa</th>
        <th width="250px" align="left" valign="middle">Fecha de pedido</th>
        <th width="250px" align="left" valign="middle">Fecha de entrega </th>
        <th width="250px" align="right" valign="middle">Monto</th>

        <!--th width="10%" align="left" valign="middle">Paqueteria</th-->
        <th width="250px" align="center" valign="middle">Acciones</th>
 </tr>

<?php
$No=0;
$No=$No+1;
foreach ($Cotizaciones as $Cotizacion)
{
$LinkAdd='Estatus';
$id_cliente=$Cotizacion['id'];
$ff=$Cotizacion['iddv'];
?>
     <tr>
        <td width="30px" align="center"> <?php print($No); ?> </td>
        <td width="140px" align="center"><?php print($Cotizacion['serie']); ?></td>
        <td width="210px" align="center"><?php print($Cotizacion['empresa']); ?></td>
        <td width="210px" align="left"> <?php print(substr($Cotizacion['fechadd'],0,10)); ?> </td>
        <td width="210px" align="left"><?php print(substr($Cotizacion['datentrega'],0,10)); ?> </td>
        <td width="210px" align="right">$ <?php print(number_format($Cotizacion['precioventa'],2)); ?> </td>
        
       
        <td width="250px" align="left" valign="middle">
        <input type="hidden" id="total<?php echo $No?>" 
         name="total<?php echo $No?>" value="<?php echo $Cotizacion['precioventa']?>" />
         
         <input type="hidden" id="subtotal<?php echo $No?>" 
         name="subtotal<?php echo $No?>" value="<?php echo $Cotizacion['subtotal']?>" />
        &nbsp;&nbsp;
        <a href="<?php echo base_url()?>pdf/nuevaRemision/<?php echo $Cotizacion['idct']?>">
        <img src="<?php echo base_url()?>img/print.png" width="18" />
     </a>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <img title="Mostrar productos"  id="mostrar<?php echo $No?>" src="<?php echo base_url()?>img/pver.png" width="18px" height="18px" style="cursor:pointer" />
         
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?php
		if($Cotizacion['idGlobal']==0)
		{
			?>
			<input type="checkbox" id="activar<?php echo $No?>" 
			onchange="calcularTotal()" value="<?php echo $Cotizacion['iddv']?>" 
			title="<?php echo $Cotizacion['iddv']?>" />
			
			<?php
		}
		else
		{
			print('<img src="'.base_url().'img/success.png" width="24" />');
		}
        ?>
   
    <br />
     Imprimir&nbsp;
 Envios&nbsp; 
 <?php
		if($Cotizacion['idGlobal']==0)
		{
			print('Facturar');
		}
		else
		{
			print('Facturado');
		}
?>
     	</td>
    </tr>
    
    <tr>
    <th colspan="8" style="background:#FFF; border:none">
    <div id="caja<?php echo $No?>" style="display:none">
    <table class="admintable" width="99%">
		<?php
		$sql="select a.cantidad, a.precio_normal, a.precio_importe,
				a.id, b.descripcion 
				from cotiza_productos AS a
				inner join productos AS b
				on(b.id=a.idp)
				where a.serie='".$Cotizacion['serie']."'";
				
		$query=$this->db->query($sql);
		$productos=$query->result();
        ?>
        
        <tr>
        <th>Producto</th>
        <th align="center">Cantidad</th>
        <th align="center">Entregado</th>
        <th align="center">Restante</th>
        <th></th>
        </tr>
        <?php
				
		foreach($productos as $row)
		{
			$sql="select sum(cantidad) as cantidad
				from ventas_entrega_detalles
				where idProducto='".$row->id."'";
				
			$query=$this->db->query($sql);
			
			$cantidades=$query->row();
			
			$cantidad=$cantidades->cantidad;
			
			$restante=$row->cantidad-$cantidad;
			
			?>
			<tr>
			<td><?php echo $row->descripcion ?></td>
			<td align="center"><?php echo $row->cantidad ?></td>
			<td align="center"><?php echo number_format($cantidad,0) ?></td>
			<td align="center"><?php echo number_format($restante,0) ?></td>
			<td align="center"> 
             <?php
			 if($restante==0)
			 {
				 ?>
				<img src="<?php echo base_url().'img/truck.png'; ?>"  
				 onclick="entregasTotales('<?php echo $row->id?>');"  
				 title="<?php echo 'Envios'; ?>" width="25" height="25" style="cursor:pointer;" />
				 <?php
			 }
			 else
			 {
				 ?>
				<img src="<?php echo base_url().'img/paqueteria.png'; ?>"  
				 onclick="entregasVelas('<?php echo $row->id?>','<?php echo $row->cantidad?>','<?php echo $cantidad?>');"  
				 title="<?php echo 'Envios'; ?>" width="25" height="25" style="cursor:pointer;" />
				 <?php
			  }
			 ?>
               <br />
             <?php 
			 if($restante==0)
			 {
				 print('Envio completo');
			 }
			 else
			 {
				 print('Envio');
			 }
			 ?>
             </td>
			</tr>
			<?php 
		}
        ?>
    </table>
    </div>
    </th>
    </tr>
	<?php
    $No++;
    }//Foreach
    
    ?>
    <input type="hidden" id="indice" name="indice" value="<?php echo $No?>" />
    </table>
    
    <div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

  <?php

	}//
	else
	{
		 print
		 (
		 	'<div class="Error_validar" style="margin-top:2px; width:95%; float:left margin-bottom: 5px;">
			   No se encontraron registros de remisiones.
			 </div>');
	}
?>

<!--                                          Productos entregados al cliente                                -->

<div style="visibility:hidden">

<div id="dialog-Entrega" title="Entrega de productos:">
<div style="width:99%;" id="id_CargandoEntrega"></div>

<div id="ErrorEntrega" class="ui-state-error" ></div>

<table class="admintable" width="99%;">

<tr>
  <td class="key">Fecha:</td>
  <td>
 <input name="FEC" id="FEC" type="text" class="cajasSelect" value="<?php echo date("d/m/Y");?>" />
 
  </td>
</tr>	

<tr>
  <td class="key">Cantidad:</td>
  <td>
  <input type="text" class="cajasSelect" name="CAN" id="CAN" />
 
  </td>
</tr>	
	<tr>
		<td class="key">Entrego:</td>
		<td>
        <input type="text" name="SUP" id="SUP" class="cajas" style="width:160px;" value=""  /> 
        
        </td>
	</tr>
</td>

</tr>

</table>

	<div id="entregaProductos"></div>
    
	</div>
</div>

<div style="visibility:hidden">
<div id="dialog-Entregados" title="Productos entregados:">
 <div id="ErrorEntregados" class="ui-state-error" ></div>
	<div id="productosEntregados"></div>
	</div>
</div>

	 <div id="dialogoRemision" title="Facturación de remisiones:">
	 <div style="width:99%;" id="facturando"></div>
	 <div id="ErrorRemision" class="ui-state-error" ></div>
	 <table class="admintable" width="99%;">
		<tr>
		  <td class="key">Seleccione cliente:</td>
		  <td>
		 	<select id="idCliente" class="cajas" style="width:auto">
            <?php
			foreach($clientes as $row)
			{
				print('<option value="'.$row->id.'">'.$row->empresa.'</option>');
			}
            ?>
            </select>
		  </td>
		</tr>	
        <tr>
            <td class="key">Subtotal</td>
            <td>
            	<label id="subtotal">0</label>
                <input type="hidden" id="subtotalFactura" value="0" />
            </td>
        </tr>
        <tr>
            <td class="key">Iva</td>
            <td>
            	<label id="iva">0</label>
                <input type="hidden" id="ivaFactura" value="0" />
            </td>
        </tr>
        <tr>
            <td class="key">Total</td>
            <td>
            	<label id="total">0</label>
                <input type="hidden" id="totalFactura" value="0" />
            </td>
        </tr>
	</table>
	 </div>
<!--                                          Productos entregados al cliente                                -->

</div>
<!-- Termina -->
</div>
