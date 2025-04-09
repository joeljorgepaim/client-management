<?php
// pages/contact_view.php

// Get contact ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$contact = Contact::getById($id);

// If contact not found, redirect to contacts list
if (!$contact) {
    $_SESSION['message'] = 'Contact not found.';
    $_SESSION['message_type'] = 'danger';
    header('Location: ' . BASE_URL . '?page=contacts');
    exit;
}

// Handle linking/unlinking client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'link_client' && isset($_POST['client_id'])) {
        $clientId = (int)$_POST['client_id'];
        $client = Client::getById($clientId);
        
        if ($client && $client->linkContact($contact->getId())) {
            $_SESSION['message'] = 'Client linked successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Failed to link client.';
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . BASE_URL . '?page=contact_view&id=' . $id);
        exit;
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'unlink_client' && isset($_POST['client_id'])) {
        $clientId = (int)$_POST['client_id'];
        $client = Client::getById($clientId);
        
        if ($client && $client->unlinkContact($contact->getId())) {
            $_SESSION['message'] = 'Client unlinked successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Failed to unlink client.';
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . BASE_URL . '?page=contact_view&id=' . $id);
        exit;
    }
}

// Get linked clients
$linkedClients = $contact->getClients();

// Get unlinked clients for dropdown
$allClients = Client::getAll();
$unlinkedClients = array_filter($allClients, function($client) use ($linkedClients) {
    foreach ($linkedClients as $linkedClient) {
        if ($client->getId() === $linkedClient->getId()) {
            return false;
        }
    }
    return true;
});
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Contact Details</h1>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?php echo BASE_URL; ?>?page=contacts" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Contacts
        </a>
        <a href="<?php echo BASE_URL; ?>?page=contact_form&id=<?php echo $contact->getId(); ?>" class="btn btn-primary">
            <i class="fa fa-edit"></i> Edit Contact
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($contact->getFullName()); ?></p>
                        <p><strong>Surname:</strong> <?php echo htmlspecialchars($contact->getSurname()); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($contact->getEmail()); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($contact->getPhone() ?: 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Linked Clients</h5>
                
                <?php if (!empty($unlinkedClients)): ?>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#linkClientModal">
                    <i class="fa fa-link"></i> Link Client
                </button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($linkedClients)): ?>
                    <p class="text-center my-3">No clients linked to this contact.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Client Code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($linkedClients as $client): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($client->getName()); ?></td>
                                        <td><?php echo htmlspecialchars($client->getClientCode()); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>?page=client_view&id=<?php echo $client->getId(); ?>" class="btn btn-sm btn-info">
                                                View
                                            </a>
                                            <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to unlink this client?');">
                                                <input type="hidden" name="action" value="unlink_client">
                                                <input type="hidden" name="client_id" value="<?php echo $client->getId(); ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Unlink</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Link Client Modal -->
<?php if (!empty($unlinkedClients)): ?>
<div class="modal fade" id="linkClientModal" tabindex="-1" role="dialog" aria-labelledby="linkClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkClientModalLabel">Link Client to Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="link_client">
                    
                    <div class="form-group">
                        <label for="client-select" class="required">Select Client</label>
                        <select class="form-control" id="client-select" name="client_id" required>
                            <option value="">-- Select Client --</option>
                            <?php foreach ($unlinkedClients as $client): ?>
                                <option value="<?php echo $client->getId(); ?>">
                                    <?php echo htmlspecialchars($client->getName() . ' (' . $client->getClientCode() . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <p class="text-muted small">
                        Can't find the client? <a href="<?php echo BASE_URL; ?>?page=client_form" target="_blank">Create a new client</a>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Link Client</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
