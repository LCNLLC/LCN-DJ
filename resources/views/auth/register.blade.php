@extends('backend.layouts.blank')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

<style>
    .checked-label {
        color: #28a745; /* Green color for confirmation */
        font-weight: bold;
    }
    .iti {
        width: 100%;
    }
</style>
@endsection



@section('content')


<div class="h-100 bg-cover bg-center py-5 d-flex align-items-center" style="background-image: url({{ uploaded_asset(get_setting('admin_login_background')) }})">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-5 mx-auto">
                <div class="card text-left">
                    <div class="card-header">
                        <h5 class=" text-primary mb-0"> {{ translate('Create a New Account') }}</h5>
                       </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">{{  translate('Full Name') }} <span class="text-danger">*</span> </label>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus placeholder="{{ translate('Full Name ') }} "  data-toggle="tooltip" title="{{ translate('Please enter your full name as it appears in official documents.') }}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email-field">{{  translate('Email') }} <span class="text-danger">*</span></label>
                                <input id="email-field" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required placeholder="{{ translate('Email') }}"   data-toggle="tooltip" title="{{ translate('Enter a valid email address, e.g. user@example.com') }}">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                  <!-- Validation message for real-time validation -->
                                  <span id="email-validation-message" class="text-danger mt-1" style="display: none;"></span>
                            </div>
                            <div class="form-group position-relative">
                                <label for="Password">{{  translate('Password') }} <span class="text-danger">*</span></label>
                                <input style="padding-right: 36px;" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ translate('password') }}"  data-toggle="tooltip" title="{{ translate('Password must be at least 6 characters long, contain letters, numbers, and special characters.') }}">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                <!-- Toggle View/Hide Icon for Password -->
                                <div class="input-group-append" style="position:absolute; top:37px; right:12px;">
                                    <button type="button" id="toggle-password" class="btn btn-link p-0">
                                        <i id="toggle-icon" class="fa fa-eye"></i> <!-- Eye Icon -->
                                    </button>
                                </div>
                                 <!-- New Span for guidance text -->
                                 <span id="password-guidance" class="text-danger fs-12" style="display: none;">{{ translate('Please enter letters, numbers, and special characters.') }}</span>

                            </div>
                                    <!-- Password strength meter -->
                                <div id="password-strength" class="mb-2">
                                    <small id="strength-message" class="text-muted"></small>
                                    <div class="progress mt-1" style="height: 5px;">
                                         <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                                     </div>
                                </div>
                            <div class="form-group position-relative">
                                <label for="password-confirm">{{  translate('Confirm Password') }} <span class="text-danger">*</span></label>
                                <input  style="padding-right: 36px;" id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="{{ translate('Confrim Password') }}">
                                <!-- Toggle View/Hide Icon for Confirm Password -->
                                    <div class="input-group-append" style="position:absolute; top:37px; right:12px;">
                                        <button type="button" id="toggle-confirm-password" class="btn btn-link p-0">
                                            <i id="toggle-confirm-icon" class="fa fa-eye"></i> <!-- Eye Icon -->
                                        </button>
                                    </div>
                                        <!-- Confirmation mismatch error message -->
                                    <span id="confirm-password-error" class="text-danger fs-12" style="display: none;">
                                        {{ translate('Passwords do not match.') }}
                                    </span>
                                    <!-- Please type password message -->
                                    <span id="please-type-password-error" class="text-danger fs-12" style="display: none;">
                                        {{ translate('Please type the password first.') }}
                                    </span>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ translate('Mobile number') }}</label>
                                <div>
                                    <input value="{{ old('phone') }}" name="phone" type="tel" class="form-control w-100 @error('phone') is-invalid @enderror" id="phone_number" placeholder="{{ translate('Add mobile number') }}"  data-toggle="tooltip" title="{{ translate('Enter your phone number with country code, e.g. +1 234-567-8901.') }}">
                                </div>
                                @if ($errors->has('phone'))
                                <span   style="width: 100%; font-size: 80%; color: #dc3545;">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                            </div>
                            <div>
                                <label for="birthdate">Birthdate: <span class="text-danger">*</span></label>
                             <input value="{{ old('birthdate') }}" type="date" id="birthdate" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" required>
                             @if ($errors->has('birthdate'))
                             <span style="width: 100%; font-size: 80%; color: #dc3545;">
                                 <strong>{{ $errors->first('birthdate') }}</strong>
                             </span>
                         @endif

                            </div>
                            <div class="checkbox pad-btm text-left my-4">
                                <input id="terms-checkbox" class="magic-checkbox" type="checkbox" required>
                                <label id="terms-label" for="terms-checkbox">
                                    {{ translate('I agree with the Terms and Conditions') }}
                                </label>
                                <span id="checkmark-icon" style="display: none; color: #28a745; margin-left: 5px;">✔</span> <!-- Checkmark icon -->
                            </div>
                            <button onclick="this.form.submit(); this.disabled=true; this.innerHTML='<span class=\'spinner-border spinner-border-sm\' role=\'status\' aria-hidden=\'true\'></span> {{ translate('Please wait...') }}';"
                                type="submit"
                                class="btn btn-primary btn-lg btn-block">
                            {{ translate('Register') }}
                        </button>
                        </form>
                        <div class="mt-3">
                            {{translate('Already have an account')}} ? <a href="{{route('login')}}" class="btn-link mar-rgt text-bold">{{translate('Sign In')}}</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')

<script>
    // Password strength meter and guidance display function
    document.getElementById("password").addEventListener("input", function() {
        const password = this.value;
        const strengthBar = document.getElementById("strength-bar");
        const strengthMessage = document.getElementById("strength-message");
        const guidanceMessage = document.getElementById("password-guidance");

        let strength = 0;
        const hasLetters = /[A-Za-z]/.test(password);
        const hasNumbers = /[0-9]/.test(password);
        const hasSpecialChars = /[^A-Za-z0-9]/.test(password);

        if (password.length >= 6) strength += 1;
        if (hasLetters) strength += 1;
        if (hasNumbers) strength += 1;
        if (hasSpecialChars) strength += 1;

        // Show specific guidance messages
        function showGuidanceMessage() {
            if (password.length < 6) {
                guidanceMessage.textContent = "Password must be at least 6 characters long.";
            } else if (!hasLetters) {
                guidanceMessage.textContent = "Password must contain at least one letter.";
            } else if (!hasNumbers) {
                guidanceMessage.textContent = "Password must contain at least one number.";
            } else if (!hasSpecialChars) {
                guidanceMessage.textContent = "Password must contain at least one special character.";
            } else {
                guidanceMessage.textContent = ""; // Clear message when all conditions are met
            }
            guidanceMessage.style.display = password.length > 0 ? "inline" : "none";
        }

        showGuidanceMessage();

        // Update meter and message based on password strength
        switch (strength) {
            case 0:
                strengthBar.style.width = "0%";
                strengthMessage.textContent = "";
                break;
            case 1:
                strengthBar.style.width = "25%";
                strengthBar.className = "progress-bar bg-danger";
                strengthMessage.textContent = "Weak";
                break;
            case 2:
                strengthBar.style.width = "50%";
                strengthBar.className = "progress-bar bg-warning";
                strengthMessage.textContent = "Medium";
                break;
            case 3:
                strengthBar.style.width = "75%";
                strengthBar.className = "progress-bar bg-info";
                strengthMessage.textContent = "Strong";
                break;
            case 4:
                strengthBar.style.width = "100%";
                strengthBar.className = "progress-bar bg-success";
                strengthMessage.textContent = "Very Strong";
                break;
        }
    });

    // JavaScript to toggle password visibility
    document.getElementById("toggle-password").addEventListener("click", function() {
        var passwordField = document.getElementById("password");
        var toggleIcon = document.getElementById("toggle-icon");
        // Toggle password visibility
        if (passwordField.type === "password") {
            passwordField.type = "text"; // Show password
            toggleIcon.classList.remove("fa-eye"); // Change icon to "eye-slash"
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password"; // Hide password
            toggleIcon.classList.remove("fa-eye-slash"); // Change icon to "eye"
            toggleIcon.classList.add("fa-eye");
        }
    });

    // Toggle visibility for the confirmation password field
    document.getElementById("toggle-confirm-password").addEventListener("click", function() {
        var confirmPasswordField = document.getElementById("password-confirm");
        var toggleConfirmIcon = document.getElementById("toggle-confirm-icon");
        // Toggle confirm password visibility
        if (confirmPasswordField.type === "password") {
            confirmPasswordField.type = "text"; // Show confirm password
            toggleConfirmIcon.classList.remove("fa-eye"); // Change icon to "eye-slash"
            toggleConfirmIcon.classList.add("fa-eye-slash");
        } else {
            confirmPasswordField.type = "password"; // Hide confirm password
            toggleConfirmIcon.classList.remove("fa-eye-slash"); // Change icon to "eye"
            toggleConfirmIcon.classList.add("fa-eye");
        }
    });
</script>

<!-- Real-Time Password Confirmation Validation JavaScript -->
<script>
    document.getElementById("password-confirm").addEventListener("input", function () {
        var password = document.getElementById("password").value;
        var confirmPassword = this.value;
        var confirmPasswordError = document.getElementById("confirm-password-error");
        var pleaseTypePasswordError = document.getElementById("please-type-password-error");

        // If Password field is empty and Confirm Password is being typed
        if (password === "" && confirmPassword !== "") {
            pleaseTypePasswordError.style.display = "block"; // Show "Please type the password first."
            confirmPasswordError.style.display = "none"; // Hide the password mismatch error
        } else {
            pleaseTypePasswordError.style.display = "none"; // Hide the "Please type the password first."

            // Check if the passwords match
            if (confirmPassword !== password) {
                confirmPasswordError.style.display = "block"; // Show the mismatch error
            } else {
                confirmPasswordError.style.display = "none"; // Hide the mismatch error
            }
        }
    });
</script>
<script>
      document.getElementById("terms-checkbox").addEventListener("change", function () {
        var termsLabel = document.getElementById("terms-label");
        var checkmarkIcon = document.getElementById("checkmark-icon");

        // Toggle label color and icon based on checkbox state
        if (this.checked) {
            termsLabel.classList.add("checked-label"); // Apply color
            checkmarkIcon.style.display = "inline";    // Show checkmark icon
        } else {
            termsLabel.classList.remove("checked-label"); // Remove color
            checkmarkIcon.style.display = "none";        // Hide checkmark icon
        }
    });
</script>
<script>
    document.getElementById("email-field").addEventListener("input", function () {
        var emailField = this;
        var email = emailField.value;
        var validationMessage = document.getElementById("email-validation-message");

        // Regular Expression for Email Format Validation
        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

        // Check if email is valid
        if (!email.match(emailRegex)) {
            // Show validation error if email is invalid
            emailField.classList.add("is-invalid");
            validationMessage.style.display = "block";
            validationMessage.innerHTML = "{{ translate('Please enter a valid email address.') }}";
        } else {
            // Hide validation error if email is valid
            emailField.classList.remove("is-invalid");
            validationMessage.style.display = "none";
        }
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.querySelector("#phone_number");

        // Initialize intl-tel-input on the phone number input field
        var iti = window.intlTelInput(input, {
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js", // Required for formatting
            initialCountry: "auto", // Auto-detect country based on IP address
            geoIpLookup: function(callback) {
                fetch('https://ipinfo.io/json', { headers: { 'Accept': 'application/json' } })
                    .then(response => response.json())
                    .then(data => callback(data.country))
                    .catch(() => callback('us')); // Fallback to 'us' if IP lookup fails
            },
            nationalMode: false, // Ensures that the number is shown with the country code
            preferredCountries: ['us', 'gb', 'ca'] // Optional: Specify countries you want to appear first
        });

        // To ensure the country code appears in the input field
        input.addEventListener('input', function() {
            var phoneNumberWithCode = iti.getNumber(); // Gets the number with country code
            input.value = phoneNumberWithCode; // Updates the input field
        });
    });
    $(document).ready(function () {
    // Enable tooltips globally for elements with data-toggle="tooltip"
    $('[data-toggle="tooltip"]').tooltip();
});
</script>


@endsection
