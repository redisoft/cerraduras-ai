<script>
function busquedaCliente()
{
	idCliente=$('#idCliente').val()
	
	window.location.href='http://'+base_url+'ventas/prebusquedaFacturasCliente/'+idCliente;
}
</script>

<div class="derecha">
<div class="barra">Facturas por cliente</div>

<div style="padding-left:10px">
 <table class="admintable" width="99%;">
		<tr>
		  <td class="key">Seleccione cliente:</td>
		  <td class="key">
		 	<select id="idCliente" class="cajas" style="width:auto" onchange="busquedaCliente()">
            <?php
			foreach($clientes as $row)
			{
				print('<option value="'.$row->id.'">'.$row->empresa.'</option>');
			}
            ?>
            </select>
		  </td>
		</tr>	
		</table>
</div>
<?php

if(!empty ($facturas))
{
?>
<div class="listproyectos" style="padding-left:10px">

<div class="Error_validar" id="registroError" style="display:none; margin-top:2px; margin-bottom: 5px;"></div>

<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<table class="admintable" width="99%;" >
 <tr>
   <th colspan="9" align="center" valign="middle">
   Facturas
   </th>
  </tr>
 <tr>
        <th width="30px" align="center" valign="middle">#</th>
        <th width="250px" align="center" valign="middle">Fecha factura</th>
        <th width="200px" align="center" valign="middle">Folio</th>
        <th width="250px" align="right" valign="middle">Monto</th>
        <th width="250px" align="center" valign="middle">Acciones</th>
 </tr>

<?php
$i=1;

foreach ($facturas as $row)
{
	
?>
     <tr>
        <td width="30px" align="center"> <?php print($i); ?> </td>
        <td width="140px" align="center"><?php print($row->fecha); ?></td>
        <td width="210px" align="center"><?php print($row->folio); ?></td>
        <td width="210px" align="right">$ <?php print(number_format($row->total,2)); ?> </td>
        <td width="250px" align="center" valign="middle">
        
        <?php
		if($row->cancelada=="0")
		{
			?>
            <a title="Ver factura en PDF" href="<?php echo base_url()?>pdf/GenerarPDF/<?php echo $row->idFactura?>">
        	<img src="<?php echo base_url()?>img/pdf.png" width="25" />
        	</a>
            <?php
		}
        ?>
        
        <?php
		if($row->cancelada=="1")
		{
			?>
            <a title="Ver factura en cancelada en PDF" href="<?php echo base_url()?>pdf/GenerarPDF/<?php echo $row->idFactura?>">
        	<img src="<?php echo base_url()?>img/pdfCortado.png" width="25" />
        	</a>
            <?php
		}
        ?>
        
        
         <a title="Descargar xml" href="<?php echo base_url()?>factura_ventas/descargarXML/<?php echo $row->idFactura?>">
        <img src="<?php echo base_url()?>img/xml.png" width="25" style="cursor:pointer" />
        </a>
        <?php
        if($row->cancelada=="0")
		{
		?>
            <img src="<?php echo base_url()?>img/pdfCancelado.png" title="Cancelar Factura" 
            width="25" style="cursor:pointer"  onclick="cancelacionFactura('<?php echo $row->idFactura?>')"/>
         <?php
		}
		?>
        <!--img onclick="obtenerRemisionesFactura('<?php echo $row->idFactura?>')" 
        src="<?php echo base_url()?>img/reports.png" width="25" style="cursor:pointer" title="Remisiones" /-->
     	</td>
    </tr>
    
    <tr>
    <th colspan="8" style="background:#FFF; border:none">
    <div id="caja<?php echo $i?>" style="display:none">
    <table class="admintable" width="99%">
		
    </table>
    </div>
    </th>
    </tr>
	<?php
    $i++;
    }//Foreach
    
    ?>
    <input type="hidden" id="indice" name="indice" value="<?php echo $i?>" />
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
			   No se encontraron registros de facturas por cliente.
			 </div>');
	}
?>

	<div style="visibility:hidden">
    <div id="remisionesFactura" title="Remisiones">
    <div style="width:99%;" id="cargandoRemisiones"></div>
    <div id="ErrorRemisiones" class="ui-state-error" ></div>
    <div id="cargarRemisiones"></div>
    </div>
    </div>
    
    <div style="visibility:hidden">
    <div id="cancelarFactura" title="Cancelar factura">
    <div style="width:99%;" id="cargandoCancelacion"></div>
    <div id="ErrorCancelacion" class="ui-state-error" ></div>
    <div id="cargarFolio"></div>
    </div>
    </div>

</div>
<!-- Termina -->
</div>
