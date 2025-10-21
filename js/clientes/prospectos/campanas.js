
function obtenerProgramasCampanaRegistro()
{
	if($('#selectCampanasEditar').val()=="0") return;
	
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			
		},
		type:"POST",
		url:base_url+'crm/obtenerProgramasCampanaRegistro',
		data:
		{
			"idCampana":		$('#selectCampana').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$('#obtenerProgramasRegistro').html(data)
		},
		error:function(datos)
		{
			
		}
	});		
}