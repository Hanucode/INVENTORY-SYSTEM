<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('content').classList.toggle('active');
    }

    function toggleProductMenu() {
        var dropdown = document.getElementById("productDropdown");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>