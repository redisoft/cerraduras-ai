function sugerirFirma(idUsuario)
{
	$('#txtFirma').val($('#txtFirma'+idUsuario).val());
}

function sugerirEmailContacto(email)
{
	$('#correo').val(email);
}