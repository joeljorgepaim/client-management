<?php
// pages/clients.php

// Get all clients
$clients = Client::getAll();
?>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Clients</h1>
        <p class="text-muted">Manage all your client organizations</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>?page=client_form" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i> Add New Client
        </a>
    </div>
</div>

<!-- Search & Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form class="form-row align-items-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control border-left-0" id="client-search" placeholder="Search clients...">
                </div>
            </div>
            <div class="col-md-2 mb-2 mb-md-0">
                <select class="form-control" id="sort-option">
                    <option value="name-asc">Name (A-Z)</option>
                    <option value="name-desc">Name (Z-A)</option>
                    <option value="newest">Newest first</option>
                    <option value="oldest">Oldest first</option>
                </select>
            </div>
            <div class="col-md-4 mb-2 mb-md-0">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-outline-primary active">
                        <input type="radio" name="view" id="view-all" checked> All
                    </label>
                    <label class="btn btn-outline-primary">
                        <input type="radio" name="view" id="view-with-contacts"> With Contacts
                    </label>
                    <label class="btn btn-outline-primary">
                        <input type="radio" name="view" id="view-no-contacts"> No Contacts
                    </label>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <button type="button" class="btn btn-outline-secondary" id="reset-filters">
                    <i class="fas fa-redo mr-1"></i> Reset
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Clients List -->
<div class="card">
    <div class="card-body">
        <?php if (empty($clients)): ?>
            <div class="text-center py-5">
                <i class="fas fa-building fa-4x text-gray-300 mb-3"></i>
                <h4 class="text-muted">No Clients Found</h4>
                <p class="text-muted">Get started by adding your first client</p>
                <a href="<?php echo BASE_URL; ?>?page=client_form" class="btn btn-primary mt-2">
                    <i class="fas fa-plus mr-2"></i> Add New Client
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover" id="clients-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Client Code</th>
                            <th class="text-center">Contacts</th>
                            <th>Created On</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <?php $contactCount = $client->getContactCount(); ?>
                            <tr class="client-row" data-name="<?php echo strtolower(htmlspecialchars($client->getName())); ?>" data-contacts="<?php echo $contactCount; ?>">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle text-center mr-3" style="width: 40px; height: 40px; line-height: 40px;">
                                            <i class="fas fa-building text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($client->getName()); ?></h6>
                                            <small class="text-muted">ID: <?php echo $client->getId(); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-light"><?php echo htmlspecialchars($client->getClientCode()); ?></span></td>
                                <td class="text-center">
                                    <?php if ($contactCount > 0): ?>
                                        <span class="badge badge-pill badge-primary"><?php echo $contactCount; ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-pill badge-light">0</span>
                                    <?php endif; ?>
                                </td>
                                <td><small class="text-muted"><?php echo date('M j, Y', strtotime($client->getCreatedAt())); ?></small></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?php echo BASE_URL; ?>?page=client_view&id=<?php echo $client->getId(); ?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="View Client Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>?page=client_form&id=<?php echo $client->getId(); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Edit Client">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-client" data-id="<?php echo $client->getId(); ?>" data-name="<?php echo htmlspecialchars($client->getName()); ?>" data-toggle="tooltip" title="Delete Client">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <span class="text-muted">Showing <?php echo count($clients); ?> clients</span>
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteClientModal" tabindex="-1" role="dialog" aria-labelledby="deleteClientModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteClientModalLabel">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Confirm Delete
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete client <strong id="client-name-to-delete"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    This action cannot be undone and will remove all links to contacts.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancel
                </button>
                <form id="delete-client-form" method="post" action="<?php echo BASE_URL; ?>?page=client_form&action=delete">
                    <input type="hidden" id="client-id-to-delete" name="id">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Handle delete button click
        $('.delete-client').click(function(e) {
            e.preventDefault();
            const clientId = $(this).data('id');
            const clientName = $(this).data('name');
            
            $('#client-id-to-delete').val(clientId);
            $('#client-name-to-delete').text(clientName);
            $('#deleteClientModal').modal('show');
        });
        
        // Client search functionality
        $('#client-search').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            filterClients();
        });
        
        // Handle view filter changes
        $('input[name="view"]').change(function() {
            filterClients();
        });
        
        // Sort options
        $('#sort-option').change(function() {
            sortClients($(this).val());
        });
        
        // Reset filters
        $('#reset-filters').click(function() {
            $('#client-search').val('');
            $('#sort-option').val('name-asc');
            $('#view-all').prop('checked', true).parent().addClass('active');
            $('#view-with-contacts').prop('checked', false).parent().removeClass('active');
            $('#view-no-contacts').prop('checked', false).parent().removeClass('active');
            filterClients();
            sortClients('name-asc');
        });
        
        // Filter clients based on search and view options
        function filterClients() {
            const searchTerm = $('#client-search').val().toLowerCase();
            const viewOption = $('input[name="view"]:checked').attr('id');
            
            $('.client-row').each(function() {
                const name = $(this).data('name');
                const contacts = $(this).data('contacts');
                let show = true;
                
                // Apply search filter
                if (searchTerm && !name.includes(searchTerm)) {
                    show = false;
                }
                
                // Apply view filter
                if (viewOption === 'view-with-contacts' && contacts === 0) {
                    show = false;
                } else if (viewOption === 'view-no-contacts' && contacts > 0) {
                    show = false;
                }
                
                $(this).toggle(show);
            });
            
            updateResults();
        }
        
        // Sort clients
        function sortClients(sortOption) {
            const rows = $('#clients-table tbody tr').get();
            
            rows.sort(function(a, b) {
                const aName = $(a).data('name');
                const bName = $(b).data('name');
                const aContacts = $(a).data('contacts');
                const bContacts = $(b).data('contacts');
                
                switch(sortOption) {
                    case 'name-asc':
                        return aName.localeCompare(bName);
                    case 'name-desc':
                        return bName.localeCompare(aName);
                    case 'contacts-asc':
                        return aContacts - bContacts;
                    case 'contacts-desc':
                        return bContacts - aContacts;
                    default:
                        return 0;
                }
            });
            
            $.each(rows, function(index, row) {
                $('#clients-table tbody').append(row);
            });
        }
        
        // Update results count
        function updateResults() {
            const visibleCount = $('.client-row:visible').length;
            const totalCount = $('.client-row').length;
            
            $('.text-muted').text(`Showing ${visibleCount} of ${totalCount} clients`);
        }
        
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Initial sort
        sortClients('name-asc');
    });
</script>
