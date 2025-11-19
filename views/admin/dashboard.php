<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<link rel="stylesheet" href="plugins/toastr/toastr.min.css">
<script src="plugins/toastr/toastr.min.js"></script>
<script>
    <?php if ($estado == '1'): ?>
        toastr.error("Hay productos que tienen un stock debajo de lo establecido", "Mensaje del Sistema");
    <?php endif; ?>
</script>
<?php
$this->endSection('contenido');
?>