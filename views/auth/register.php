<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?>
    </title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css"> -->
    <link rel="stylesheet" href="<?php echo assets('css/signup.css'); ?>">
    <!--ICON LINK-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="flex-item">
        <div class="first-half">
            <div class="container-flex">
                <form method="post">

                    <div class="nav-title">
                        <a href="<?php echo route(''); ?>">
                            <i style="color: #F15025;" class="fa fa-cutlery" aria-hidden="true"></i>
                            <p class="navtext">recipe<span style="color: #F15025;">haven</span></p>
                        </a>
                    </div>

                    <?php if (!empty($errors)): ?>
                        <script>
                            var errorList = "<?php echo implode('\n', $errors); ?>";
                            alert("Errors:\n" + errorList);
                        </script>
                    <?php endif; ?>

                    <div class="titlehead">
                        <div>
                            <p class="titlehead1">Welcome to recipe<span style="color: #F15025;">haven</span></p>
                            <p class="titlehead2">Sign up to view your favourite recipe</p>
                        </div>
                    </div>

                    <div class="signup">
                        <div class="textinput">
                            <i style="color: #F15025;" class="fa fa-envelope icon" aria-hidden="true"></i>
                            <input type="email" id="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <div class="textinput">
                            <i style="color: #F15025;" class="fa fa-user icon" aria-hidden="true"></i>
                            <input type="text" id="first_name" name="first_name" placeholder="Enter your first name" required>
                        </div>

                        <div class="textinput">
                            <i style="color: #F15025;" class="fa fa-user icon" aria-hidden="true"></i>
                            <input type="text" id="last_name" name="last_name" placeholder="Enter your last name" required>
                        </div>

                        <div class="textinput">
                            <i style="color: #F15025;" class="fa fa-unlock-alt icon"></i>
                            <i onclick="toggleVisibility()" style="color: #F15025" class="fa-solid fa-eye"></i>
                            <input id="password" type="password" name="password" placeholder="Enter your Password" required>
                        </div>

                        <div class="signup-btn">
                            <button type="submit">Sign Up</button>
                        </div>

                        <div class="log">
                            Already have an account? <a style="font-weight: 600;" href="<?php echo route('login'); ?>">Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="second-half"></div>
    </div>



    <script>
        function toggleVisibility() {
            let passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>

</html>