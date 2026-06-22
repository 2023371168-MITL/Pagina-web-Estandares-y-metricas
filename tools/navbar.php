<nav class="app-header navbar navbar-expand bg-body">

<div class="container-fluid">

<!-- BOTON SIDEBAR -->
<ul class="navbar-nav">

<li class="nav-item">

<a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
<i class="bi bi-list"></i>
</a>

</li>

<li class="nav-item d-none d-md-block">
<a href="/mi_proyecto/index.php" class="nav-link">Inicio</a>
</li>

<li class="nav-item d-none d-md-block">
<a href="#" class="nav-link">Contacto</a>
</li>

</ul>


<!-- LADO DERECHO NAVBAR -->
<ul class="navbar-nav ms-auto">


<!-- BUSCADOR -->
<li class="nav-item">

<a class="nav-link" href="#" role="button">
<i class="bi bi-search"></i>
</a>

</li>


<!-- NOTIFICACIONES -->
<li class="nav-item dropdown">

<a class="nav-link" data-bs-toggle="dropdown" href="#">
<i class="bi bi-bell-fill"></i>
<span class="navbar-badge badge text-bg-warning">3</span>
</a>

<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

<span class="dropdown-item dropdown-header">3 Notificaciones</span>

<div class="dropdown-divider"></div>

<a href="#" class="dropdown-item">
<i class="bi bi-cart-fill me-2"></i> Nuevo pedido registrado
<span class="float-end text-secondary fs-7">Hace 5 min</span>
</a>

<div class="dropdown-divider"></div>

<a href="#" class="dropdown-item">
<i class="bi bi-box-seam me-2"></i> Nuevo producto agregado
<span class="float-end text-secondary fs-7">Hace 1 hora</span>
</a>

</div>

</li>


<!-- FULLSCREEN -->
<li class="nav-item">

<a class="nav-link" href="#" data-lte-toggle="fullscreen">
<i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
<i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none"></i>
</a>

</li>


<!-- USUARIO -->
<li class="nav-item dropdown user-menu">

<a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">

<img
src="/mi_proyecto/img/user2-160x160.jpg"
class="user-image rounded-circle shadow"
alt="User Image">

<span class="d-none d-md-inline">

<?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario'; ?>

</span>

</a>


<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

<li class="user-header text-bg-primary">

<img
src="/mi_proyecto/img/user2-160x160.jpg"
class="rounded-circle shadow"
alt="User Image">

<p>

<?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario'; ?>

<small>Panel de ventas</small>

</p>

</li>


<li class="user-footer">

<a href="#" class="btn btn-default btn-flat">
Perfil
</a>

<a href="/mi_proyecto/logout.php"
class="btn btn-default btn-flat float-end">

Cerrar sesión

</a>

</li>

</ul>

</li>

</ul>

</div>

</nav>