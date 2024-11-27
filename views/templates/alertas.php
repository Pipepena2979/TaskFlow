<?php 
    foreach ($alertas as $key => $alerta):
        foreach($alerta as $mensaje):
?>

    <div class="alerta <?= $key ?>"><?php echo $mensaje; ?></div>

<?php
        endforeach;
    endforeach;
?>