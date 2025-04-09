<?php
// pages/client_view.php

// Get client ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$client = Client::getById($id);

// If client not found, redirect to clients list
if (!$client) {
    $_SESSION['message'] = 'Client not found.';
    $_SESSION['message_type'] = 'danger';
    header('Location: ' . BASE_URL . '?page=clients');
    exit;
}

// Handle linking/unlinking contact
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'link_contact' && isset($_POST['contact_id'])) {
        $contactId = (int)$_POST['contact_id'];
        if ($client->linkContact($contactId)) {
            $_SESSION['message'] = 'Contact linked successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Failed to link contact.';
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . BASE_URL . '?page=client_view&id=' . $id);
        exit;
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'unlink_contact' && isset($_POST['contact_id'])) {
        $contactId = (int)$_POST['contact_id'];
        if ($client->unlinkContact($contactId)) {
            $_SESSION['message'] = 'Contact unlinked successfully.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Failed to unlink contact.';
            $_SESSION['message_type'] = 'danger';
        }
        header('Location: ' . BASE_URL . '?page=client_view&id=' . $id);
        exit;
    }
}

// Get linked contacts
$linkedContacts = $client->getContacts();

// Get unlinked contacts for dropdown
$allContacts = Contact::getAll();
$unlinkedContacts = array_filter($allContacts, function($contact) use ($linkedContacts) {
    foreach ($linkedContacts as $linkedContact) {
        if ($contact->getId() === $linkedContact->getId()) {
            return false;
        }
    }
    return true;
});
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Client Details</h1>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?php echo BASE_URL; ?>?page=clients" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Clients
        </a>
        <a href="<?php echo BASE_URL; ?>?page=client_form&id=<?php echo $client->getId(); ?>" class="btn btn-primary">
            <i class="fa fa-edit"></i> Edit Client
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Client Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($client->getName()); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Client Code:</strong> <?php echo htmlspecialchars($client->getClientCode()); ?></p>
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
                <h5 class="mb-0">Linked Contacts</h5>
                
                <?php if (!empty($unlinkedContacts)): ?>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#linkContactModal">
                    <i class="fa fa-link"></i> Link Contact
                </button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($linkedContacts)): ?>
                    <p class="text-center my-3">No contacts linked to this client.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Full Name</th>
                                    <th>Surname</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($linkedContacts as $contact): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($contact->getFullName()); ?></td>
                                        <td><?php echo htmlspecialchars($contact->getSurname()); ?></td>
                                        <td><?php echo htmlspecialchars($contact->getEmail()); ?></td>
                                        <td><?php echo htmlspecialchars($contact->getPhone()); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>?page=contact_view&id=<?php echo $contact->getId(); ?>" class="btn btn-sm btn-info">
                                                View
                                            </a>
                                            <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to unlink this contact?');">
                                                <input type="hidden" name="action" value="unlink_contact">
                                                <input type="hidden" name="contact_id" value="<?php echo $contact->getId(); ?>">
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

<!-- Link Contact Modal -->
<?php if (!empty($unlinkedContacts)): ?>
<div class="modal fade" id="linkContactModal" tabindex="-1" role="dialog" aria-labelledby="linkContactModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkContactModalLabel">Link Contact to Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="action" value="link_contact">
                    
                    <div class="form-group">
                        <label for="contact-select" class="required">Select Contact</label>
                        <select class="form-control" id="contact-select" name="contact_id" required>
                            <option value="">-- Select Contact --</option>
                            <?php foreach ($unlinkedContacts as $contact): ?>
                                <option value="<?php echo $contact->getId(); ?>">
                                    <?php echo htmlspecialchars($contact->getFullName() . ' ' . $contact->getSurname() . ' (' . $contact->getEmail() . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <p class="text-muted small">
                        Can't find the contact? <a href="<?php echo BASE_URL; ?>?page=contact_form" target="_blank">Create a new contact</a>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Link Contact</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
