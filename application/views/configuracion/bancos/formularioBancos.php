<?php
echo'
<script>
$(document).ready(function()
{
	$("#txtNombre").autocomplete(
	{
		source:"'.base_url().'configuracion/obtenerBancosRepetidos",
		
		select:function( event, ui)
		{
			notify("El banco ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtNombre").reset();
		}
	});
});
</script>
<form id="frmBancos" name="frmBancos" method="post" action="'.base_url().'bancos/registrarBanco" enctype="multipart/form-data">
	<table class="admintable" width="100%">
		<tr>
		<td class="key">Nombre:</td>
		<td>
			<input type="text" class="cajasNormales" id="txtNombre" name="txtNombre" />
		</td>
		</tr>	
		<tr style="display:none">
			<td class="key">Cliente:</td>
			<td>
				<input type="text" class="cajasNormales" id="txtCliente" style="width:500px" />
				<input type="hidden" id="txtIdCliente" name="txtIdCliente" value="0" />
				<script>
				$(document).ready(function()
				{
					$("#txtCliente").autocomplete(
					{
						source:"'.base_url().'configuracion/obtenerClientes",
						
						select:function( event, ui)
						{
							$("#txtIdCliente").val(ui.item.idCliente)
						}
					});
				});
				</script>
			</td>
		</tr>	
		<tr style="display:none">
			<td class="key">Logotipo:</td>
			<td>
				<input type="file" class="cajasNormales" id="txtLogotipo" name="txtLogotipo" style="height:24px" />
			</td>
		</tr>
	</table>
</form>';