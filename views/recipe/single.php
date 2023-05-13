<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?>
    </title>
    <!--Font icon links-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!--Google Fonts-->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Sono:wght@400;500&family=Ubuntu:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!--Styling Link-->
    <link rel="stylesheet" href="<?php echo assets('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets('css/recipedetails.css'); ?>">
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

    <!--Background-->
    <div>
        <div class="recipe-image">
            <div class="recipe-texts">
                <p>
                    <?php echo $recipe['title']; ?>
                </p>
                <form action="#">
                    <?php if ($logged): ?>
                        <?php if ($saved): ?>
                            <button id="save-button" href="<?php echo route('recipe/' . $recipe['id'] . '/unsave'); ?>">Remove
                                from saved <i class="fa fa-heart"></i></button>
                        <?php else: ?>
                            <button id="save-button" href="<?php echo route('recipe/' . $recipe['id'] . '/save'); ?>">Save
                                recipe <i class="fa fa-heart"></i></button>
                        <?php endif; ?>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="recipe-details">
        <div style="display: flex; justify-content: space-between;">
            <div class="author">
                <header class="desc-header">
                    Author
                </header>
                <p>
                    <?php echo $recipe['first_name'] . ' ' . $recipe['last_name']; ?>
                </p>
            </div>
            <div class="date">
                <header class="desc-header">
                    Created
                </header>
                <p>
                    <?php echo $recipe['created_at']; ?>
                </p>
            </div>

            <div class="views">
                <header class="desc-header">
                    Views
                </header>
                <p>
                    <?php echo $views; ?>
                </p>
            </div>

            <div class="rating">
                <header class="desc-header">
                    Ratings
                </header>
                <p>
                    <?php if ($ratings['average'] > 0): ?>
                        <?php echo $ratings['average']; ?>/5
                    <?php else: ?>
                        No ratings yet
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="desc">
            <header class="desc-header">
                Directions
            </header>

            <p>
                <?php echo nl2br($recipe['directions']); ?>
            </p>
        </div>

        <div class="ingredient">
            <header class="desc-header">
                Ingredient
            </header>
            <div class="ingredient-list">
                <?php echo nl2br($recipe['ingredients']); ?>
            </div>
        </div>

        <div class="categories">
            <header class="desc-header">
                Categories
            </header>
            <p>
                <?php echo $recipe['categories']; ?>
            </p>
        </div>

        <div class="categories">
            <header class="desc-header">
                Prepation Time
            </header>
            <p>
                <?php echo $recipe['prep_time']; ?> minutes
            </p>
        </div>

        <div class="categories">
            <header class="desc-header">
                Servings
            </header>
            <p>
                <?php echo $recipe['servings']; ?> servings
            </p>
        </div>

        <?php if ($logged): ?>
            <div class="categories">
                <header class="desc-header">
                    Rate Recipe
                </header>
                <div id="rating">
                    <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/1'); ?>">★</a>
                    <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/2'); ?>">★</a>
                    <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/3'); ?>">★</a>
                    <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/4'); ?>">★</a>
                    <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/5'); ?>">★</a>
                </div>
            </div>
        <?php endif; ?>
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


        // Save/Remove Recipe
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('save-button').addEventListener('click', function (event) {
                event.preventDefault(); // prevent default behavior of link
                var url = this.getAttribute('href'); // get URL from href attribute

                // Add a confirmation dialog box to confirm save/remove
                if (confirm("Are you sure you want to save/remove this recipe?")) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', url, true);
                    xhr.onreadystatechange = function () {
                        if (this.readyState === 4 && this.status === 200) {
                            var response = this.responseText;
                            // if response is "Recipe saved" change link to "Remove"
                            if (response === 'Recipe saved') {
                                location.reload();
                            }
                            // if response is "Recipe removed" change link to "Save"
                            else if (response === 'Recipe removed') {
                                location.reload();
                            }
                            // else alert response
                            else {
                                alert(response);
                            }
                        }
                    };
                    xhr.send();
                }
            });
        });

        // Rating
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('rating').addEventListener('click', function (event) {
                event.preventDefault(); // prevent default behavior of link
                var url = event.target.getAttribute('href'); // get URL from href attribute
                var xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                xhr.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        var response = this.responseText;
                        // if response is "Recipe rated" display message
                        if (response === 'Recipe rated') {
                            alert('Recipe rated successfully');
                            location.reload();
                        }
                        // else alert response
                        else {
                            alert(response);
                        }
                    }
                };
                xhr.send();
            });
        });
    </script>
</body>

</html>