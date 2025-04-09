<?php
// templates/header.php
// Make sure there is no whitespace before or after the PHP tags
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --dark-color: #5a5c69;
            --light-color: #f8f9fc;
            --danger-color: #e74a3b;
            --warning-color: #f6c23e;
            --info-color: #36b9cc;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            color: #5a5c69;
            padding-top: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .container {
            max-width: 1280px;
            padding-top: 20px;
            flex: 1;
        }
        
        /* Navbar styling */
        .navbar {
            background-color: white !important;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 0.75rem 1rem;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand i {
            margin-right: 0.5rem;
            font-size: 1.5rem;
        }
        
        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            padding: 0.75rem 1rem !important;
            transition: all 0.2s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            background-color: #f8f9fc;
            border-radius: 5px;
        }
        
        .nav-link i {
            margin-right: 0.5rem;
        }
        
        /* Card styling */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
            background-color: white;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .card-header:first-child {
            border-radius: 8px 8px 0 0;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            box-shadow: 0 0.125rem 0.25rem 0 rgba(58, 59, 69, 0.2);
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #3a5ecd;
            border-color: #3a5ecd;
        }
        
        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-success:hover {
            background-color: #18ad79;
            border-color: #18ad79;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #d93c2d;
            border-color: #d93c2d;
        }
        
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }
        
        .btn-info:hover {
            background-color: #2ea7b9;
            border-color: #2ea7b9;
        }
        
        /* Tables */
        .table {
            color: var(--dark-color);
        }
        
        .table thead th {
            background-color: #f8f9fc;
            border-top: none;
            border-bottom: 2px solid #e3e6f0;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f2f4fd;
        }
        
        /* Forms */
        .form-control {
            border-radius: 6px;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
            color: var(--dark-color);
        }
        
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        label {
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .required:after {
            content: " *";
            color: var(--danger-color);
        }
        
        /* Page headers */
        .page-header {
            margin-bottom: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        /* Stats cards */
        .stat-card {
            border-left: 4px solid;
            border-radius: 8px;
        }
        
        .stat-card-primary {
            border-left-color: var(--primary-color);
        }
        
        .stat-card-success {
            border-left-color: var(--secondary-color);
        }
        
        .stat-card-info {
            border-left-color: var(--info-color);
        }
        
        .stat-card .stat-title {
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--dark-color);
            letter-spacing: 0.1rem;
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }
        
        .stat-card .card-body {
            padding: 1.25rem;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: 8px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
        
        .alert-success {
            color: #0e6251;
            background-color: #d4edda;
        }
        
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
        }
        
        /* Modal */
        .modal-content {
            border-radius: 8px;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .modal-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        
        .modal-footer {
            background-color: #f8f9fc;
            border-top: 1px solid #e3e6f0;
        }
        
        /* Footer */
        footer {
            background-color: white;
            padding: 1.5rem 0;
            box-shadow: 0 -0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-top: 3rem;
            color: var(--dark-color);
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <i class="fas fa-users"></i> Client Manager
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item <?php echo ($page == 'home') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($page == 'clients' || $page == 'client_form' || $page == 'client_view') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>?page=clients">
                            <i class="fas fa-building"></i> Clients
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($page == 'contacts' || $page == 'contact_form' || $page == 'contact_view') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>?page=contacts">
                            <i class="fas fa-address-card"></i> Contacts
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <main>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php if ($_SESSION['message_type'] == 'success'): ?>
                        <i class="fas fa-check-circle mr-2"></i>
                    <?php elseif ($_SESSION['message_type'] == 'danger'): ?>
                        <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php elseif ($_SESSION['message_type'] == 'warning'): ?>
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                    <?php else: ?>
                        <i class="fas fa-info-circle mr-2"></i>
                    <?php endif; ?>
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php 
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
<?php endif; ?>