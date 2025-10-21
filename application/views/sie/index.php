<script src="<?php echo base_url()?>js/sie/informacionFinanciera.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/bundle.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/utils.js"></script> 
<script src="<?php echo base_url()?>js/apexcharts.js"></script>
<link href="<?php echo base_url()?>css/apexcharts.css" rel="stylesheet" type="text/css" />

<input type="hidden" id="txtIdEscenario" value="<?=$idEscenario?>" />

<div class="row">
	<div class="col-md-4"></div>
    <div class="col-md-4" ><input type="text" class="form-control date" style="cursor:pointer" id="txtFecha"  value="<?php echo date('Y-m-d')?>" onchange="obtenerInformacionFinanciera()" readonly="readonly" /></div>
    <div class="col-md-4"></div>
    
   <!-- <input type="text" class="form-control date" id="fechaPerra">-->
</div>


<div id="generandoExcel"></div>


<div id="obtenerInformacionFinanciera">
</div>


<!--<div id="ventanaGraficaSaldosFecha" title="Saldos">
    <div id="obtenerGraficaSaldosFecha"></div>
</div>-->


<div class="modal fade" style="overflow-y: auto" id="ventanaGraficaSaldosFecha" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Saldos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center" id="obtenerGraficaSaldosFecha">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
            </div>
            
        </div>
    </div>
</div>


<div class="modal fade" style="overflow-y: auto" id="ventanaDetallesSaldosFecha" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Detalles de saldos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center" id="obtenerDetalleSaldoFecha">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
            </div>
            
        </div>
    </div>
</div>


