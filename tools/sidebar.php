<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mi_proyecto/tools/permisos.php');

$page = $_GET['page'] ?? 'home';
?>

<aside class="app-sidebar bg-body-secondary shadow">

    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">

                <!-- DASHBOARD / INICIO -->
                <?php if(tieneAcceso("dashboard")){ ?>
                <li class="nav-item">
                    <a href="/mi_proyecto/?page=home"
                       class="nav-link <?php echo ($page == 'home' || $page == 'dashboard' || $page == '') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Inicio</p>
                    </a>
                </li>
                <?php } ?>


                <!-- ESPECIALIDADES -->
                <?php if(tieneAcceso("categorias")){ ?>
                <li class="nav-item">
                    <a href="/mi_proyecto/?page=categorias"
                       class="nav-link <?php echo ($page == 'categorias') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-tags"></i>
                        <p>Especialidades</p>
                    </a>
                </li>
                <?php } ?>


                <!-- SERVICIOS MÉDICOS -->
                <?php if(tieneAcceso("productos")){ ?>
                <li class="nav-item">
                    <a href="/mi_proyecto/?page=productos"
                       class="nav-link <?php echo ($page == 'productos' || $page == 'servicios') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-heart-pulse"></i>
                        <p>Servicios Médicos</p>
                    </a>
                </li>
                <?php } ?>


                <!-- CITAS -->
                <?php if(tieneAcceso("citas")){ ?>
                <li class="nav-item">
                    <a href="/mi_proyecto/?page=citas"
                       class="nav-link <?php echo ($page == 'citas') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-calendar2-check"></i>
                        <p>Citas</p>
                    </a>
                </li>
                <?php } ?>


                <!-- RECETAS MÉDICAS -->
                <?php if(tieneAcceso("recetas")){ ?>
                <li class="nav-item">
                    <a href="/mi_proyecto/?page=recetas"
                       class="nav-link <?php echo ($page == 'recetas') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-file-earmark-medical"></i>
                        <p>Recetas Médicas</p>
                    </a>
                </li>
                <?php } ?>


                <!-- REPORTES -->
                <?php if(tieneAcceso("reportes")){ ?>
                <li class="nav-item">
                    <a href="/mi_proyecto/?page=reportes"
                       class="nav-link <?php echo ($page == 'reportes') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-flag"></i>
                        <p>Reportes</p>
                    </a>
                </li>
                <?php } ?>


                <!-- ROLES -->
                <?php if(tieneAcceso("roles")){ ?>
                <li class="nav-item">
                    <a href="/mi_proyecto/?page=roles"
                       class="nav-link <?php echo ($page == 'roles') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-shield-lock"></i>
                        <p>Roles</p>
                    </a>
                </li>
                <?php } ?>


                <!-- USUARIOS -->
                <?php if(tieneAcceso("usuarios")){ ?>
                <li class="nav-item">
                    <a href="/mi_proyecto/?page=usuarios"
                       class="nav-link <?php echo ($page == 'usuarios') ? 'active' : ''; ?>">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                <?php } ?>

            </ul>

        </nav>

    </div>

</aside>