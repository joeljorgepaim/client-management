<?php
 
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
    <link rel="stylesheet" href="assets/css/styles.css">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
           <img style="height: 30px; " src="assets/images/logo.png" alt=""> </a>
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