<select id="selectConcepto" name="selectConcepto" class="cajas" style="width:300px; ">
    <?php
    foreach($conceptos as $row)
    {
        echo '<option  value="'.$row->idConcepto.'">'.$row->nombre.'</option>';
    }
    ?>
</select>