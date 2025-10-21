<script>
$(document).ready(function()
{
	$('#txtFechaSeguimiento').datepicker();
	$('#txtFechaCierre').datepicker()
	
	$("#txtHoraSeguimiento, #txtHoraCierre").timepicker({timeOnly: true});
	
	sugerirCorreo();
	
	$("#txtBuscarCotizacionCrm").autocomplete(
	{
		source:base_url+'configuracion/obtenerListaCotizaciones/<?php echo $idCliente?>',
		
		select:function( event, ui)
		{
			$('#txtIdCotizacionCrm').val(ui.item.idCotizacion);
		}
	});
	
	$("#txtBuscarVentaCrm").autocomplete(
	{
		source:base_url+'configuracion/obtenerListaVentas/<?php echo $idCliente?>',
		
		select:function( event, ui)
		{
			$('#txtIdVentaCrm').val(ui.item.idCotizacion);
		}
	});
});
</script>
<form id="frmSeguimiento" name="frmSeguimiento">
	<input  type="checkbox" id="chkAlertaSeguimiento" name="chkAlertaSeguimiento" value="1" style="display:none" />
    
	<div id="enviandoBitacora"></div>
    <table class="admintable" width="100%;">
    	<tr>
            <td class="key">Folio:</td>
            <td>
                <?php echo $folio?>
            </td>
        </tr>
        
        <?php
        if(sistemaActivo=='IEXE')
		{
			?>
			
		   <tr>
				<td class="key">Usuario:</td>
				<td>
					<select id="selectUsuarioRegistro" name="selectUsuarioRegistro" class="cajas" style="width:300px" >
					<?php
					foreach($responsables as $row)
					{
						echo '<option value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
					}
					?>
					</select>
				</td>
			</tr>
			<?php
		}
		?>
        
        
        <tr>
            <td class="key">CRM:</td>
            <td>
            	
                <input type="hidden" name="txtTipoStatusCrm" id="txtTipoStatusCrm" value="1" />
            	<div id="obtenerStatusCrm" style="width:220px; float: left">
                    <select id="selectStatus" name="selectStatus" class="cajas" style="width:200px; " onchange="opcionesSeguimiento()">
                        <?php
                        foreach($status as $row)
                        {
                            echo '<option value="'.$row->idStatus.'|'.($row->idStatus<=4?$row->idStatus:$row->idStatusIgual).'">'.$row->nombre.'</option>';
                        }
                        ?>
                    </select>
                </div>
                
                 <img onclick="obtenerCatalogoStatus()" src="<?php echo base_url()?>img/agregar.png" width="20" height="20" title="CRM" style="float:left" />
            </td>
        </tr>
        
         <tr>
            <td class="key">Estatus:</td>
            <td>
            	
                <input type="hidden" name="txtTipoEStatusCrm" id="txtTipoEStatusCrm" value="1" />
            	<div id="obtenerEstatusCrm" style="width:220px; float: left">
                    <select id="selectEstatus" name="selectEstatus" class="cajas" style="width:200px; ">
                    	<option value="0">Seleccione</option>
                        <?php
                        foreach($estatus as $row)
                        {
                            echo '<option value="'.$row->idEstatus.'">'.$row->nombre.'</option>';
                        }
                        ?>
                    </select>
                </div>
                
                 <img onclick="obtenerCatalogoEstatus()" src="<?php echo base_url()?>img/agregar.png" width="20" height="20" title="Estatus" style="float:left" />
            </td>
        </tr>
        
        <tr>
            <td class="key">Área:</td>
            <td>
                <select id="selectAreas" name="selectAreas" class="cajas" style="width:300px; " onchange="obtenerConceptosArea()">
                    <?php
                    foreach($areas as $row)
                    {
                        echo '<option  value="'.$row->idArea.'">'.$row->nombre.'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Concepto:</td>
            <td id="obtenerConceptosArea">
                <select id="selectConcepto" name="selectConcepto" class="cajas" style="width:300px; " >
                    <?php
                    foreach($conceptos as $row)
                    {
                        echo '<option  value="'.$row->idConcepto.'">'.$row->nombre.'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        
         <tr>
            <td class="key">Fecha:</td>
            <td>                  
                <input type="text" name="txtFechaSeguimiento" id="txtFechaSeguimiento" class="cajas" style="width:100px;" value="<?php echo date('Y-m-d')?>"  /> 
                
                Hora
                <input type="text" name="txtHoraSeguimiento" id="txtHoraSeguimiento" class="cajas" style="width:40px;" value="<?php echo date('H:00')?>" readonly="readonly"  />
                
            </td>
        </tr>
        
        <tr id="filaServicio">
            <td class="key">Servicio:</td>
            <td>
            	<input type="hidden" name="txtTipoServicioCrm" id="txtTipoServicioCrm" value="1" />
                 
            	<div id="obtenerServiciosCrm" style="width:220px; float: left">
                    <select id="selectServicio" name="selectServicio" class="cajas" style="width:200px; " onchange="opcionesServicios()">
                    <?php
                    foreach($servicios as $row)
                    {
                        echo '<option value="'.$row->idServicio.'">'.$row->nombre.'</option>';
                    }
                    ?>
                    </select>
                </div>
                
                <img onclick="obtenerCatalogoServicios()" src="<?php echo base_url()?>img/agregar.png" width="20" height="20" title="Servicios" style="float:left" />
                
                 <input type="text" name="txtBuscarCotizacionCrm" id="txtBuscarCotizacionCrm" placeholder="Seleccione cotización" class="cajas" style="width:300px"/>
                 <input type="text" name="txtBuscarVentaCrm" id="txtBuscarVentaCrm" placeholder="Seleccione venta" class="cajas" style="width:300px; display:none"/>
                 <input type="text" name="txtBuscarCompraCrm" id="txtBuscarCompraCrm" placeholder="Seleccione compra" class="cajas" style="width:300px; display:none"/>
                 
                 <input type="hidden" name="txtIdCotizacionCrm" id="txtIdCotizacionCrm" value="0" />
                 <input type="hidden" name="txtIdVentaCrm" 	id="txtIdVentaCrm" 	value="0" />
            </td>
        </tr>
        
         <tr id="filaContacto">
            <td class="key">Contacto:</td>
            <td>
                <select id="selectContactos" name="selectContactos" class="cajas" style="width:300px">
                	
                <?php
				
				if($contactos==null)
				{
					echo '<option value="0">Seleccione</option>';
				}
                foreach($contactos as $row)
                {
                    echo '<option value="'.$row->idContacto.'">Nombre: '.$row->nombre.', Teléfono: '.$row->telefono.', Email: '.$row->email.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        
         <tr>
            <td class="key">Responsable:</td>
            <td>
                <select id="selectResponsable" name="selectResponsable" class="cajas" style="width:300px" onchange="sugerirCorreo()">
                <?php
                foreach($responsables as $row)
                {
                    echo '<option value="'.$row->idResponsable.'|'.$row->correo.'">'.$row->nombre.'</option>';
                }
                ?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Email:</td>
            <td>
                <input  type="text" id="txtEmailSeguimiento" name="txtEmailSeguimiento" style="width:300px" class="cajas" />
            </td>
        </tr>
        
         <tr id="filaLugar">
            <td class="key">Lugar:</td>
            <td>
                <input  type="text" id="txtLugar" name="txtLugar" rows="3" style="width:300px"class="cajas" />
            </td>
        </tr>
    
        <tr id="filaCierre">
            <td class="key">Seguimiento:</td>
            <td>                    
                       
                <input type="text" name="txtFechaCierre" id="txtFechaCierre" class="cajas" style="width:100px;" value="<?php echo date('Y-m-d')?>"/> 
                
                Hora
                <input type="text" name="txtHoraCierre" id="txtHoraCierre" class="cajas" style="width:40px;" value="<?php echo date('H:00')?>" readonly="readonly"  />
                
            </td>
        </tr>
        
        <tr id="filaRecordatorio">
            <td class="key">Recordatorio:</td>
            <td>                    
                       
                <select id="selectTiempo" name="selectTiempo" class="cajas" style="width:100px">
                	<option value="0">Seleccione</option>
                    <?php
                    foreach($tiempos as $row)
					{
						echo '<option value="'.$row->idTiempo.'">'.$row->nombre.'</option>';
					}
					?>
                </select>
                
            </td>
        </tr>
        
        <tr id="filaComentarios">
            <td class="key">Comentarios:</td>
            <td>
                <textarea id="txtComentarios" name="txtComentarios" rows="3" style="width:300px"class="TextArea"></textarea>
            </td>
        </tr>
        
        <tr id="filaBitacora" style="display:none">
            <td class="key">Bitácora:</td>
            <td>
                <textarea id="txtBitacora" name="txtBitacora" rows="3" style="width:300px"class="TextArea"></textarea>
            </td>
        </tr>
        
        <tr id="filaEnviarBitacora" style="display:none">
            <td class="key">Enviar:</td>
            <td>
                <img src="<?php echo base_url()?>img/correo.png" title="enviar" onclick="enviarBitacora()" width="24" height="22" />
            </td>
        </tr>
    </table>
</form>
