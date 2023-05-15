<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Font icon links-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!--Google Fonts-->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Sono:wght@400;500&family=Ubuntu:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!--Styling Link-->
    <link rel="stylesheet" href="<?php echo assets('css/style.css'); ?>">
    <!--Swiper link-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <title><?php echo $title; ?></title>
</head>

<body>
    <div class="background"></div>
    <div class="closeicon">
        <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="modal">
        <form class="search-btn" action="<?php echo route('search'); ?>" method="get">
            <input type="text" name="param" placeholder="Type a recipe">
            <button><i style="font-size: 1.2rem;" class="fa-solid fa-magnifying-glass"></i></button>
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
                    <i style="color: #F15025;" class="fa fa-cutlery"></i>
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


    <!--Background-->
    <div class="containerbox">
        <swiper-container class="sms" navigation="false" fade="true" clickable="true" pagination="true" effect="fade"
            space-between="30" centered-slides="true" autoplay-delay="5500" loop="true"
            autoplay-disable-on-interaction="false">
            <swiper-slide class="slide1">
                <div class="card-details">
                    <div>
                        <p class="cardheader">Vanilla Cake</p>
                        <p class="cardtext">Sweeten Up Your Day with a Slice of Moist and Fluffy Cake</p>
                        <span class="cardbutton">
                            <a href="#">
                                View Recipe
                            </a>
                        </span>
                    </div>
                </div>
            </swiper-slide>

            <swiper-slide class="slide2">
                <div class="card-details">
                    <div>
                        <p class="cardheader">Browny Cookies</p>
                        <p class="cardtext">Decadent Delight: Indulge in Rich and Fudgy Brownie Cookies</p>
                        <span class="cardbutton">
                            <a href="#">
                                View Recipe
                            </a>
                        </span>
                    </div>
                </div>
            </swiper-slide>

            <swiper-slide class="slide3">
                <div class="card-details">
                    <div>
                        <p class="cardheader">Caramel Cookies</p>
                        <p class="cardtext">Experience Blissful Sweetness with Homemade Caramel Cookies</p>
                        <span class="cardbutton">
                            <a href="#">
                                View Recipe
                            </a>
                        </span>
                    </div>
                </div>
            </swiper-slide>

        </swiper-container>
    </div>
    </div>

    <!--First Container-->
    <section class="slider-container">
        <header class="slidehead">
            <p>Popular Recipes</p>
        </header>
        <div class="container">
            <div class="swiper card">
                <div class="swiper-wrapper">

                    <?php foreach($most_viewed as $popular): ?>
                        <div class="swiper-slide">
                            <div class="img-box">
                                <img src="<?php echo assets('recipe-images/'.$popular['image']); ?>" alt="">
                                <header class="swiper-header">
                                    <h1><?php echo $popular['title']; ?></h1>
                                </header>
                                <div class="viewbtn">
                                    <a class="viewrecipe" href="<?php echo route('recipe/'.$popular['id']); ?>">View recipe</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
                <div style="color: #000; background-color: #fff; padding: .3rem;" class="swiper-button-next"></div>
                <div style="color: #000; background-color: #fff; padding: .3rem;" class="swiper-button-prev"></div>
            </div>
        </div>
    </section>



    <!--SUBCRIBE-->
    <div class="sub-container">
        <div class="sub-item">
            <header class="sub-head">
                <p class="head1">Check Out The Latest From our Blog</p>
                <p class="head2" style="margin-bottom: .5rem; padding: .5rem 0;">To receive updates on the latest recipe
                </p>
            </header>

            <a class="sub-btn" style="color: #fff;" href="<?php echo route('blog'); ?>">View updates</a>
        </div>
    </div>


    <!--THIRD CONTAINER-->
    <section class="slider-container">
        <header class="slidehead">
            <p>Recent Recipes</p>
        </header>
        <div class="container">
            <div class="swiper card">
                <div class="swiper-wrapper">

                    <?php foreach($most_recent as $recent): ?>
                        <div class="swiper-slide">
                            <div class="img-box">
                                <img src="<?php echo assets('recipe-images/'.$recent['image']); ?>" alt="">
                                <header class="swiper-header">
                                    <h1><?php echo $recent['title']; ?></h1>
                                </header>
                                <div class="viewbtn">
                                    <a class="viewrecipe" href="<?php echo route('recipe/'.$recent['id']); ?>">View recipe</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>


                </div>
                <div style="color: #000; background-color: #fff; padding: .3rem;" class="swiper-button-next"></div>
                <div style="color: #000; background-color: #fff; padding: .3rem;" class="swiper-button-prev"></div>
            </div>
        </div>
    </section>




    <!--footer-->
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

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-element-bundle.min.js"></script>
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




        /*Swiper Js*/
        var swiper = new Swiper(".card", {
            slidesPerView: 3,
            loop: true,
            speed: 1000,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                480: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
            },
        });

    </script>
</body>

</html>