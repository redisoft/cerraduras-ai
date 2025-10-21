<script src="<?php echo base_url()?>js/sie/calendario/calendario.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/bundle.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/utils.js"></script>

<script>
$(document).ready(function()
{
	obtenerCalendarioPagos('',''); 
	//$('#txtMes').monthpicker({changeYear:true});
});
	
</script>

<div class="row">
	<div class="col-md-5"></div>
    <div class="col-md-2 text-center">
    	Mes<br />
        <input type="text" class="form-control" id="txtMes" value="<?=date('Y-m-m')?>" onchange="obtenerCalendarioPagos('','')" readonly="readonly" />
    </div>
    <div class="col-md-5"></div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <div  id="obtenerCalendarioPagos"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ventanaDetallesCalendario" style="overflow-y: auto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Calendario de pagos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center" id="obtenerDetallesCalendario">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
            </div>
            
        </div>
    </div>
</div>

