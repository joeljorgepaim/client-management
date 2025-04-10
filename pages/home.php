<?php



$db = Database::getInstance();
$conn = $db->getConnection();

$clientCount = $conn->query("SELECT COUNT(*) as count FROM clients")->fetch_assoc()['count'];
$contactCount = $conn->query("SELECT COUNT(*) as count FROM contacts")->fetch_assoc()['count'];
$linkCount = $conn->query("SELECT COUNT(*) as count FROM client_contact")->fetch_assoc()['count'];

// Get recent clients
$recentClients = $conn->query("SELECT * FROM clients ORDER BY created_at DESC LIMIT 5");
$recentContacts = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Dashboard</h1>
        <p class="text-muted">Welcome to the Client Management System</p>
    </div>
    <div>
        <span class="badge badge-pill badge-light">Today: <?php echo date('F j, Y'); ?></span>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-primary h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-title ">Total Clients</div>
                        <div class="stat-value"><?php echo $clientCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-sm btn-primary">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?page=client_form" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus mr-1"></i> Add New
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-success h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-title ">Total Contacts</div>
                        <div class="stat-value"><?php echo $contactCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-address-card fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="<?php echo BASE_URL; ?>?page=contacts" class="btn btn-sm btn-success">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?page=contact_form" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus mr-1"></i> Add New
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card stat-card stat-card-info h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="stat-title">Client-Contact Links</div>
                        <div class="stat-value"><?php echo $linkCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-link fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="text-muted mb-0 small">Active connections between clients and contacts</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold ">
                    <i class="fas fa-building mr-1"></i> Recent Clients
                </h6>
                <a href="<?php echo BASE_URL; ?>?page=client_form" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus mr-1"></i> Add New
                </a>
            </div>
            <div class="card-body">
                <?php if ($recentClients->num_rows === 0): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-building fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">No clients added yet.</p>
                        <a href="<?php echo BASE_URL; ?>?page=client_form" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i> Add Your First Client
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php while ($client = $recentClients->fetch_assoc()): ?>
                            <div class="list-group-item border-left-0 border-right-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($client['name']); ?></h6>
                                    <div class="small text-muted d-flex align-items-center">
                                        <span class="badge badge-light mr-2">
                                            <?php echo htmlspecialchars($client['client_code']); ?>
                                        </span>
                                        <i class="far fa-clock mr-1"></i>
                                        <?php echo date('M j, Y', strtotime($client['created_at'])); ?>
                                    </div>
                                </div>
                                <a href="<?php echo BASE_URL; ?>?page=client_view&id=<?php echo $client['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($recentClients->num_rows > 0): ?>
                <div class="card-footer text-center">
                    <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-link">
                        View all clients <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold ">
                    <i class="fas fa-address-card mr-1"></i> Recent Contacts
                </h6>
                <a href="<?php echo BASE_URL; ?>?page=contact_form" class="btn btn-sm btn-success">
                    <i class="fas fa-plus mr-1"></i> Add New
                </a>
            </div>
            <div class="card-body">
                <?php if ($recentContacts->num_rows === 0): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-address-card fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">No contacts added yet.</p>
                        <a href="<?php echo BASE_URL; ?>?page=contact_form" class="btn btn-success">
                            <i class="fas fa-plus mr-1"></i> Add Your First Contact
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php while ($contact = $recentContacts->fetch_assoc()): ?>
                            <div class="list-group-item border-left-0 border-right-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($contact['full_name'] . ' ' . $contact['surname']); ?></h6>
                                    <div class="small text-muted d-flex align-items-center">
                                        <span class="mr-2">
                                            <i class="fas fa-envelope mr-1"></i>
                                            <?php echo htmlspecialchars($contact['email']); ?>
                                        </span>
                                        <i class="far fa-clock mr-1"></i>
                                        <?php echo date('M j, Y', strtotime($contact['created_at'])); ?>
                                    </div>
                                </div>
                                <a href="<?php echo BASE_URL; ?>?page=contact_view&id=<?php echo $contact['id']; ?>" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($recentContacts->num_rows > 0): ?>
                <div class="card-footer text-center">
                    <a href="<?php echo BASE_URL; ?>?page=contacts" class="btn btn-link">
                        View all contacts <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Get Started Guide for new users -->
<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h6 class="m-0 font-weight-bold">
            <i class="fas fa-info-circle mr-1"></i> Quick Guide
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="text-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <h5 class="text-center">Step 1: Create Clients</h5>
                <p class="text-muted small text-center">
                    Add your client organizations to the system with unique client codes.
                </p>
                <div class="text-center">
                    <a href="<?php echo BASE_URL; ?>?page=client_form" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus mr-1"></i> Add Client
                    </a>
                </div>
            </div>
            
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="text-center mb-3">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-address-card"></i>
                    </div>
                </div>
                <h5 class="text-center">Step 2: Add Contacts</h5>
                <p class="text-muted small text-center">
                    Create contact records for individuals with their contact information.
                </p>
                <div class="text-center">
                    <a href="<?php echo BASE_URL; ?>?page=contact_form" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-plus mr-1"></i> Add Contact
                    </a>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="text-center mb-3">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-link"></i>
                    </div>
                </div>
                <h5 class="text-center">Step 3: Link Records</h5>
                <p class="text-muted small text-center">
                    Connect contacts to their respective client organizations.
                </p>
                <div class="text-center">
                    <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-link mr-1"></i> View Clients
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
