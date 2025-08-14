<footer class="bg-white border-top py-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 small">&copy; {{ date('Y') }} eSurvTrack. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0 small">Version 1.0.0</p>
            </div>
        </div>
    </div>
</footer>

<script>
    // Fullscreen toggle
    const fullscreenToggle = document.getElementById('fullscreenToggle');
    const fullscreenIcon = document.getElementById('fullscreenIcon');

    fullscreenToggle.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            fullscreenIcon.classList.replace('fa-expand', 'fa-compress');
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
                fullscreenIcon.classList.replace('fa-compress', 'fa-expand');
            }
        }
    });



const menuToggle = document.getElementById('menuToggle');
menuToggle.addEventListener('click', () => {
    document.body.classList.toggle('sidebar-collapsed');
});

</script>
