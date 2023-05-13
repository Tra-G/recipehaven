<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!--Font icon links-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!--Google Fonts-->
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Sono:wght@400;500&family=Ubuntu:wght@400;500;700&display=swap"
        rel="stylesheet">
    <!--Styling Link-->
    <link rel="stylesheet" href="<?php echo assets('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets('css/submitrecipe.css'); ?>">
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
    <div class="submitrecipe-container">
        <div class="submit">
            <header class="submitrecipe-header">
                Add a Recipe
            </header>

            <?php if ($errors): ?>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <script>alert('<?php echo $error; ?>')</script>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <div class="submitrecipe-item">
                <form method="POST" enctype="multipart/form-data">
                    <div class="submitrecipe-form">
                        <label for="title">Recipe Title</label>
                        <input type="text" placeholder="Give your recipe a title" id="title" name="title" required>
                    </div>

                    <div class="submitrecipe-form">
                        <label for="image">Photo</label>
                        <input type="file" id="image" name="image" required>
                    </div>

                    <div class="submitrecipe-form">
                        <label for="directions">Directions</label>
                        <textarea name="directions" id="text" cols="30" rows="10" placeholder="Give a direction on how to prepare the recipe"></textarea>
                    </div>

                    <div id="submitrecipe-form" class="submitrecipe-form ingredients-recipe">
                        <label for="ingredients">Ingredients</label>
                        <textarea id="text" name="ingredients" cols="30" rows="10" placeholder="Write out the ingredients" required></textarea>
                    </div>


                    <div class="submitrecipe-form">
                        <label for="servings">Servings</label>
                        <input type="number" placeholder="e.g 8" id="servings" name="servings" required>
                    </div>

                    <div class="submitrecipe-form">
                        <label for="prep_time">Prep Time</label>
                        <div style="display: flex;">
                            <input style="width: 50%;" type="number" placeholder="e.g 30" id="prep_time" name="prep_time" required>
                            <select
                                style="padding-left: 1rem; margin-left: 1rem; width: 50%; font-size: 1rem; outline: none;"
                                name="prep-time" id="prep-time" disabled>
                                <option value="min">minutes</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: flex;" class="submitrecipe-form">
                        <label for="prep-time">Categories</label>
                        <select style="padding-left: 1rem; margin-left: 1rem; font-size: 1rem; outline: none; width: 100%;"
                        id="categories" name="categories[]" multiple required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>


                    <button class="submitrecipe-btn" type="submit">Submit</button>


                </form>

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