<?php

?>
        </main>
        
        <footer class="mt-5 pt-5 text-muted border-top">
            <p>&copy; <?php echo date('Y'); ?> Created by: Joel Paim</p>
        </footer>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Global JavaScript functions
        $(document).ready(function() {
            // Auto-generate client code when name is entered
            $('#client-name').on('blur', function() {
                const name = $(this).val();
                if (name && $('#client-code').val() === '') {
                    $.ajax({
                        url: '<?php echo BASE_URL; ?>?ajax=generate_client_code',
                        method: 'POST',
                        data: { name: name },
                        dataType: 'json',
                        success: function(response) {
                            $('#client-code').val(response.client_code);
                        }
                    });
                }
            });
            
            // Form validation
            $('.needs-validation').submit(function(event) {
                const form = this;
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $(form).addClass('was-validated');
            });
            
            // Delete client button functionality
            $('.delete-client').click(function(e) {
                e.preventDefault();
                const clientId = $(this).data('id');
                const clientName = $(this).data('name');
                
                $('#client-id-to-delete').val(clientId);
                $('#client-name-to-delete').text(clientName);
                $('#deleteClientModal').modal('show');
            });
            
            // Delete contact button functionality
            $('.delete-contact').click(function(e) {
                e.preventDefault();
                const contactId = $(this).data('id');
                const contactName = $(this).data('name');
                
                $('#contact-id-to-delete').val(contactId);
                $('#contact-name-to-delete').text(contactName);
                $('#deleteContactModal').modal('show');
            });
        });
        
        // Alternative direct functions if jQuery event binding doesn't work
        function deleteClient(id, name) {
            document.getElementById('client-id-to-delete').value = id;
            document.getElementById('client-name-to-delete').innerText = name;
            $('#deleteClientModal').modal('show');
        }
        
        function deleteContact(id, name) {
            document.getElementById('contact-id-to-delete').value = id;
            document.getElementById('contact-name-to-delete').innerText = name;
            $('#deleteContactModal').modal('show');
        }
    </script>
</body>
</html>