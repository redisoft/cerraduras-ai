    <?php
	if($factura->cancelada==1)
	{
		echo '  <div class="alertasGeneral" align="center">El CFDI se encuentra cancelado</div>';
	}
    ?>

     <!--hr /-->
    <div align="center" style="font-size:9px" >
    Este documento es una representación impresa de un CFDI 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    Este comprobante fue generado por Redisoftsystems
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <?php echo '<label>Página '.'{PAGENO}/{nb}'.'</label>';?>
    </div>