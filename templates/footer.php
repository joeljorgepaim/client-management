<?php
// templates/footer.php
?>
        </main>
    </div>
    
    <footer class="mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Client Management System</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p class="mb-0">Version 1.0</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Global JavaScript functions
        $(document).ready(function() {
            // Auto-generate client code when name is entered
            $('#client-name').on('blur', function() {
                const name = $(this).val();
                if (name && $('#client-code').val() === '') {
                    $('.client-code-loader').removeClass('d-none');
                    $.ajax({
                        url: '<?php echo BASE_URL; ?>?ajax=generate_client_code',
                        method: 'POST',
                        data: { name: name },
                        dataType: 'json',
                        success: function(response) {
                            $('#client-code').val(response.client_code);
                            $('.client-code-loader').addClass('d-none');
                        },
                        error: function() {
                            $('.client-code-loader').addClass('d-none');
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
            
            // Animate alert fade-out
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000); // Auto close alerts after 5 seconds
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Add animation to cards
            $('.card').addClass('animate__animated animate__fadeIn');
            
            // Add active class to sidebar based on URL
            $(".nav-link").each(function() {
                if (window.location.href.indexOf($(this).attr('href')) > -1) {
                    $(this).addClass('active');
                }
            });
        });
    </script>
</body>
</html>
