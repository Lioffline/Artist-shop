<?php
declare(strict_types=1);

// Настройка навигационного поля
function nav_bar_show()
{
    ?>
    <nav class="navbar fixed-navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="index.php">
                    <i class="fas fa-palette mr-2"></i> Home
                </a>

                <?php if (isset($_SESSION["user_id"])): ?>
                    <a class="navbar-item" href="create_post.php">
                        <span class="icon">
                            <i class="fas fa-plus"></i>
                        </span>
                        <strong>Create</strong>
                    </a>
                <?php endif; ?>
            </div>

            <div class="navbar-item" style="flex-grow: 1; display: flex;">
                <form class="field has-addons" action="search.php" method="post" style="flex-grow: 1; display: flex;">
                    <div class="control" style="flex-grow: 1;">
                        <input type="text" class="input" name="usersearch" placeholder="Search..." style="width: 100%; height: 100%;">
                    </div>
                    <div class="control">
                        <button class="button is-primary" style="height: 100%;">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if (isset($_SESSION["user_id"])): ?>
                        <a class="button ml-2" href="chats.php" style="background: none; border: none; border-radius: 50%;">
                            <span class="icon">
                                <i class="fas fa-comments" style="color: #CCCCCC;"></i>
                            </span>
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <?php if (isset($_SESSION["user_id"])): ?>
                            <div class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link">
                                    <i class="fas fa-user icon-user"></i>
                                    <?php echo htmlspecialchars($_SESSION["username"]); ?>
                                </a>
                                <div class="navbar-dropdown is-right">
                                    <a class="navbar-item" href="includes/profile.inc.php">
                                        Profile
                                    </a>
                                    <a class="navbar-item" href="manage_balance.php">
                                        Balance
                                    </a>
                                    <a class="navbar-item" href="edit_profile.php">
                                        Edit
                                    </a>
                                    <hr class="navbar-divider">
                                    <a class="navbar-item" href="signup.php">
                                        New account
                                    </a>
                                    <a class="navbar-item" href="login.php">
                                        New login
                                    </a>
                                    <hr class="navbar-divider">
                                    <form action="includes/logout.inc.php" method="post">
                                        <button class="button is-danger navbar-item">
                                            <span style="color: #ff6685;">Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!isset($_SESSION["user_id"])): ?>
                            <a class="button is-primary is-outlined" href="signup.php"><strong>Sign up</strong></a>
                            <button class="button is-light is-outlined" onclick="location.href='login.php';return false;">Log in</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>
<?php
}
?>