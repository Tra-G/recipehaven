<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!--Font icon links-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!--Google Fonts-->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Sono:wght@400;500&family=Ubuntu:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!--Styling Link-->
    <link rel="stylesheet" href="<?php echo assets('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets('css/contact.css'); ?>">
</head>

<body>
    <div class="background"></div>
    <div class="closeicon">
        <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="modal">
        <form class="search-btn" action="<?php echo route('search'); ?>" method="get">
            <input type="text" name="param" placeholder="Type a recipe">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="nav-menu">
        <div class="nav-block">
            <div class="nav-1">
                <div class="nav1">
                    <div>
                        <i class="fa-brands fa-facebook"></i>
                        <i class="fa-brands fa-square-twitter"></i>
                        <i class="fa-brands fa-linkedin"></i>
                        <i class="fa-brands fa-youtube"></i>
                    </div>

                    <div class="submit-icon">
                        <a class="nav-submit" href="<?php echo route('recipe/new'); ?>">
                            <p>submit recipe</p>
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav2">
                <a class="nav-header" href="<?php echo route(''); ?>">
                    <i style="color: #F15025;" class="fa fa-cutlery" aria-hidden="true"></i>
                    <p>recipe<span style="color: #F15025;">haven</span></p>
                </a>

                <div class="navlink">
                    <i style="color: #000;" class="fa fa-times"></i>
                    <a href="<?php echo route(''); ?>">
                        Home
                    </a>

                    <a href="<?php echo route('about'); ?>">
                        About
                    </a>

                    <a href="<?php echo route('contact'); ?>">
                        Contact
                    </a>

                    <a href="<?php echo route('blog'); ?>">
                        Blog
                    </a>

                    <a href="<?php echo route('login'); ?>">
                        Login
                    </a>

                    <a href="<?php echo route('register'); ?>">
                        Sign Up
                    </a>

                </div>

                <div class="nav-icon">
                    <div class="nav-search">
                        <i class="fa searchIcon fa-search"></i>
                    </div>

                    <div class="line navline"></div>

                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <!--BACKGROUND-->
    <div class="bg-contact">
        <div class="bg-text">
            <p>Contact Us</p>
        </div>
    </div>


    <div class="form">
        <div class="form-container1">
            <div class="form-header">
                <p>Get In Touch</p>
            </div>
            <form action="mailto:reciperaven@gmail.com" method="post" enctype="text/plain">
                <div class="flex-item">
                    <input type="text" placeholder="First name">
                    <input type="text" placeholder="Last name">
                </div>

                <div class="flex-item">
                    <input type="email" placeholder="Email address">
                    <input type="text" placeholder="Phone number">
                </div>

                <div class="textarea">
                    <textarea name="text" id="text" cols="35" rows="15" placeholder="Enter your message"></textarea>
                </div>

                <div class="form-btn">
                    <button type="submit">Submit</button>
                </div>

            </form>
        </div>



        <div class="form-container2">
            <div class="form2-header">
                <p>Prefer to reach out directly?</p>
            </div>

            <div>
                <div class="item-container">
                    <div class="item">
                        <div class="flex-text">
                            <i style="color: #F15025;" class="fa-solid fa-envelope"></i>
                            <p class="container2-text">Email Address</p>
                        </div>
                        <div class="flex-text2">
                            reciperaven@gmail.com
                        </div>
                    </div>


                    <div class="item">
                        <div class="flex-text">
                            <i style="color: #F15025;" class="fa-solid fa-phone"></i>
                            <p class="container2-text">Phone Number</p>
                        </div>

                        <div class="flex-text2">
                            +2343-222-4111
                        </div>
                    </div>

                    <div class="item">
                        <div class="flex-text">
                            <i style="color: #F15025;" class="fa-sharp fa-solid fa-location-dot"></i>
                            <p class="container2-text">Our Location</p>
                        </div>

                        <div class="flex-text2">
                            1669 Old Zebulon Rd
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <footer>
        <div class="footer-item">
            <div class="footer-text news">
                <div class="footer-head">
                    <p>Join Our Newsletter</p>
                </div>

                <div class="footer-link">
                    <p>subscribe to get notified about all the news & updates</p>
                </div>

                <div class="input-flex">
                    <input type="text" placeholder="Your email address">
                    <button>sign up</button>
                </div>
            </div>


            <div class="footer-text">
                <div class="footer-head">
                    <p>Quick Links</p>
                </div>

                <div class="anchor-link">
                    <a href="<?php echo route(''); ?>">Home</a>
                    <a href="<?php echo route('about'); ?>">About</a>
                    <a href="<?php echo route('blog'); ?>">Blog</a>
                    <a href="<?php echo route('contact'); ?>">Contact Us</a>
                </div>

            </div>

        </div>
    </footer>




    <script>
        const hamburger = document.querySelector(".hamburger");
        const menu = document.querySelector(".navlink");
        const closeIcon = document.querySelector('.fa-times');

        hamburger.addEventListener("click", function () {
            menu.classList.toggle("show");
        });

        closeIcon.addEventListener('click', () => {
            menu.classList.remove('show');
        });



        let modal = document.querySelector('.modal');
        let background = document.querySelector('.background');
        let searchBtn = document.querySelector('.searchIcon')
        let closebtn = document.querySelector('.closeicon');

        function searchBar() {
            modal.style.display = 'flex';
            background.style.display = 'block';
            closebtn.style.display = 'block';
        }

        function closeBar() {
            modal.style.display = 'none';
            background.style.display = 'none';
            closebtn.style.display = 'none';
        }

        searchBtn.onclick = searchBar;
        closebtn.onclick = closeBar;

    </script>
</body>

</html>