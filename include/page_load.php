<!-- Page load spinner -->
<!-- jsuites plugin -->
<script src="https://jsuites.net/v5/jsuites.js"></script>
<!-- jsuites plugin css for spinner  -->
<link rel="stylesheet" href="https://jsuites.net/v5/jsuites.css" type="text/css" />

<div id="spinner" class="spinner-border text-primary" style="position: absolute; top: 50%; left: 50%; display: none;"
    role="status">
</div>

<script>
const show = function() {
    jSuites.loading.show();
    // Hide the loading spin after 1 seconds
    setTimeout(function() {
        // Hide
        jSuites.loading.hide();
    }, 300);
}

window.onload = function() {
    show();
};
</script>