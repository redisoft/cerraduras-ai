<?php 
echo '
<select id="selectPedimentos" name="selectPedimentos" class="cajas" style="width: 300px" required="true">
    <option value="">Seleccione</option>';

    foreach($pedimentos as $row)
    {
        echo '<option value="'.$row->idPedimento.'">'.$row->pedimento.'</option>';
    }

echo'
</select>';
