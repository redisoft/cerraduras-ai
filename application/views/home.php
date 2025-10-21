
<script>
function quitar()
{
	$('#loader').fadeOut();
	$('#cargarVentas').fadeOut();
}
function cargarGrafica()
{
	//alert($("#productos5").val());
	
	 var URL="http://"+base_url+"principal/graficaProduccion";
	 $('#loader').fadeIn();
	  $.ajax({
              async:true,
         beforeSend:function(objeto){$('#loader').html('<img src="'+ img_loader +'"/> Espere...');},
               type:"POST",
  	        url:URL,
               data:{"mes":$("#mesB").val(),"anio":$("#anioB").val(),"producto1":$("#productos1").val(),"producto2":$("#productos2").val(),"producto3":$("#productos3").val(),"producto4":$("#productos4").val(),"producto5":$("#productos5").val()},
           datatype:"html",
            success:function(data, textStatus){
								       					   					   
                           switch(data){
                                   case "0":
                                            $("#errorGrafica").fadeIn();
                                            $("#errorGrafica").html("<p>Error al generar la grafica.</p>");
											$('#loader').fadeOut();
                                             break;
                                   case "1":
											$('#graficaProduccion').load("http://"+base_url+"principal/obtenerGrafica");
											
											window.setTimeout("quitar()",500);  
                                            break;

                           }//switch
 	               },
	         error:function(datos){
                    $("#errorGrafica").fadeIn();
		    $("#errorGrafica").html(datos);	
                  }
           });//Ajax			
}

function cargarGraficaVentas()
{
	var URL="http://"+base_url+"principal/graficaVentas";
	$('#cargarVentas').fadeIn();
	 
	$.ajax(
	{
  		async:true,
        beforeSend:function(objeto){$('#cargarVentas').html('<img src="'+ img_loader +'"/> Espere...');},
        type:"POST",
  	    url:URL,
        data:{"anio":$("#anio").val()},
        datatype:"html",
        success:function(data, textStatus)
		{
		   switch(data)
		   {
		   		case "0":
						$("#errorGrafica").fadeIn();
						$("#errorGrafica").html("<p>Error al generar la grafica.</p>");
						$('#cargarVentas').fadeOut();
						 break;
			    case "1":
						$('#graficaVentas').load("http://"+base_url+"principal/obtenerGraficaVentas");
					     window.setTimeout("quitar()",500);  
						break;
			}//switch
		},
		 error:function(datos)
		 {
			$("#errorGrafica").fadeIn();
			$("#errorGrafica").html(datos);	
		 }
	 });//Ajax			
}
 
</script>
<div class="derecha">

<div class="barra">Reportes de ventas </div>

<div class="listproyectos" style=" margin-left:1.5%; width: 97%;" align="center">

    <table class="admintable" style="width:95%">
    <tr>
    <th width="20%">Ventas</th>
    <th>Produccion</th>
    </tr>
    <tr>
    <td class="key">
    Seleccione el año
      <select class="cajasSelect" id="anio" name="anio" onchange="cargarGraficaVentas()">
        <?php
		for($i=2011;$i<=2050;$i++)
		{
			print('<option value="'.$i.'">'.$i.'</option>');
		}
        ?>
        </select>
    </td>
    <td class="key">
      
      Mes
  			 <select name="mesB" id="mesB" class="cajasSelect">
                  <option value="01">ENE</option>
                  <option value="02">FEB</option>
                  <option value="03">MAR</option>
                  <option value="04">ABR</option>
                  <option value="05">MAY</option>
                  <option value="06">JUN</option>
                  <option value="07">JUL</option>
                  <option value="08">AGO</option>
                  <option value="09">SEP</option>
                  <option value="10">OTC</option>
                  <option value="11">NOV</option>
                  <option value="12">DIC</option>
              </select>
			 &nbsp; Año
             <select name="anioB" id="anioB" class="cajasSelect">
			  <?php
			  $i=2011;
			   while($i<2051)
			   {
					print('<option value="'.$i.'">'.$i.'</option>');
					$i++;
			   }
              ?>
              </select>
              &nbsp;
              <img onclick="cargarGrafica()"  src="<?php echo base_url()?>img/search_32.png" width="25" style="cursor:pointer" title="Generar grafica"/>
        <br />
		<?php
		
		for($i=1;$i<6;$i++)
		{
		    print('<select class="cajas" style="width:100px" name="productos'.$i.'" id="productos'.$i.'">');
			foreach($productos as $producto) 
			{
				?>
					<option value="<?php  echo $producto->id?>"><?php echo $producto->descripcion ?></option>
				<?php
			}
		  print('</select>');
		}
        ?>
       
        </td>
    </tr>
    
    <tr>
        <td>
        <div id="cargarVentas" style="width: 50%;" align="center" > </div>
  
<div class="listproyectos" style="width:50%;" align="center" id="graficaVentas">
 
<iframe FRAMEBORDER="0" BORDER=0 width="420px" align="middle" 
    height="450px" 
    src="http://www.maptools.org/cgi-bin/owtchart?type=3DBar&NumSets=1&vals=<?php echo $ventas[1]/1000?>!<?php echo $ventas[2]/1000?>!<?php echo $ventas[3]/1000?>!<?php echo $ventas[4]/1000?>!<?php echo $ventas[5]/1000?>!<?php echo $ventas[6]/1000?>!<?php echo $ventas[7]/1000?>!<?php echo $ventas[8]/1000?>!<?php echo $ventas[9]/1000?>!<?php echo $ventas[10]/1000?>!<?php echo $ventas[11]/1000?>!<?php echo $ventas[12]/1000?>&numpts=12&setcolors=054258&xlabels=Enero%3BFebrero%3BMarzo%3BAbril%3BMayo%3BJunio%3BJulio%3BAgosto%3BSeptiembre%3BOctubre%3BNoviembre%3BDiciembre&w=400&h=400&Title=Reporte%20de%20ventas%20anual&XTitle=Meses&XTitleColor=101010&XTitleFont=G&YTitle=Dinero%20%28Miles%20de%20pesos%29&YTitleColor=0C0C0C&YTitleFont=G&YMin=0&YMax=1000&BarWidth=&StackType=beside"  
    name="menu" allowtransparency="true" style="background-color: transparent;" scrolling="no">
</iframe> 

</div>
        </td>
        <td>
          <div id="loader" style="width:50%;" align="center" > </div>
    
    <div id="graficaProduccion" class="listproyectos" style="width:50%;" align="left">

<iframe FRAMEBORDER="0" BORDER=0 width="420px" align="middle" 
    height="450px" 
    src="http://www.maptools.org/cgi-bin/owtchart?type=3DBar&NumSets=1&vals=0!0!0!0!0&numpts=5&setcolors=054258&xlabels=Producto1%3BProducto2%3BProducto3%3BProducto4%3BVProducto5&w=400&h=400&Title=Reporte%20de%20produccion&XTitle=Productos&XTitleColor=101010&XTitleFont=G&YTitle=Cajas%20%28Miles%20de%20piezas%29&YTitleColor=0C0C0C&YTitleFont=G&YMin=0&YMax=100&BarWidth=&StackType=beside"  
    name="menu" allowtransparency="true" style="background-color: transparent;" scrolling="no">
</iframe> 

</div>
        </td>
    </tr>
    
   </table>
</div>
</div>

<?php //  src="https://chart.googleapis.com/chart?cht=p3&chd=t:95,5&chs=770x200&chl=Ganancias 95%|Perdidas 5%"  
//
?>