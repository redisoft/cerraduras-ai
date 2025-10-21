<?php
foreach($sublineas as $row)
{
	echo '<div class="puntoVentaSubLineas" onclick="definirSubLinea('.$row->idSubLinea.')">';

	echo '<section>'.$row->nombre.'</section>
	
	</div>';
}