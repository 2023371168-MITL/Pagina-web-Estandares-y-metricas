<footer class="app-footer">

<div class="float-end d-none d-sm-inline">
Panel administrativo
</div>

<strong>
Copyright © 2025
<a href="https://www.instagram.com/lebasi_louzes/" class="text-decoration-none">
isa_MITL_09
</a>
</strong>

</footer>

</div>




<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>


<script src="/mi_proyecto/js/adminlte.js"></script>


<script>

const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";

document.addEventListener("DOMContentLoaded", function () {

const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {

OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
scrollbars: {
theme: "os-theme-light",
autoHide: "leave",
clickScroll: true
}
});

}

});

</script>

</body>
</html>