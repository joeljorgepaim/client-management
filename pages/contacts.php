<?php
// pages/contacts.php

// Get all contacts
$contacts = Contact::getAll();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h1>Contacts</h1>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?php echo BASE_URL; ?>?page=contact_form" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add New Contact
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($contacts)): ?>
            <p class="text-center my-5">No contact(s) found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Full Name</th>
                            <th>Surname</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="text-center">No. of linked clients</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($contact->getFullName()); ?></td>
                                <td><?php echo htmlspecialchars($contact->getSurname()); ?></td>
                                <td><?php echo htmlspecialchars($contact->getEmail()); ?></td>
                                <td><?php echo htmlspecialchars($contact->getPhone()); ?></td>
                                <td class="text-center"><?php echo $contact->getClientCount(); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>?page=contact_view&id=<?php echo $contact->getId(); ?>" class="btn btn-sm btn-info">
                                        View
                                    </a>
                                    <a href="<?php echo BASE_URL; ?>?page=contact_form&id=<?php echo $contact->getId(); ?>" class="btn btn-sm btn-primary">
                                        Edit
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger delete-contact" data-id="<?php echo $contact->getId(); ?>" data-name="<?php echo htmlspecialchars($contact->getFullName() . ' ' . $contact->getSurname()); ?>">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteContactModal" tabindex="-1" role="dialog" aria-labelledby="deleteContactModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteContactModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete contact <strong id="contact-name-to-delete"></strong>?
                <p class="text-danger mt-2">This action cannot be undone and will remove all links to clients.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="delete-contact-form" method="post" action="<?php echo BASE_URL; ?>?page=contact_form&action=delete">
                    <input type="hidden" id="contact-id-to-delete" name="id">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle delete button click
        $('.delete-contact').click(function(e) {
            e.preventDefault();
            const contactId = $(this).data('id');
            const contactName = $(this).data('name');
            
            $('#contact-id-to-delete').val(contactId);
            $('#contact-name-to-delete').text(contactName);
            $('#deleteContactModal').modal('show');
        });
    });
</script>
