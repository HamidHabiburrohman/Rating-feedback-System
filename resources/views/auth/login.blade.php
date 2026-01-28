<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unit Rating Feedback - Admin Login</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png">
    <link rel="stylesheet" href="../assets/css/styles.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="../assets/images/logos/logo.svg" alt="">
                                </a>
                                <h5 class="text-center mb-1">Unit Rating Feedback System</h5>
                                <p class="text-center text-muted mb-4">Admin Dashboard Access</p>

                                <div id="messageContainer" class="mb-3" style="display: none;">
                                    <div class="alert" id="alertMessage">
                                        <span id="messageText"></span>
                                    </div>
                                </div>

                                <form id="loginForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                            autofocus placeholder="admin@university.edu">
                                        <div class="invalid-feedback" id="emailError"></div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required placeholder="Enter your password">
                                        <div class="invalid-feedback" id="passwordError"></div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input primary" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label text-dark" for="remember">
                                                Remember me (30 days)
                                            </label>
                                        </div>
                                        <a class="text-primary fw-bold" href="#" id="forgotPassword">Forgot Password?</a>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4" id="submitBtn">
                                        <span id="btnText">Sign In</span>
                                        <span id="btnSpinner" style="display: none;">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                            Logging in...
                                        </span>
                                    </button>

                                    <div class="text-center">
                                        <p class="text-muted mb-0">For visitor access, go to <a href="/">main page</a></p>
                                        <p class="text-muted small mt-2">Only admin accounts can login to this dashboard</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            const savedEmail = localStorage.getItem('saved_email');
            if (savedEmail) {
                $('#email').val(savedEmail);
                $('#remember').prop('checked', true);
            }

            $('#loginForm').on('submit', function (e) {
                e.preventDefault();
                resetFormErrors();
                hideMessage();

                const email = $('#email').val().trim();
                const password = $('#password').val();

                if (!email || !password) {
                    showMessage('Please fill in all fields', 'danger');
                    return;
                }

                setButtonLoading(true);

                $.ajax({
                    url: '/login',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success && response.data) {
                            handleLoginSuccess(response.data);
                        } else {
                            showMessage(response.message || 'Login failed', 'danger');
                            setButtonLoading(false);
                        }
                    },
                    error: function (xhr) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            showMessage(response.message || 'Login failed', 'danger');
                        } catch (e) {
                            showMessage('Server error', 'danger');
                        }
                        setButtonLoading(false);
                    }
                });
            });

            $('#remember').change(function () {
                if ($(this).is(':checked')) {
                    localStorage.setItem('saved_email', $('#email').val());
                } else {
                    localStorage.removeItem('saved_email');
                }
            });

            $('#forgotPassword').click(function (e) {
                e.preventDefault();
                showMessage('Please contact administrator to reset password.', 'info');
            });

            function handleLoginSuccess(data) {
                localStorage.setItem('auth_token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));

                showMessage('Login successful! Redirecting...', 'success');

                setTimeout(() => {
                    window.location.href = '/admin/dashboard';
                }, 1000);
            }

            function setButtonLoading(loading) {
                const btn = $('#submitBtn');
                const btnText = $('#btnText');
                const btnSpinner = $('#btnSpinner');

                if (loading) {
                    btn.prop('disabled', true);
                    btnText.hide();
                    btnSpinner.show();
                } else {
                    btn.prop('disabled', false);
                    btnText.show();
                    btnSpinner.hide();
                }
            }

            function showMessage(message, type) {
                const container = $('#messageContainer');
                const alert = $('#alertMessage');
                const text = $('#messageText');

                alert.removeClass('alert-success alert-danger alert-info alert-warning');
                alert.addClass('alert-' + type);

                text.text(message);
                container.slideDown();

                if (type === 'success') {
                    setTimeout(hideMessage, 5000);
                }
            }

            function hideMessage() {
                $('#messageContainer').slideUp();
            }

            function showFieldError(field, message) {
                $('#' + field).addClass('is-invalid');
                $('#' + field + 'Error').text(message);
            }

            function resetFormErrors() {
                $('#email, #password').removeClass('is-invalid');
                $('#emailError, #passwordError').text('');
            }
        });
    </script>
</body>
</html>