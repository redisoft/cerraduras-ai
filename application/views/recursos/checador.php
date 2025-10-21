<script src="<?php echo base_url()?>js/administracion/reloj.js"></script>

<div class="derecha">
    <div class="submenu">
    <div class="toolbar" id="toolbar">
    
    <div class="seccionDiv">
        Checador
    </div>
 	
    <table class="toolbar" width="100%" >
        <tr>
            <td width="100%" style="text-align:center">
            	<br />
            	<span id="liveclock" style="width:200px"></span>
                <br />
                <input  type="text" class="cajas" id="txtBuscarPersonal" style="width:200px; margin-top:20px" placeholder="Número de personal" /> 
            </td>
        </tr>
	</table>
</div>
</div>
<div class="listproyectos">
	
	<div id="obtenerAsistencias"></div>
    
    <div id="ventanaEntradasSalidas" title="Entradas y salidas de personal">
    <div align="center"><span id="relojDigital"></span></div>
    <div id="registrandoChequeo"></div>
    <div style="margin-top:4px" id="cargarInformacionEntrada"></div>
    </div>

</div>
</div>

