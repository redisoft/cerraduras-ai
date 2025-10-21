<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar">
 <table class="toolbar" width="100%" >
 	<tr>
    	<td class="seccion">
        Precios unitarios	
        </td>
    </tr>
    <tr>
          <td valign="middle" style="border:none" >
        <input type="text"  name="txtBusquedas" id="txtBusquedas" class="busquedas" placeholder="Seleccione producto"   
        onkeyup="buscarDato(this.value,'productoTerminado');" onblur="datoEncontrado();" style="width:300px;"/>
        
        <div align="left" class="suggestionsBox" id="listaInformacion" style="display: none; position:absolute; margin-left:37.5%; width:300px">
        		<img src="<?php echo base_url()?>img/upArrow.png" style="position: relative; top: -12px; left: 30px;" />
        	<div class="suggestionList" id="autoListaInformacion">
       		 &nbsp;
        	</div>
    	 </div>
         
         <?php
        if($this->session->userdata('idProductoTerminadoBusqueda')!="")
        {
			echo 
			'<br />
			<a href="'.base_url().'produccion/busquedaProductoTerminado/todos" class="toolbar" style="margin-left:520px">
			<span class="icon-option" 
			title="Añadir cliente"><img src="'.base_url().'img/quitar.png" width="30px;" 
			height="30px;" title="Borrar busqueda" />
			</span>
			Borrar busqueda</a>';
        }
        ?>        
        
        </td>
         </tr>
 </table>
 </div>
</div>

<div class="listproyectos">
<?php
if(!empty($productos))
{
?>
<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pagi'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>
<table class="admintable" width="100%">
    <tr>
	    <th class="encabezadoPrincipal">#</th>
        <th class="encabezadoPrincipal">Producto</th>
        <!-- (a) -->
        <th class="encabezadoPrincipal" style="width:12%;">
            <table width="100%" class="admintable">
                <tr>
                	<th class="encabezadoPrincipal" style="border:none;">(a)</th>
                </tr>
                <tr>
               		<th class="encabezadoPrincipal" style="height:54px"> P.Costo</th>
                </tr>
            </table>
        </th>
        <!-- (b) -->
        <th class="encabezadoPrincipal"> 
            <table class="admintable" width="100%;">
               <tr style="background-color:#F6F6F6">
               <th class="encabezadoPrincipal" style="border:none">(b)</th>
               </tr>
               <tr style="background-color:#F6F6F6">
               <th class="encabezadoPrincipal" style="height:54px"> Inventario inicial</th>
               </tr>
           </table>
       </th>
        <th class="encabezadoPrincipal">
            <table class="admintable" width="100%;">
               <tr style="background-color:#F6F6F6">
               <th class="encabezadoPrincipal" style="border:none">(c)</th>
               </tr>
               <tr style="background-color:#F6F6F6">
               <th class="encabezadoPrincipal" style="height:54px">Producto terminado en el mes</th>
               </tr>
           </table>
       </th>
        <th class="encabezadoPrincipal">
            <table class="admintable" width="100%;">
           <tr style="background-color:#F6F6F6">
           <th class="encabezadoPrincipal" style="font-weight:bold; border:none">(d)</th>
           </tr>
             <tr style="background-color:#F6F6F6">
           	 <th class="encabezadoPrincipal" style="height:54px"> Salida venta total mensual</th>
           </tr>
           </table>
        </th>
        
        <th class="encabezadoPrincipal">
           <table class="admintable" width="100%;">
           <tr style="background-color:#F6F6F6">
           <th class="encabezadoPrincipal" style="border:none">(e)</th>
           </tr>
           <tr style="border:none">
           <th class="encabezadoPrincipal"> 
           Total de venta <br />
			(a)*(d)
           </th>
           </tr>
           </table>
       </th>
       
		 <th class="encabezadoPrincipal">
             <table class="admintable" width="100%;">
               <tr style="background-color:#F6F6F6; border:none">
               <th class="encabezadoPrincipal" style="border:none">(f)</th>
               </tr>
                 <tr style="background-color:#F6F6F6; border:none">
               <th class="encabezadoPrincipal"> 
                Inventario<br />
                Unidades<br />
                (b)+(c)-(d)
               </th>
               </tr>
               </table>
           </th>
        <th  class="encabezadoPrincipal">
              <table class="admintable" width="100%;">
               <tr style="background-color:#F6F6F6;">
               <th class="encabezadoPrincipal" style="width:80px; border:none">
                (g)<br />
                </th>
               </tr>
                <tr style="border:none">
                <th class="encabezadoPrincipal">
                Final <br />
				$<br />
                (a)*(f)
               </th>
               </tr>
           </table>
        </th>  
		<!--th>Acciones</th-->             
    </tr>
<?php
	
	$i=1;
	$registros=$numeroRegistros+1;
	
	$fecha = date('Y-m-d');
	$mes= substr($fecha,5,2);
	$anio= substr($fecha,0,4);
		
	 foreach($productos as $row)
	 {
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$sql="select a.idProducto, a.nombre, 
			b.idProductoProduccion, b.cantidad
			from productos as a
			inner join rel_producto_produccion as b
			on (a.idProducto=b.idProducto)
			inner join produccion_productos as c
			on(b.idProductoProduccion=c.idProducto)
			where c.idProducto='".$row->idProducto."' ";
		
		$cantidad=0;
		$cantidadProducida=0;
		$vendido=0;
			
		foreach($this->db->query($sql)->result() as $rou)
		{
			$sql="select sum(a.cantidad) as cantidad 
			from produccion_orden_detalle as a
			inner join produccion_orden_produccion as b
			on (a.idOrden=b.idOrden)
			where b.idProducto='".$rou->idProducto."' 
			and month(a.fechaProduccion)<'".$mes."'
			and year(a.fechaProduccion)<='".$anio."' 
			and idRelacion='0' ";
			
			#echo $sql;
			$cantidad+=$this->db->query($sql)->row()->cantidad*$rou->cantidad;
			
			$sql="select sum(a.cantidad) as cantidad 
			from produccion_orden_detalle as a
			inner join produccion_orden_produccion as b
			on (a.idOrden=b.idOrden)
			where b.idProducto='".$rou->idProducto."' 
			and month(a.fechaProduccion)='".$mes."'
			and year(a.fechaProduccion)='".$anio."'
			and idRelacion='0' ";	

			$cantidadProducida+=$this->db->query($sql)->row()->cantidad*$rou->cantidad;
			
			$sql="select sum(a.cantidad) as cantidad 
			from ventas_entrega_detalles as a
			inner join cotiza_productos as b
			on (a.idProducto=b.idProducto)
			where b.idProduct='".$rou->idProducto."' 
			and month(a.fecha)='".$mes."'
			and year(a.fecha)='".$anio."'";	
			
			$vendido+=$this->db->query($sql)->row()->cantidad*$rou->cantidad;
		}
		
		$sql="select stock
		from produccion_productos
		where idProducto='$row->idProducto'";
		
		$inicial=$this->db->query($sql)->row()->stock;
		
		switch($cantidad)
		{
			case 0:
			$cantidad=$inicial-$cantidadProducida;
		}
		
		$precio=(($row->costo+$row->costoAdministrativo)/$row->piezas)*(($this->session->userdata('iva'))/100+1)*(($row->utilidadA)/100+1);
		$totalVendido=$vendido*$precio;
		$totalPiezas=$cantidad+$cantidadProducida-$vendido;
		$totalDineroPiezas=$totalPiezas*$precio;
		
		?>
		
        <tr <?php echo $estilo?>>
			<td align="center" valign="middle"> <?php echo $registros; ?> </td>
			<td align="left"   valign="middle"> <?php echo $row->nombre?> </td>
			<td align="right"  valign="middle">$ <?php echo number_format($precio,4); ?>  </td>
			<td align="center" valign="middle"> <?php echo number_format($cantidad,0) ?> </td>
			<td align="center" valign="middle"> <?php echo number_format($cantidadProducida,0)?>  </td>
			<td align="center" valign="middle"> <?php echo number_format($vendido,0)?></td>
			<td align="center" valign="middle">$  <?php echo number_format($totalVendido,4)?> </td>
			<td align="center" valign="middle"> <?php echo number_format($totalPiezas,0)?>   </td>
			<td align="right"  valign="middle">$  <?php echo number_format($totalDineroPiezas,4) ?> </td>
		 </tr>
			<?php
			$i++;
			$registros++;
	}
?>
</table>
<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pagi'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<?php

}
	else
	{
		echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de productos</div>';
	}
	?>
</div>
</div>
