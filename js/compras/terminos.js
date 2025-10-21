$(document).ready(function()
{	
	$("#ventanaTerminos").dialog(
	{
		autoOpen:false,
		show: { effect: "scale", duration: 400 },
		height:200,
		width:500,
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
	})
})