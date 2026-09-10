<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="d-flex justify-content-center align-items-center" style="height:600px;">
                <img src="<?php echo "https://companiasysven.com/logos/" . session()->get("gene_nruc") . '/' . 'logo.jpg'; ?>" class="rounded mx-auto d-block" alt="">
            </div>
        </div>
    </div>
</div>
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