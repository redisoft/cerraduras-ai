<script src="<?php echo base_url()?>js/reportes/corteDiario.js?v=<?php echo(rand());?>"></script>
<form id="frmEnvios" action="<?=base_url()?>reportes/ticketEnvios" target="_blank" method="post">
<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar">

	<table class="toolbar" width="100%">
        <tr>
            <td>
				<input name="FechaCorteDiario" type="text" title="Fecha" style="width:150px" id="FechaCorteDiario" class="busquedas" value="<?=date('Y-m-d')?>" onchange="obtenerReporte()" />
            </td>
        </tr>
    </table>
</div>
</div>

<div class="listproyectos" style="margin-top:25px">
	<div id="generandoReporte"></div>
	
	<div id="obtenerReporte">
		<input type="hidden" id="selectRutas" value="0" />
	</div>

	</div>
</div>


<div id="ticketReporte" style="display: none"></div>

</form>

