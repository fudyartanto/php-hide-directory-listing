<?php
session_start();
$accounts = [
    'admin' => '6a59f50d6c909b1f7937a49998635116'
];

function url($path)
{
    return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . trim($path, '/');
}

function isLoggedIn()
{
    return (isset($_SESSION['user']));
}

function jumpToLastPage()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    jumpToLastPage();
}

if ($_POST['username'] && $_POST['password']) {
    if (isset($accounts[$_POST['username']]) && md5($_POST['password']) == $accounts[$_POST['username']]) {
        $_SESSION['user'] = $accounts[$_POST['username']];
        jumpToLastPage();
    }
}

$exclude = ['.', '..', '.htaccess', 'index.php'];
$path = __DIR__ . $_SERVER['REQUEST_URI'];
$dirs = scandir($path);
foreach ($dirs as $k => $v) {
    if (in_array($v, $exclude) || !is_dir($path)) {
        unset($dirs[$k]);
    }
}

if (isset($_GET['p'])) {
    echo md5($_GET['p']);
}
?>
<html>
    <head>
        <title>Directory Protected</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css"/>
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css" type="text/css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
        <link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.indigo-purple.min.css" />
        <style>
            main {
                padding-top: 40px;
            }
            .mdl-list__item .mdl-list__item-avatar{
                font-size: 28px;
                text-align: center;
                line-height: 40px;
            }
            .mdl-list__item, .mdl-list__item:hover, .mdl-list__item:focus, .mdl-list__item:active {
                text-decoration: none;
            }
            .mdl-card {
                margin-bottom: 20px;
            }
            .aux {
                max-width: 980px;
                max-width: 20rem;
                margin: 0 auto;
            }
            .mdl-menu__item a {
                color: inherit;
                text-decoration: none;
                display: block;
            }
            .mdl-card {
                min-height: auto;
            }
        </style>
    </head>
    <body>
        <div class="mdl-layout mdl-js-layout">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row" style="padding: 0 40px">
                    <span class="mdl-layout-title"></span>
                    <div class="mdl-layout-spacer"></div>
                    <nav class="mdl-navigation">
                        <a class="mdl-navigation__link" href="#"></a>
                    </nav>
                    <!-- Right aligned menu below button -->
                    <button id="demo-menu-lower-right"
                            class="mdl-button mdl-js-button mdl-button--icon">
                    <i class="material-icons">more_vert</i>
                    </button>

                    <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                        for="demo-menu-lower-right">
                        <li class="mdl-menu__item"><a href="<?php echo url('/?logout');?>">Logout</a></li>
                    </ul>
                </div>
            </header>
            <main class="mdl-layout__content">
                <div class="aux">
                    <?php if (isLoggedIn()) :?>
                        <?php foreach ($dirs as $k => $dir) :?>
                        <div class="mdl-card mdl-shadow--2dp">
                            <div class="mdl-card__actions mdl-card--border" style="text-align: right">
                                <div class="mdl-list__item">
                                    <h2 class="mdl-card__title-text">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">folder</i>
                                            <span><?php echo $dir?></span>
                                        </span>
                                    </h2>
                                </div>
                                <a class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" href="<?php echo url($dir);?>">
                                    View
                                </a>
                            </div>
                        </div>
                        <?php endforeach;?>
                    <?php else :?>
                        <form action="" class="text-center" method="post">
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" name="username">
                                <label class="mdl-textfield__label" for="sample3">Username</label>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="password" name="password">
                                <label class="mdl-textfield__label" for="sample3">Password</label>
                            </div>
                            <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                                Login
                            </button>
                        </form>
                    <?php endif;?>
                </div>
            </main>
        </div>
        <script defer src="https://code.getmdl.io/1.2.1/material.min.js"></script>
    </body>
</html>
