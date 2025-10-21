
<script>
	$(document).ready(function()
	{
		$('#txtFechaSeguimiento').datepicker();
		$('#txtFechaCierre').datetimepicker()
		
		$("#txtHoraSeguimiento").timepicker({timeOnly: true});
	});
</script>

<table class="admintable" width="100%;">
    <tr>
        <td class="key">Fecha:</td>
        <td>                           <!-- FECHA CON HORA -->
        	<input type="text" name="txtFechaSeguimiento" id="txtFechaSeguimiento" class="cajas" style="width:100px;" value="<?php echo date('Y-m-d')?>"  /> 
            
            Hora
			<input type="text" name="txtHoraSeguimiento" id="txtHoraSeguimiento" class="cajas" style="width:40px;" value="<?php echo date('H:00')?>" readonly="readonly"  />
            
        </td>
    </tr>
    
    <tr>
        <td class="key">Status:</td>
        <td>
        	<select id="selectStatus" name="selectStatus" class="cajas" onchange="opcionesSeguimiento()">
			<?php
            foreach($status as $row)
            {
                echo '<option value="'.$row->idStatus.'">'.$row->nombre.'</option>';
            }
            ?>
            </select>
        </td>
    </tr>
    
    <tr id="filaServicio">
        <td class="key">Servicio:</td>
        <td>
        	<select id="selectServicio" name="selectServicio" class="cajas">
			<?php
            foreach($servicios as $row)
            {
                echo '<option value="'.$row->idServicio.'">'.$row->nombre.'</option>';
            }
            ?>
            </select>
        </td>
    </tr>
    
     <tr>
        <td class="key">Responsable:</td>
        <td>
        	<select id="selectResponsable" name="selectResponsable" class="cajas" style="width:300px">
			<?php
            foreach($responsables as $row)
            {
                echo '<option value="'.$row->idResponsable.'">'.$row->nombre.'</option>';
            }
            ?>
            </select>
        </td>
    </tr>
    
     <tr id="filaLugar">
        <td class="key">Lugar:</td>
        <td>
            <input  type="text" id="txtLugar" name="txtLugar" rows="3" style="width:300px"class="cajas" />
        </td>
    </tr>
    
    <tr id="filaMonto" ice:repeating="true">
        <td class="key">Monto:</td>
        <td>                          
        	<input type="text" name="txtMonto" id="txtMonto" value="0" class="cajas" style="width:160px;" /> 
        </td>
    </tr>
    
    <tr id="filaCierre">
        <td class="key">Fecha cierre:</td>
        <td>                           
        	<input type="text" name="txtFechaCierre" id="txtFechaCierre" class="cajas" style="width:160px;" /> 
        </td>
    </tr>
    
    <tr>
        <td class="key">Comentarios:</td>
        <td>
            <textarea id="txtComentarios" name="txtComentarios" rows="3" style="width:300px"class="TextArea"></textarea>
        </td>
    </tr>
</table>
