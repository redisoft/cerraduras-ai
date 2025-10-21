<script src="<?php echo base_url()?>js/sie/creditos/grafica.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/bundle.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/utils.js"></script>
<script src="<?php echo base_url()?>js/apexcharts.js"></script>
<link href="<?php echo base_url()?>css/apexcharts.css" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function()
{
	obtenerGraficaCreditos(); 
});
	
</script>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div id="obtenerGraficaCreditos"></div>
    </div>      
    <div class="col-md-3"></div>
</div>

<div class="modal fade" id="ventanaCreditosDetalles" style="overflow-y: auto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Créditos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center" id="obtenerCreditosDetalles">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
            </div>
            
        </div>
    </div>
</div>


