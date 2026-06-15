<?php
require_once __DIR__ . '/../includes/bootstrap.php';
requireLogin();
redirect(dashboardForRole($_SESSION['user']['role']));
