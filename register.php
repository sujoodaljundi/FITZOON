<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup/Login</title>
    <link rel="icon" href="./img/icon.svg">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Raleway:300,600" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel="stylesheet" href="./css/login_reg.css">
</head>
<body>

<div class="container">
    <section id="formHolder">
        <div class="row">
            <div class="col-sm-6 brand">
                <div class="heading">
                    <img src="./img/logo.png" alt="logo" class="logo" width="100%">
                </div>
            </div>

            <div class="col-sm-6 form">
                <div class="login form-peice switched">
                    <form id="loginForm" class="login-form"  method="post">
                        <div class="form-group">
                            <br>
                            <br>
                            <h1>Sign In!</h1>
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                        <div class="CTA">
                            <input type="submit" value="Login">
                            <a href="#" class="switch">I'm New</a>
                        </div>
                    </form>
                </div>

                <div class="signup form-peice">
                    <form class="signup-form " id="registerForm"  method="post">
                        <div class="form-group">
                            <h3 >Sign Up!</h3>
                            <label for="user_name">Full Name</label>
                            <input type="text" name="user_name" id="name" class="name" required>
                            <span class="error"></span>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="email" required>
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="pass" required>
                            <span class="error"></span>
                        </div>
                        
                        <div class="form-group">
                            <select class="form-select" name="country" id="country" required>
                                <option value="" disabled selected>Select your country</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number - <small>Optional</small></label>
                            <input type="tel" name="phone_number" id="phone" pattern="[0-9]{10,14}">
                        </div>
                        <div class="CTA">
                            <input type="submit" value="Signup Now" id="submit" name="register">
                            <a href="#" class="switch">I have an account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("https://restcountries.com/v3.1/all")
            .then((response) => response.json())
            .then((data) => {
                const countrySelect = document.getElementById("country");
                data.forEach((country) => {
                    const option = document.createElement("option");
                    option.value = country.name.common;
                    option.textContent = country.name.common;
                    countrySelect.appendChild(option);
                });
            })
            .catch((error) => console.error("Error fetching countries:", error));
    });

    $(document).ready(function () {
        'use strict';

        var user_name_Error = true,
            emailError = true,
            passwordError = true,
            passConfirmError = true;

        // Label effect
        $('input').focus(function () {
            $(this).siblings('label').addClass('active');
        });

        // Form validation
        $('input[name="user_name"]').blur(function () {
            if ($(this).val().length === 0) {
                $(this).siblings('span.error').text('Please type your full name').fadeIn().parent('.form-group').addClass('hasError');
                user_name_Error = true;
            } else if ($(this).val().length < 4) {
                $(this).siblings('span.error').text('Please type at least 4 characters').fadeIn().parent('.form-group').addClass('hasError');
                user_name_Error = true;
            } else {
                $(this).siblings('.error').text('').fadeOut().parent('.form-group').removeClass('hasError');
                user_name_Error = false;
            }
        });

        $('input[name="email"]').blur(function () {
            if ($(this).val().length === 0) {
                $(this).siblings('span.error').text('Please type your email address').fadeIn().parent('.form-group').addClass('hasError');
                emailError = true;
            } else {
                $(this).siblings('.error').text('').fadeOut().parent('.form-group').removeClass('hasError');
                emailError = false;
            }
        });

        $('input[name="password"]').blur(function () {
            var passwordVal = $(this).val();
            var hasNumber = /\d/.test(passwordVal);
            var hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(passwordVal);
            if (passwordVal.length < 8) {
                $(this).siblings('span.error').text('Please type at least 8 characters').fadeIn().parent('.form-group').addClass('hasError');
                passwordError = true;
            } else if (!hasNumber) {
                $(this).siblings('span.error').text('Password must contain at least one number').fadeIn().parent('.form-group').addClass('hasError');
                passwordError = true;
            } else if (!hasSpecialChar) {
                $(this).siblings('span.error').text('Password must contain at least one special character').fadeIn().parent('.form-group').addClass('hasError');
                passwordError = true;
            } else {
                $(this).siblings('.error').text('').fadeOut().parent('.form-group').removeClass('hasError');
                passwordError = false;
            }
        });

       

        
        $('.signup-form').submit(function (e) {
            if (user_name_Error || emailError || passwordError ) {
                e.preventDefault(); 
                alert('Please correct the errors before submitting.');
            }
        });

        // Form switch
        $('a.switch').click(function (e) {
            $(this).toggleClass('active');
            e.preventDefault();
            if ($(this).hasClass('active')) {
                $(this).parents('.form-peice').addClass('switched').siblings('.form-peice').removeClass('switched');
            } else {
                $(this).parents('.form-peice').removeClass('switched').siblings('.form-peice').addClass('switched');
            }
        });
    });




document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("helper_functions/signin.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                position: 'top-center',
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = './index.php';
            });
        } else {
            Swal.fire({
                position: 'top-center',
                icon: 'error',
                title: data.message,
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => console.error("Error:", error));
});



document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent form from submitting normally

        // Gather form data
        const formData = new FormData(this);

        // AJAX request
        fetch('helper_functions/signup.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Display success message
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                    confirmButtonText: 'Go to Home'
                }).then(() => {
                    window.location.href = './index.php';
                });
            } else {
                // Display error message
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonText: 'Try Again'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong! Please try again later.',
                confirmButtonText: 'OK'
            });
        });
    });
});


</script>
</body>
</html>
