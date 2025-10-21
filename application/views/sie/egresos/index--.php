<script src="<?php echo base_url()?>js/sie/egresos/egresos.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/bundle.js"></script>
<script src="<?php echo base_url()?>js/apexcharts.js"></script>
<link href="<?php echo base_url()?>css/apexcharts.css" rel="stylesheet" type="text/css" />
<!--<script src="<?php echo base_url()?>js/bibliotecas/chartjs/utils.js"></script>-->

<script>
$(document).ready(function()
{
	obtenerGraficaEgresos(); 
	$('#txtInicio,#txtFin').daterangepicker(
	{
		singleDatePicker: true,
		locale: 
		{
		  format: 'YYYY-MM-DD'
		}
	});
});
	
</script>

<div class="row">
	<div class="col-md-3"></div>
    <div class="col-md-3 text-center">
    	De<br />
        <input type="text" class="form-control" id="txtInicio" value="<?=date('Y-m-d')?>" onchange="obtenerGraficaEgresos()" readonly="readonly" />
    </div>
    <div class="col-md-3 text-center">
    	a<br />
		<input type="text" class="form-control" id="txtFin" value="<?=date('Y-m-d')?>" onchange="obtenerGraficaEgresos()"  readonly="readonly"/>
    </div>
    <div class="col-md-3"></div>
</div>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                
                <div class="text-center">
                    <!--<div id="simple-pie" class="apex-charts"></div>-->
                    <div id="obtenerGraficaEgresos"></div>
                </div>
            </div>
        </div>
    </div>      
    <div class="col-md-3"></div>
</div>

<div class="modal fade" id="ventanaDetallesEgresos" style="overflow-y: auto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Egresos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center" id="obtenerDetallesEgresosConceptos">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
            </div>
            
        </div>
    </div>
</div>

