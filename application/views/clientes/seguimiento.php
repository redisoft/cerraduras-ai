<div class="derecha">
<div class="submenu">
<table class="toolbar" width="100%">
    <tr>
     <td class="seccion">Seguimiento</td>
     <td   align="left" valign="middle" style="font-size:14px; text-align:left" colspan="3"> 
		<?php
        echo 'Cliente: '.$cliente->empresa
        ?>
     </td>
     </tr>
     <tr>
       <td align="center" valign="middle" style="border:none; width:10%"  >
        <a href="<?php echo base_url()?>clientes/ficha" class="toolbar" id="Id_Cotizacioness">
        <span class="icon-option" title="Lista de cotizaciones">
          <img src="<?php print(base_url()); ?>img/remision.png" width="30px" class="Id_Cotizacioness" 
          id="Id_Cotizacioness" title="Ver lista de remisiones" alt="Ver lista de remisiones" style="vertical-align:middle;display:inline-table;cursor:pointer;" /> </span>
           Cotizaciones
        </a>
       </td>
        <td align="center" valign="middle" class="button" style="border:none; width:10%">               
        <a class="toolbar" href="<?php echo base_url()?>clientes/ventas">
        <span class="icon-option" title="Lista de compras">
         <img src="<?php print(base_url()); ?>img/almacen.png" width="30px"  title="Ver lista de compras" alt="Ver lista de compras" 
           style="vertical-align:middle;display:inline-table;cursor:pointer;" /> </span>
         Ventas
        </a>                  
      </td>
                  
       <td align="center" valign="middle" style="border:none; width:10%">         
       <a class="toolbar" href="<?php echo base_url()?>ficha/contactos/<?php echo $this->uri->segment(3)?>" >
        <span class="icon-option" title="Contactos">
          <img src="<?php print(base_url()); ?>img/contactos.png" width="20px" id="" title="Contactos" 
         style="vertical-align:middle;display:inline-table;cursor:pointer;" />  </span>
           Contactos                      
        </a>      
       </td>
       
          <td align="center" valign="middle" style="border:none; width:60%" >         
       <a class="toolbar" href="<?php echo base_url()?>clientes/seguimiento/<?php echo $this->uri->segment(3)?>" >
        <span class="icon-option" title="Seguimiento">
          <img src="<?php print(base_url()); ?>img/seguimiento.png" width="30px" height="30px" id="" title="Seguimiento" 
         style="vertical-align:middle;display:inline-table;cursor:pointer;" />  </span>
           Seguimiento                      
        </a>      
       </td>
   </tr>
</table>
</div>

<div class="listproyectos">
<input type="hidden" name="id_cli" id="id_cli" value="<?php print($IDC); ?>"  />
<?php

 echo 
 '
	 <div align="right" style="margin-left:60%; padding-right:20px">
	 Normal<img src="'.base_url().'img/GreenBall.png" width="25">
	 
	 Proximo<img src="'.base_url().'img/YellowBall.png" width="25">

	 Pendiente<img src="'.base_url().'img/RedBall.png" width="25">';
	 
	if($permiso->escribir=='1')
	{ 
		echo'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<img src="'.base_url().'img/add.png" width="32" height="32" 
		class="agregarSeguimiento" id="agregarSeguimiento" 
		style="padding-right:10px;cursor:pointer;" title="Añadir seguimiento">
		<br />
		<a style=" color:#0285C0; font-weight:bold">Agregar</a>';
	}
	
 echo'</div>'; 
 
if(!empty($seguimientos))
{
	echo'
	<table class="admintable" width="97%;" style=" margin-left:1.5%;">
	  <tr>
		  <th class="encabezadoPrincipal">#</th>
		  <th class="encabezadoPrincipal">Fecha</th>
		  <th class="encabezadoPrincipal">Comentarios</th>
		  <th class="encabezadoPrincipal">Status</th>
		  <th class="encabezadoPrincipal">Acciones</th>
	  </tr>';

	$i=1;
	$fecha=date('Y-m-d');
	 foreach ($seguimientos as $row)
	 {
		 $estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
			
		print'
			<tr '.$estilo.'>
				<td align="center" valign="middle">'.$i.'</td>
				<td align="center" valign="middle">'.$row->fecha.'</td>
				<td align="center" valign="middle">
					<a onclick="detallesSeguimiento('.$row->idSeguimiento.')" style="cursor:pointer" id="lblSeguimiento'.$i.'">'.
					substr($row->comentarios,0,50).'</a>
					<input type="hidden" value="'.$row->idSeguimiento.'" id="idSeguimiento'.$i.'" />
				</td>
				<td align="center" valign="middle">';
			  
				$sql="SELECT DATEDIFF('$row->fecha', '$fecha') AS diferencia "; #Obtener la diferencia de dias
				$query=$this->db->query($sql);
				
				$query=$query->row();
				
				if ($query->diferencia>3)
				{
					print('<img src="'.base_url().'img/GreenBall.png" width="25" />');
				}
				
				if ($query->diferencia==3 or $query->diferencia==2 or $query->diferencia==1)
				{
					print('<img src="'.base_url().'img/YellowBall.png" width="25" />');
				}
				
				if ($query->diferencia<1 )
				{
					print('<img src="'.base_url().'img/RedBall.png" width="25" />');
				}
					
			  echo'</td>
			  <td align="center" valign="middle" id="tdSeguimiendo'.$i.'">';
			  
			  if($permiso->escribir=='1')
			  { 
				  if($row->activo=="1")
				  {
					  echo '<input type="checkbox" id="chkSeguimiento'.$i.'" onchange="confirmarSeguimiento('.$i.')" title="Confirmar" />
					  <br />
					  <a>Confirmar</a>';
				  }
				  else
				  {
					  echo '<img src="'.base_url().'img/success.png" width="25" title="Confirmado" />';
				  }
			  }
			  echo'</td>
		   </tr>';
	 
		$i++;

    }//Foreach de Lista de Clientes de Contactos...
?>
</table> 


<?php
 }//Lista de Clientes
else
{
	print
	('<div class="Error_validar" style="margin-top:2px; width:67%; margin-left:2px; margin-bottom: 5px;">
       No hay registro de seguimientos.</div>');
}//Else
?>

<div id="ventanaSeguimiento" title="Seguimiento">
<div style="width:99%;" id="cargandoSeguimiento"></div>
<div id="errorSeguimiento" class="ui-state-error" ></div>
<table class="admintable" width="99%;">
    <tr>
    <td class="key">Fecha:</td>
    <td><input type="text" name="FechaDia" id="FechaDia" class="cajas" style="width:160px;" value=""  /> </td>
    </tr>
    
    <tr>
    <td class="key">Comentarios:</td>
    <td>
    	<textarea id="txtComentarios" name="txtComentarios" rows="3" cols="50" class="TextArea"></textarea>
    </td>
    </tr>
</table>
</div>

<div id="ventanaInformacionSeguimiento" title="Detalles de seguimiento">
<div id="errorInformacionSeguimiento" class="ui-state-error" ></div>
<div style="width:99%;" id="cargarSeguimiento"></div>
</div>

<div id="ventanaConfirmar" title="Confirmar seguimiento">
<div style="width:99%;" id="cargandoConfirmacion"></div>
<div id="errorConfirmacion" class="ui-state-error" ></div>
<table class="admintable" width="99%;">
    <tr>
        <td class="key">Observaciones:</td>
        <td>
            <textarea id="txtObservaciones" name="txtObservaciones" rows="3" cols="50" class="TextArea"></textarea>
        </td>
    </tr>
</table>
</div>



<!-- Termina listproyectos -->
</div>
<!-- Termina -->
</div>
