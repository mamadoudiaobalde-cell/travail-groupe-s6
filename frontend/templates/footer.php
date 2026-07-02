        </div>
    </main>
    
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; <?= date('Y') ?> <?= $_ENV['APP_NAME'] ?? 'Gestion Soutenances Universitaires' ?> - Tous droits réservés</p>
            <p style="font-size: 12px; margin-top: 5px;">Version 1.0.0</p>
        </div>
    </footer>
    
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/auth.js"></script>
    <script src="/assets/js/validation.js"></script>
    <script src="/assets/js/modals.js"></script>
    <script src="/assets/js/notifications.js"></script>
    <script src="/assets/js/filters.js"></script>
    <script src="/assets/js/datatable.js"></script>
    <?php if (isset($includeCharts) && $includeCharts): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/assets/js/charts.js"></script>
    <?php endif; ?>
    <?php if (isset($includeCalendar) && $includeCalendar): ?>
    <script src="/assets/js/calendar.js"></script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/frontend/assets/js/main.js"></script>
</body>
</html>