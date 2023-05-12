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
    <link rel="stylesheet" href="<?php echo assets('css/login.css'); ?>">
    <!--ICON LINK-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="background"></div>
    <form id="myForm" onsubmit="return submitForm()" method="post" class="modal">
        <div class="modal-header">
            <p>Forgot Your Password?</p>
        </div>

        <div id="result" class="modal-text">
            Not to worry, Just enter your email to reset password.
        </div>

        <div class="email-modal">
            <input class="email-input" type="email" name="email" placeholder="Enter your email">
        </div>

        <div class="email-modal">
            <button type="submit">Reset Password</button>
        </div>

        <div class="email-modal closebtn">
            <button type="submit">Go back</button>
        </div>
    </form>

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

                    <div class="titlehead">
                        <div>
                            <p class="titlehead1">
                                Welcome to recipe<span style="color: #F15025">haven</span>
                            </p>
                            <?php if (isset($errors) && count($errors) > 0): ?>
                                <div class="alert alert-danger">
                                    <?php foreach ($errors as $error): ?>
                                        <p class="titlehead2">
                                            <?php echo $error ?>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="titlehead2">Sign in to view your favourite recipe</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="signup">
                        <div>
                            <div class="textinput">
                                <i style="color: #F15025" class="fa icon fa-envelope" aria-hidden="true"></i>
                                <input type="email" id="email" name="email" placeholder="Email" required>
                            </div>

                            <div class="textinput">
                                <div><i style="color: #F15025" class="fa icon fa-unlock-alt" aria-hidden="true"></i>
                                </div>

                                <i onclick="toggleVisibility()" style="color: #F15025" class="fa-solid fa-eye"></i>
                                <div class="input-wid">
                                    <input id="password" name="password" type="password" placeholder="Password">
                                </div>

                            </div>

                            <div class="reminder">
                                <div>
                                    <input type="checkbox" checked>
                                    <span>Remember me</span>
                                </div>

                                <div class="forgot-password">
                                    <a>Forgot password?</a>
                                </div>
                            </div>

                            <div class="signup-btn">
                                <button type="submit">Login</button>
                            </div>

                            <div class="log">
                                <span>Don't have an account?</span>
                                <a href="<?php echo route('register'); ?>">Sign up</a>
                            </div>

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


        let modal = document.querySelector('.modal');
        let background = document.querySelector('.background');
        let closeBtn = document.querySelector('.closebtn');
        let forgotPassword = document.querySelector('.forgot-password');

        function openForm() {
            background.style.display = 'block';
            modal.style.display = 'block';
        }

        function closeForm() {
            background.style.display = 'none';
            modal.style.display = 'none';
        }

        forgotPassword.onclick = openForm;
        closeBtn.onclick = closeForm;



        //FORGOT PASSWORD
        function submitForm() {
            var xhr = new XMLHttpRequest();
            var formData = new FormData(document.getElementById("myForm"));
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    // document.getElementById("result").innerHTML = xhr.responseText;
                    const modalContent = document.querySelectorAll('.email-modal');
                    modalContent.forEach(item => item.style.display = 'none');
                    alert(xhr.responseText);
                    location.reload();
                }
            };
            xhr.open("POST", "reset", true);
            xhr.send(formData);
            return false;
        }

    </script>
</body>

</html>