

<div style="border-radius: 7px; width:8.6cm; height:5.4cm; border: solid 1px #000">

    <div style="height:45px; line-height:40px; font-size:15px; text-align:center">
        <?php echo $configuracion->nombre?>
    </div>
    
    <div style="width:5cm; float:left; font-size:12px; text-align:center; height:3.1cm">
        
        <div style="line-height:18px">
            <?php echo $personal->nombre?> <br />
            <?php echo $personal->departamento?>
        </div>
        
       <!-- <div id="codigoBarras" style="margin-top:20px"></div>-->
       <img src="<?php echo base_url()?>img/personal/<?php echo $personal->numeroAcceso?>.bmp" align="middle" style="margin-left:20px; margin-top:10px; width:300px; height:150px" />
    </div>
    
    <div style="width:3.1cm; float:left; text-align:center">
        <?php
        if(file_exists('img/personal/'.$personal->idPersonal.'_'.$personal->fotografia) and strlen($personal->fotografia)>3)
		{
			echo '<img src="'.base_url().'img/personal/'.$personal->idPersonal.'_'.$personal->fotografia.'" style="max-width:100px; max-height:100px; height:3.1cm" />';
		}
		else
		{
			echo '<img src="'.base_url().'img/personal/silueta.jpeg" style="max-width:100px; max-height:100px; height:3.1cm" />';
		}
		?>
    </div>
    
    <!--<div style="border-top: solid 1px #000; margin-top:3.9cm"></div>-->
</div>