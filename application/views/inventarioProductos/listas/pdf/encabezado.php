<htmlpageheader name="myHTMLHeader1">
	<?php
    echo'
	<div align="center">
		<table width="100%">
			<tr>
				<td width="20%">';
				
				if(file_exists('img/logos/'.$this->session->userdata('logotipo')) and strlen($this->session->userdata('logotipo'))>3)
				{
					echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 80px; max-height: 80px" />';
				}
					
				echo'
				</td>
				<td style="font-size:18px" align="center">DESCUENTOS DE PRODUCTOS - '.$lista->nombre.'</td>
			</tr>
			
			<tr>
				<td colspan="2" style="font-size:13px">';
				
				if($lista->vigencia=='0')
				{
					echo 'Fecha: '.obtenerFechaMesCorto($lista->fechaInicial);
				}
				else
				{
					echo 'Periodo: '.obtenerFechaMesCorto($lista->fechaInicial).' al '.obtenerFechaMesCorto($lista->fechaFinal);
				}
				
				echo'
				</td>
			</tr>
			
		</table>
	</div>';

	?>
    <table class="admintable" style="width:100%">
        <tr>
            <th width="6%" style="color:#000;">#</th>
            <th width="20%" style="color:#000;" >Codigo interno</th>
            <th width="35%" style="color:#000;" align="center">Producto</th>
            <th width="19%" style="color:#000;" align="center">Línea</th>
            <th width="10%" style="color:#000;" align="center">Precio</th>
            <th width="10%" style="color:#000;" align="center">Precio nuevo</th>    
        </tr>
    </table>
</htmlpageheader>

<htmlpageheader name="myHTMLHeader1Even">
	<?php
    echo'
	<div align="center">
		<table width="100%">
			<tr>
				<td width="20%">';
				
				if(file_exists('img/logos/'.$this->session->userdata('logotipo')) and strlen($this->session->userdata('logotipo'))>3)
				{
					echo '<img src="'.base_url().'img/logos/'.$this->session->userdata('logotipo').'" style="max-width: 80px; max-height: 80px" />';
				}
					
				echo'
				</td>
				<td style="font-size:18px" align="center">DESCUENTOS DE PRODUCTOS - '.$lista->nombre.'</td>
			</tr>
			
			<tr>
				<td colspan="2" style="font-size:13px">';
				
				if($lista->vigencia=='0')
				{
					echo 'Fecha: '.obtenerFechaMesCorto($lista->fechaInicial);
				}
				else
				{
					echo 'Periodo: '.obtenerFechaMesCorto($lista->fechaInicial).' al '.obtenerFechaMesCorto($lista->fechaFinal);
				}
				
				echo'
				</td>
			</tr>
			
		</table>
	</div>';

	?>
    <table class="admintable" style="width:100%">
        <tr>
            <th width="6%" style="color:#000;">#</th>
            <th width="20%" style="color:#000;" >Codigo interno</th>
            <th width="35%" style="color:#000;" align="center">Producto</th>
            <th width="19%" style="color:#000;" align="center">Línea</th>
            <th width="10%" style="color:#000;" align="center">Precio</th>
            <th width="10%" style="color:#000;" align="center">Precio nuevo</th>    
        </tr>
    </table>
</htmlpageheader>

mpdf-->
<!-- set the headers/footers - they will occur from here on in the document -->
<!--mpdf
<sethtmlpageheader name="myHTMLHeader1" page="O" value="on" show-this-page="1" />
<sethtmlpageheader name="myHTMLHeader1Even" page="E" value="on" />
mpdf-->
