
    
<?php
require_once('application/libraries/TreeMenu.php');
$icon 		= 'folder.gif';

// Menu01 -- This is from the original example
$menu01  	= new HTML_TreeMenu("arbolCuentas", base_url().'images', '_self');

$cuentas 		= new HTML_TreeNode("CUENTAS", "", $icon);

#$nivel1   		= &$cuentas->addItem(new HTML_TreeNode("NIVEL 1", "test.php", $icon));

foreach($primerNivel as $row)
{
	$nivel1   		= &$cuentas->addItem(new HTML_TreeNode($row->nombre, "", $icon));
	
	foreach($segundoNivel as $segundo)
	{
		if($segundo->idCuenta==$row->idCuenta)
		{
			$nivel2   		= &$nivel1->addItem(new HTML_TreeNode('<label onclick="formularioNivel3('.$segundo->idSubCuenta.')">'.$segundo->nombre.'</label>', "", $icon));
			
			$tercerNivel	= $this->cuentas->obtenerNivel3($segundo->idSubCuenta);
			
			foreach($tercerNivel as $tercero)
			{
				$nivel3 		= &$nivel2->addItem(new HTML_TreeNode('<label onclick="formularioNivel4('.$tercero->idSubCuenta3.')">'.$tercero->nombre.'</label>', "", $icon));
				$cuartoNivel	= $this->cuentas->obtenerNivel4($tercero->idSubCuenta3);
			
				foreach($cuartoNivel as $cuarto)
				{
					$nivel4 		= &$nivel3->addItem(new HTML_TreeNode($cuarto->nombre, "test.php", $icon));
					$quintoNivel	= $this->cuentas->obtenerNivel5($cuarto->idSubCuenta4);
				
					foreach($quintoNivel as $quinto)
					{
						$nivel5 		= &$nivel4->addItem(new HTML_TreeNode($quinto->nombre, "test.php", $icon));
						
						$sextoNivel	= $this->cuentas->obtenerNivel6($quinto->idSubCuenta5);
				
						foreach($sextoNivel as $sexto)
						{
							$nivel6 		= &$nivel5->addItem(new HTML_TreeNode($sexto->nombre, "test.php", $icon));
						}
					}
				}
			}
		}
	}
}



/*$nivel1   		= &$cuentas->addItem(new HTML_TreeNode("NIVEL 1", "test.php", $icon));
$nivel2   		= &$nivel1->addItem(new HTML_TreeNode("NIVEL 2", "test.php", $icon));
$nivel3 		= &$nivel2->addItem(new HTML_TreeNode("NIVEL 3", "test.php", $icon));
$nivel4 		= &$nivel3->addItem(new HTML_TreeNode("NIVEL 4", "test.php", $icon));
$nivel5 		= &$nivel4->addItem(new HTML_TreeNode("NIVEL 5", "test.php", $icon));
$nivel6 		= &$nivel5->addItem(new HTML_TreeNode("NIVEL 6", "test.php", $icon));*/

//$blaat->addItem(new HTML_TreeNode("deleted-items", "test.php", $icon));

/*$cuentas->addItem(new HTML_TreeNode("sent-items",    "test.php", $icon));
$cuentas->addItem(new HTML_TreeNode("drafts",        "test.php", $icon));	*/

$menu01->addItem($cuentas);
/*$menu01->addItem(new HTML_TreeNode("Menu 1 Stuff", "test.php", $icon));
$menu01->addItem(new HTML_TreeNode("Other Stuff", "test.php", $icon));//$cuentas);*/
#$menu01->addItem($cuentas);

?>
  


<div id="arbolCuentas"></div>

<?php $menu01->printMenu()?>