
$(document).ready(function()
{
	$("#ventanaAdeudoPendiente").dialog(
	{
		autoOpen:false,
		height:320,
		width:700,
		modal:true,
		resizable:false,
		buttons: 
		{
			'Aceptar': function() 
			{
				$(this).dialog('close');	
			},
		},
		close: function() 
		{
		}
	});
	
});
