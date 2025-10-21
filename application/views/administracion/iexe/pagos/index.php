<script src="<?php echo base_url()?>js/clientes/prospectos/inscritos.js"></script>


<div class="derecha">
<div class="submenu" style="height:0px">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar" >
	<?php
    
	echo '
	<table class="toolbar" width="100%">
        <tr>
			<td class="button" width="30%"  style="cursor: pointer">
				<a id="bntRegistrarCliente" onclick="excelReporte()" >
					<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Exportar"/><br />
			   		Exportar
				</a> 
				
				<a href="'.base_url().'crm/reportes">
					<img src="'.base_url().'img/general.png" width="30px;" height="30px;" title="Promotores"/><br />
			   		General
				</a>  
				
				<a href="'.base_url().'crm/reportePromotores">
					<img src="'.base_url().'img/promotores.png" width="30px;" height="30px;" title="Promotores"/><br />
			   		Inscritos
				</a>
				
				<a href="'.base_url().'crm/reporteBajas">
					<img src="'.base_url().'img/bajas.png" width="30px;" height="30px;" title="Bajas"/><br />
			   		Bajas
				</a>     
				
				
				<a id="btnAtrasos" onclick="obtenerAtrasos()">
					<img src="'.base_url().'img/atrasos.png" width="30px;" height="30px;" title="Atrasos"  /><br />
					Atrasos  
				</a>         
				
				<a href="'.base_url().'crm/reporteProspectos">
					<img src="'.base_url().'img/prospectos.png" width="30px;" height="30px;" title="Prospectos"  /><br />
					Prospectos  
				</a>    
				
				<a href="'.base_url().'crm/inscritos">
					<img src="'.base_url().'img/inscrito.png" width="30px;" height="30px;" title="Prospectos"  /><br />
					Vel. Inscritos  
				</a>      
				   
			</td>
			
            <td width="70%" align="right" valign="middle" style="text-align:right" >
				
				<form action="javascript:fo()">
            		<input type="text"  name="txtBuscarProspecto" id="txtBuscarProspecto" class="busquedas" placeholder="Buscar por prospecto, email, teléfono" style="width:730px" />
				</form>
        	</td>  
        </tr>
	</table> ';
	
	if($permiso[15]->activo==0)
	{
		 echo '
		<script>
			desactivarBotonSistema(\'btnAtrasos\');
		</script>';
	}
	
	?>
</div>       
</div>
<br />	
<br />	
<br />       
<div class="listproyectos">
	
    
    <?php
    echo '
	<div style="width:100%; height: 102px;">

	<ul class="menuTabs">
		<div><li style="width: 150px" onclick="window.location.href=\''.base_url().'clientes/prospectos/prospectos\'" >Prospectos</li></div>
		<div><li style="width: 150px" onclick="window.location.href=\''.base_url().'clientes/prospectos/seguimientos\'" >Seguimientos</li> </div>
		<div>
			<li style="width: 150px" onclick="window.location.href=\''.base_url().'clientes/seguimientosDiarios\'" >Seguimiento diario</li> 
		</div>
		<div><li id="btnNuevoTab" style="width: 150px"  onclick="window.location.href=\''.base_url().'crm/nuevos\'" > Nuevos</li> </div>
		<div><li style="width: 150px" onclick="window.location.href=\''.base_url().'crm/atrasos\'" >Atrasos</li> </div>
		<div><li style="width: 150px" onclick="window.location.href=\''.base_url().'clientes/bajas\'">Bajas</li> </div>
		<div><li id="btnPromotoresTab" style="width: 150px" onclick="window.location.href=\''.base_url().'configuracion/promotores\'">Promotores</li> </div>
		
		<div><li style="width: 150px"  onclick="window.location.href=\''.base_url().'crm/reportes\'" class="activado">Reportes</li> </div>
		
	</ul>
	</div>';

	?>
    
    <input type="hidden"  name="txtCriterioAtrasosEditar" id="txtCriterioAtrasosEditar" value="no"/>
    
    <div id="exportandoReporte"></div>
    
    <div id="obtenerReporte">
    	<input type="hidden" id="selectFuentesBusqueda" value="0" />
        <input type="hidden" id="selectProgramaBusqueda" value="0" />
        <input type="hidden" id="selectCampanasBusqueda" value="0" />
        <input type="hidden" id="selectProspectoBusqueda" value="-1" />
        <input type="hidden" id="selectPromotorBusqueda" value="0" />
    </div>

    </div>
    
    <?php
    $this->load->view('clientes/prospectos/atrasos/modalAtrasos');
	?>

    
</div>

