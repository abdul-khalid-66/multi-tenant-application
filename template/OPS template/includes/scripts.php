<!--   Core JS Files   -->
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="assets/js/plugins/chartjs.min.js"></script>

<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>

<?php if (basename($_SERVER['PHP_SELF']) == 'dashboard.php'): ?>
<script>
    // Dashboard-specific scripts
    var ctx = document.getElementById("chart-bars").getContext("2d");
    // ... rest of your chart code ...
</script>
<?php endif; ?>

</body>
</html>