<script>
$(document).ready(function()
{
	$("#txtNombre").autocomplete(
	{
		source:base_url+'configuracion/obtenerTipoCliente',
		
		select:function( event, ui)
		{
			notify("El <?php echo $this->session->userdata('identificador')?> ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtNombre").reset();
		}
	});
});
</script>

<table class="admintable" width="100%;">
	<tr>
        <td class="key">Descripción:</td>
        <td>
            <input name="txtNombre" id="txtDescripcion" type="text" class="cajas" style="width:300px" />
        </td>
	</tr>	
</table>