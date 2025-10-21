<?php
if($emisor!=null)
{
	echo $emisor->serie.$folio.'<input type="hidden" id="txtFolioActual" name="txtFolioActual" value="'.$emisor->serie.$folio.'">';
}
else
{
	echo 'Seleccione el emisor';
}
?>