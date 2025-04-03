</main>
            
            <!-- Footer -->
            <footer class="bg-white border-t p-4 text-center text-sm text-gray-500">
                <p>Business Management System &copy; <?php echo date('Y'); ?></p>
            </footer>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Confirm before delete actions
        document.querySelectorAll('.confirm-delete').forEach(button => {
            button.addEventListener('click', (e) => {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });
        });

        // Initialize tooltips
        tippy('[data-tippy-content]', {
            arrow: true,
            animation: 'shift-away'
        });
    </script>
</body>
</html>