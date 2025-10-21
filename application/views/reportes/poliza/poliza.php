<div style="width:746px; background-color:#FFF; height:auto; padding-left:35px; padding-right:35px">
<div style="border-radius: 15px; border: solid 2px #000; width:746px; height:150px;">
    <div class="divTitulosGrandes">
    	POLIZA DE CHEQUE
    </div>
	<div style="font-size:16px" align="center">
    <?php
	 	echo obtenerNombreFecha($egreso->fecha).'<br />';
		echo '$'.number_format($egreso->pago,2).'<br />';
		echo $cantidadLetras.' MXN<br />';
		echo 'Número de cheque: '.$egreso->cheque.'<br />';
		echo 'Páguese este documento a: '.$nombre->nombre.'<br />';
     ?>
     </div>
</div>

<div style="width:100%; margin-top:10px">
<div style="border-radius: 17px; border: solid 2px #000; width:500px; height:60px; float:left">
	<div class="divTitulos">
		CONCEPTO DEL PAGO
     </div>
    <center style="font-size:16px">
     <?php
	 	echo '&nbsp;&nbsp;&nbsp;'.$egreso->producto;
     ?>
     </center>
</div>

<div style="border-radius: 15px; border: solid 2px #000; width:200px; height:60px; float:right">
	<div class="divTitulos">
    	FIRMA CHEQUE RECIBIDO
    </div>
</div>
</div>

<div style="font-size:8px; margin-top:10px; margin-bottom:10px">
	<strong>DISTRIBUCIÓN</strong> CHEQUE.- BENFICIARIO COPIA COLOR.- ARCHIVO  CON COMPROBANTES.- COPIA BLANCA ARCHIVO NUMERICO.-
    CONTABILIDAD CONCILIACIONES BANCARIAS
</div>

<div style="border: solid 2px #000; width:746px; height:250px; border-bottom:none; border-left:none; border-right:none">
	<table style="width:100%" class="admintable">
    	<tr>
            <th width="10%" style="border-left:solid 2px #000">CUENTA</th>
            <th width="15%">SUB-CUENTA</th>
            <th width="30%">NOMBRE</th>
            <th width="15%">PARCIAL</th>
            <th width="15%">DEBE</th>
            <th width="15%" style="border-right:solid 2px #000; ">HABER</th>
        </tr>
        
        <?php
		for($i=1;$i<8;$i++)
		{	
			$estilo=$i%2>0?'  ':' class="sombreado"';
			/*$abajoI='';
			$abajoD='';
			
			if($i==8)
			{
				$abajoI='; border-bottom-left-radius:15px; ';
				$abajoD='style="border-bottom-right-radius:15px;"';
				
			}*/
			
			echo '
			<tr '.$estilo.'> 
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td style="border-right: solid 2px #000"></td>
			</tr>';
		}
		
		#border-bottom-left-radius:15px;
		echo '
		<tr class="sombreado"> 
			<td style=" border-bottom: solid 2px #000; "></td>
			<td style=" border-bottom: solid 2px #000"></td>
			<td style=" border-bottom: solid 2px #000"></td>
			<td style=" border-bottom: solid 2px #000"></td>
			<td style=" border-bottom: solid 2px #000"></td>
			<td style=" border-bottom: solid 2px #000; border-right: solid 2px #000"></td>
		</tr>';
        
		?>
        <tr>
        <td style="border:none; background-color: #FFF; height:18px" colspan="3"></td>
        <td style="border:none; background-color: #FFF; font-size:12px">SUMAS IGUALES</td>
        <td style="border: solid 2px #000">
        	
        </td>
            <td style="border: solid 2px #000">
            </td>
        </tr>
    </table>
</div>

<div style="border-radius: 15px; border: solid 2px #000; width:746px; height:50px; margin-top:20px">
    <table style="width:100%" class="admintable">
    	<tr>
            <th style="border-top-left-radius:15px">HECHO POR</th>
            <th>REVISADO</th>
            <th>AUTORIZADO</th>
            <th>AUXILIARES</th>
            <th>DIARIO</th>
            <th style="border-top-right-radius:15px">PÓLIZA No.</th>
        </tr>
        
        <?php
		echo '
		<tr>
			<td style="height:18px; border-bottom-left-radius:15px; border: none"></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style="border-bottom-right-radius:15px"></td>
		</tr>';
        ?>
        </table>
</div>

</div>