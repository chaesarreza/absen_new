</div> <footer class="footer-custom mt-auto pt-4">
                <div class="text-center">
                    <small class="text-muted">
                        &copy; <?= date('Y') ?> - Aplikasi Absensi Madrasah Copyright Chaesar Reza Albajabir
                    </small>
                </div>
            </footer>

        </main> </div> <script src="../assets/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarToggle = document.getElementById("sidebarToggle");
            const sidebar = document.querySelector(".admin-panel .sidebar");
            const adminWrapper = document.querySelector(".admin-panel-wrapper");
            const backdrop = document.createElement('div');
            backdrop.className = 'sidebar-backdrop';
            
            if (adminWrapper) {
                 adminWrapper.appendChild(backdrop);
            }

            const closeSidebar = () => {
                sidebar.classList.remove("show");
                adminWrapper.classList.remove("sidebar-open");
                backdrop.classList.remove('show');
            };

            if (sidebarToggle) {
                sidebarToggle.addEventListener("click", function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle("show");
                    adminWrapper.classList.toggle("sidebar-open");
                    backdrop.classList.toggle('show');
                });
            }

            const mobileMenuTrigger = document.getElementById('mobileMenuTrigger');
            if (mobileMenuTrigger && sidebarToggle) {
                mobileMenuTrigger.addEventListener('click', function() {
                    sidebarToggle.click();
                });
            }
            
            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }
        });
    </script>
</body>
</html>