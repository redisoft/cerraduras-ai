<script>
$(document).ready(function()
{
	$('#txtFechaSeguimiento').datepicker();
	$('#txtFechaCierre').datepicker()
	
	$("#txtHoraSeguimiento, #txtHoraCierre").timepicker({timeOnly: true});
	
	sugerirCorreo();
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
    	<tr>
            <td class="key">CRM:</td>
            <td>
            	
                <input type="hidden" name="txtTipoStatusCrm" id="txtTipoStatusCrm" value="1" />
            	<div id="obtenerStatusCrm" style="width:220px; float: left">
                    <select id="selectStatus" name="selectStatus" class="cajas" style="width:200px; " onchange="opcionesSeguimiento()">
                        <?php
                        foreach($status as $row)
                        {
							if($row->idStatusIgual!=4 and $row->idStatusIgual!=3)
							{
                            	echo '<option value="'.$row->idStatus.'">'.$row->nombre.'</option>';
							}
                        }
                        ?>
                    </select>
                </div>
                	
                 <!--<img onclick="obtenerCatalogoStatus()" src="<?php echo base_url()?>img/agregar.png" width="20" height="20" title="Status" style="float:left" />-->
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
            	Compras
            </td>
        </tr>
        
         <tr id="filaContacto">
            <td class="key">Contacto:</td>
            <td>
                <select id="selectContactos" name="selectContactos" class="cajas" style="width:300px">
                	<option value="0">Seleccione</option>
                <?php
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
        
        <!--<tr id="filaBitacora" style="display:none">
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
        </tr>-->
    </table>
</form>
