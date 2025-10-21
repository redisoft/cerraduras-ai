<link rel="stylesheet" href="<?php echo base_url()?>css/fileUpload/jquery.fileupload.css">
<link rel="stylesheet" href="<?php echo base_url()?>css/bootstrap/boot.css">

<script src="<?php echo base_url()?>js/bibliotecas/fileUpload/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/fileUpload/jquery.iframe-transport.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/fileUpload/jquery.fileupload.js"></script>

<script>
$(document).ready(function()
{
	$('#txtFechaSeguimiento').datepicker();
	$('#txtFechaCierre').datepicker()
	
	$("#txtHoraSeguimiento, #txtHoraCierre, #txtHoraCierreFin").timepicker({timeOnly: true});
	
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
	
	opcionesSeguimiento()
});
</script>
<form id="frmSeguimiento" name="frmSeguimiento">
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
				<td class="key">Promotor:</td>
				<td>
					<select id="selectUsuarioRegistro" name="selectUsuarioRegistro" class="cajas" style="width:300px" >
					<?php
					foreach($promotores as $row)
					{
						if($cliente->idPromotor==$row->idResponsable)
						{
							echo '<option '.($row->idResponsable==$cliente->idPromotor?'selected="selected"':'').' value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
						}
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
                            echo '<option '.($row->idStatus==4?'selected="selected"':'').' value="'.$row->idStatus.'|'.($row->idStatus<=4?$row->idStatus:$row->idStatusIgual).'">'.$row->nombre.'</option>';
                        }
                        ?>
                    </select>
                </div>
                
                 <!--<img onclick="obtenerCatalogoStatus()" src="<?php echo base_url()?>img/agregar.png" width="20" height="20" title="CRM" style="float:left" />-->
            </td>
        </tr>
        
         <tr>
            <td class="key">Estatus:</td>
            <td>
            	
                <input type="hidden" name="txtTipoEStatusCrm" id="txtTipoEStatusCrm" value="1" />
            	<div id="obtenerEstatusCrm" style="width:220px; float: left">
                    <select id="selectEstatus" name="selectEstatus" class="cajas" style="width:200px; ">
                    	<!--<option value="0">Seleccione</option>-->
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
            <td class="key">Fecha:</td>
            <td>
            	<?php
                if($idRol!=1) echo date('Y-m-d H:00');
			
				?>              
                <input type="text" name="txtFechaSeguimiento" id="txtFechaSeguimiento" class="cajas" style="width:100px; <?php echo $idRol!=1?'display:none':''?>" value="<?php echo date('Y-m-d')?>"  /> 
                
                <?php
                if($idRol==1) echo 'Hora';
			
				?>       
                
                <input type="text" name="txtHoraSeguimiento" id="txtHoraSeguimiento" class="cajas" style="width:40px;  <?php echo $idRol!=1?'display:none':''?>" value="<?php echo date('H:00')?>" readonly="readonly"  />
                
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
        
         <tr style="display:none">
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
        
        <tr style="display:none">
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
            <td class="key">Próximo contacto:</td>
            <td>  
            	Fecha                  
                <input type="text" name="txtFechaCierre" id="txtFechaCierre" class="cajas" style="width:100px;" value="<?php echo date('Y-m-d')?>"/> 
                
                <?php
                
				echo'
				&nbsp;
				 Entre
				<!--<input type="text" name="txtHoraCierre" id="txtHoraCierre" class="cajas" style="width:40px;"  readonly="readonly"  />-->
				
				<select id="txtHoraCierre" name="txtHoraCierre" class="cajas" style="width:65px;" onchange="sugerirHora()">';
				
				for($i=7;$i<=22;$i++)
				{
					for($m=0;$m<=55;$m+=5)
					{
						echo '<option>'.($i<10?'0'.$i:$i).':'.($m<10?'0'.$m:$m).'</option>';
					}
				}
					
				echo'
				</select>
				&nbsp;
				 y 
				<!--<input type="text" name="txtHoraCierreFin" id="txtHoraCierreFin" class="cajas" style="width:40px;"  readonly="readonly"  />-->
				
				<select id="txtHoraCierreFin" name="txtHoraCierreFin" class="cajas" style="width:65px;">';
				
				for($i=7;$i<=22;$i++)
				{
					for($m=0;$m<=55;$m+=5)
					{
						echo '<option>'.($i<10?'0'.$i:$i).':'.($m<10?'0'.$m:$m).'</option>';
					}
				}
				
				echo '<option>23:00</option>';
					
				echo'
				</select>';
				?>
                
                
                
                <!--&nbsp;
                Entre
                <input type="text" name="txtHoraCierre" id="txtHoraCierre" class="cajas" style="width:40px;" value="<?php echo date('H:00')?>" readonly="readonly"  />
                &nbsp;
                 y 
                <input type="text" name="txtHoraCierreFin" id="txtHoraCierreFin" class="cajas" style="width:40px;" value="<?php echo date('H:30')?>" readonly="readonly"  />-->
                
            </td>
        </tr>
        
        <tr>
            <td class="key">Alerta:</td>
            <td>  
                Crear alerta para este seguimiento <input  type="checkbox" id="chkAlertaSeguimiento" name="chkAlertaSeguimiento" value="1" />
                
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
        
        
        <tr id="filaArchivosSeguimiento" style="display:none">
            <td class="key">Archivos:</td>
            <td>
            	<input type="hidden" name="txtIdImagenes" id="txtIdImagenes" value="<?php echo $imagen?>" />
                
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Archivo(s)</span>
                    <input id="txtImportarFichero" type="file" name="files[]" multiple>
                </span>
                <br /><br />
    
                 <div id="progress" class="progress">
                    <div class="progress-bar progress-bar-success"></div>
                </div>
                <div id="files" class="files"></div>
            </td>
        </tr>
        
    </table>
</form>

<?php
echo '
<table class="tabsTabla admintable" width="100%" >
		<tr>
			<th colspan="2">Prospecto</th>
		</tr>
		<tr>
			<td class="key">Nombre:</td>
			<td align="left">       
				<input type="text" class="cajas"   value="'.$cliente->nombre.'" readonly="readonly" />
			</td>
		</tr>
		
		<tr>
			<td class="key">Teléfono:</td>
			<td align="left">       
				<input type="text" class="cajas"   value="'.trim($cliente->telefono.(strlen($cliente->movil)>0?' '.$cliente->movil:'')).'"  readonly="readonly"/>
			</td>
		</tr>
		
		<tr>
			<td class="key">Email:</td>
			<td align="left">       
				<input type="text" class="cajas" value="'.$cliente->email.'" readonly="readonly" />
			</td>
		</tr>
		
	</table>';
?>


<script src="<?php echo base_url()?>js/clientes/prospectos/archivos.js"></script>