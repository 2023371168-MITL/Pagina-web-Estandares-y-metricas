<?php

$page_title = "Tienda";

include_once("tools/header.php");
include_once("tools/navbar.php");
include_once("tools/sidebar.php");

require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/mypathdb.php');

/*CONSULTA CON CATEGORIA */
$sql="SELECT p.*, c.nombre AS categoria
FROM productos p
LEFT JOIN categorias c ON p.id_categoria = c.id
WHERE p.status=1
ORDER BY c.nombre ASC, p.nombre ASC";

$resultado=mysqli_query($conn,$sql);

?>

<main class="app-main">

<div class="container mt-4">

<?php
$categoria_actual = "";

/* RECORRER PRODUCTOS */
while($row=mysqli_fetch_assoc($resultado)){

/* CAMBIO DE CATEGORIA */
if($categoria_actual != $row['categoria']){

// cerrar row anterior si no es la primera
if($categoria_actual != ""){
echo "</div>";
}

$categoria_actual = $row['categoria'];
?>

<h4 class="mt-4 mb-3 text-primary">
<?php echo $categoria_actual; ?>
</h4>

<div class="row">

<?php } ?>

<!-- PRODUCTO -->
<div class="col-md-3 mb-4">

<div class="card h-100 shadow-sm">

<?php if($row['imagen']!=""){ ?>
<img src="/mi_proyecto/img/productos/<?php echo $row['imagen']; ?>" 
class="card-img-top" 
style="height:180px; object-fit:cover;">
<?php } ?>

<div class="card-body text-center">

<h6 class="card-title">
<?php echo $row['nombre']; ?>
</h6>

<p class="text-success fw-bold">
$<?php echo number_format($row['precio'],2); ?>
</p>

</div>

<div class="card-footer text-center">

<button class="btn btn-success w-100"
onclick="AgregarCarrito(
'<?php echo $row['id']; ?>',
'<?php echo htmlspecialchars($row['nombre']); ?>',
'<?php echo $row['precio']; ?>'
)">

🛒 Agregar al carrito

</button>

</div>

</div>

</div>

<?php } ?>

</div> <!-- cierre último row -->

</div>

</main>

<?php include_once("tools/footer.php"); ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="/mi_proyecto/modulos/carrito/carritoFunciones.js"></script>

<style>
.card:hover{
transform: scale(1.03);
transition: 0.2s;
}
</style>