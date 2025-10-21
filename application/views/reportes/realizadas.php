
<script>

	function busquedaFacturaFechas()
	{
		inicio=document.getElementById('FechaDia').value;
		fin=document.getElementById('FechaDia2').value
		
		if(inicio==""|| fin=="" )
		{
			alert("Debe llenar ambos campos");
			return;
		}
		
		if(inicio > fin)
		{
			alert('La fecha de inicio no puede ser mayor a la final.');
			return;
		} 
		
		direccion="http://"+base_url+"reportes/busquedaFacturaFechas/"+inicio+"/"+fin;
		
		window.location.href=direccion;
	}

	function busquedaCliente()
	{
		cliente=document.getElementById('idCliente').value;
		
		//alert(cliente);
		direccion="http://"+base_url+"reportes/busquedaCliente/"+cliente;
		
		window.location.href=direccion;
	}
	
	function busquedaFolioFactura()
	{
		folio=document.getElementById('folio').value;
		
		//alert(folio);
		direccion="http://"+base_url+"reportes/busquedaFolioFactura/"+folio;
		
		window.location.href=direccion;
	}
	
</script>
<div class="derecha">

<div class="barra">Facturas realizadas</div>

<div class="submenu">

  <table class="toolbar" style="width:99%">
    <tr>
      <td align="left" valign="middle" style="font-size:12px; width:18%">
        Folio
        <input type="text" id="folio" name="folio"  class="cajas" style="width:50px; background-color:#FFF;" />
        <?php print('<img src="'.base_url().'img/search_32.png" width="30px;" height="30px;" id="fol" onclick="busquedaFolioFactura()" title="Buscar factura" style="cursor:pointer;">'); ?>
      </td>
      <td valign="middle" style="font-size:12px; width:48%">
          Fecha inicial: <input type="text" id="FechaDia"  name="FechaDia" class="cajas" style="width:80px;" />&nbsp;&nbsp;
          &nbsp;&nbsp;Fecha final: <input type="text" id="FechaDia2" name="FechaDia2" class="cajas" style="width:80px;" />&nbsp;
          <?php print('<img src="'.base_url().'img/search_32.png" width="30px;" height="30px;" onclick="busquedaFacturaFechas()" title="Buscar factura por fechas" style="cursor:pointer;">'); ?>
      </td>

      <td>
      Cliente &nbsp;
      
       <input type="text"  name="txtBusquedas" id="txtBusquedas" class="cajas"   
                onkeyup="buscarDato(this.value,'facturas');" onblur="datoEncontrado();" style="width:200px; background-color:#FFF"/>
                
                <div align="left" class="suggestionsBox" id="listaInformacion" 
                	style="display: none; position:absolute; margin-left:73%">
                        <img src="<?php echo base_url()?>img/upArrow.png" style="position: relative; top: -12px; left: 30px;" />
                    <div class="suggestionList" id="autoListaInformacion">
                     &nbsp;
                    </div>
                 </div>
           <!--select class="cajas" style="width:auto" onchange="busquedaCliente()" name="idCliente" id="idCliente">
           
           <option value="nada">Todos</option>
           <?php
		   foreach($clientes as $cliente)
		   {
			   ?>
               <option value="<?php echo $cliente->id?>"
               <?php
			   if($cliente->id==$this->session->userdata('idClienteFactura'))
			   {
				   print(' selected="selected"');
			   }
               ?>
               ><?php echo $cliente->empresa?></option>
               <?php
		   }
           ?>
           </select-->
      </td>

     </tr>

 </table>

<div class="Error_validar" id="id_errorFechas" style="display:none;"></div>

</div>

<div class="listproyectos">

<div id="CargandoID" style="width:92%; float:left; margin-top:2px; margin-bottom: 5px;"></div>

<div class="Error_validar" id="registroError" style="display:none; width:90%; margin-left:4%; margin-top:2px; margin-bottom: 5px;"></div>

<?php

if(!empty($Facturas)){

?>

<div id="RESPUESTACLIENTE" style="width:95%;float:left; margin-left:4%;">


    <input type="hidden" name="id_inicio" id="id_inicio" value="<?php //print($inicio); ?>">

 

<div style="width:90%; margin-bottom:2%; padding-bottom:1%; text-align:center;" align="center">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<p style="font-size:12px;"> Total de registros: <span style="color:#c50101; font-size:13px;"><?php //print($TotalRegistros); ?></span></p>

<table class="admintable" width="99%;">

    <tr>
        <th>#</th>
        <th>Cliente</th>
        <th>Folio</th>
        <th>Total</th>
        <th>Recibido</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>

<?php
$No=1;

foreach($Facturas as $Factura){

     $Link_PDFCandelada="";
       $CancelarFactura="";
              $Link_PDF="";
          $DescargarZip="";
          $ImgCancelada="";
        $FacturaMotivos="";


   switch($Factura['cancelada']){

      case "0":
                /*
                 $OnClick="LlamarVentanaFactura('".$No."');";
         $CancelarFactura='<img src="'.base_url().'img/borrar.png" width="17px" height="17px" style="cursor:pointer;" id="Id_CancelarFactura"
                                 border="0" title="Cancelar factura." alt="Cancelar factura."  onclick="'.$OnClick.'"/>
                              ';
                */
          $URL=base_url().'pdf/generarPDF/'.$Factura['idf'];
     $Link_PDF='<a title="Ver Factura en PDF." href="'.$URL.'" target="_black">
                 <img src="'.base_url().'img/pdf.png" width="17px" height="17px"
                     alt="Ver Factura en PDF."    border="0"/>
               </a>';


              $URL=base_url().'factura_ventas/download/'.$Factura['idf'];
     $DescargarZip='<a title="Descargar archivo XML." href="'.$URL.'" target="_black">
                      <img src="'.base_url().'img/box_down.png" width="17px" height="17px"
                           alt="Descargar archivo XML."    border="0"/>
                   </a>';

              break;
      
     }//Siwtch


print('
  <tr>
  <td valign="middle" align="center">'.$No.'</td>
  <td valign="middle" align="center">'.$Factura['empresa'].'</td>
  <td valign="middle" align="center">'.$Factura["folio"].'</td>
  <td valign="middle" align="right" >$ <span class="box_articulos">'.number_format($Factura['total'],2).'</span></td>
  <td valign="middle" align="center">'.$Factura['recibido_by'].'</td>
  <td valign="middle" align="center"> '.substr($Factura['fecha_factura'],0,10).'</td>
     <td valign="middle" align="center">
         '.$Link_PDF.'



    </td>
 </tr>');
  $No++;
}//Foreach

?>

</table>
    

<div style="width:90%; margin-top:1%; margin-bottom:1%; text-align:center;" align="center">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

</div><!--  RESPUESTACLIENTES -->



<?php

}//Fin IF de Facturas
else{
?>

<div class="Error_validar" style="margin-top:2px; width:90%; margin-bottom: 5px; margin-left:4%;">No hay registros de facturas realizadas</div>

 <?php
   }//else
 ?>

<!-- Formulario para Cancelar la Factura -->


<!-- Termina la lista de Contactos -->

<!-- Formulario para Ver Motivos de cancelacion -->

<div id="dialog-MotivosFactura" title="Motivos de cancelación">

<div style="width:99%;" id="id_CargandoMotivosFactura"></div>

<div id="ErrorMotivosFactura" class="Error_validar" style="display:none;" ></div>

<!-- Muestra los productos -->
<!-- ************************ -->
<div id="RESPUESTAFORMULARIOMOTIVOS" style="float:left; vertical-align:top; width:100%;" ></div>

</div>
<!-- Termina la lista de Contactos -->


</div>
<!-- Termina -->
</div>
