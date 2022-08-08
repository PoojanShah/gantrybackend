<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title><?= $data['page_title']; ?> - Admin Panel</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/css/ready.css">
    <link rel="stylesheet" href="/assets/css/demo.css">
</head>
<body>
<?php
    $link = $_SERVER['REQUEST_URI'];

    $link = explode('/', $link);
    if(isset($link[3])) {
    $last_link = $link[3];
    }
    else {
        $last_link = '';
    }
    if(isset($link[2])) {
        $link = $link[2];
    }
    else {
        $link = '';
    }
?>
<div class="wrapper">
    <div class="main-header">
        <div class="logo-header">
            <a href="/admin/" class="logo">
                Admin Panel
            </a>
            <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
        </div>
        <nav class="navbar navbar-header navbar-expand-lg">
            <div class="container-fluid">

                <?php if($link == 'products-test') { ?>
                    
					
                <?php } ?>


                <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="la la-user" style="font-size: 25px; position: relative; top: 4px; left: 8px;"></i>
                            <span><?= $data['user']->name; ?></span></span>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li>
                                <div class="user-box">
                                    <div class="u-text">
                                        <h4><?= $data['user']->name; ?></h4>
                                        <p class="text-muted"><?= $data['user']->email; ?></p><a href="/admin/profile/" class="btn btn-rounded btn-danger btn-sm">Edit Profile</a></div>
                                </div>
                            </li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" style="cursor: pointer;" href="/admin/logout/"><i class="fa fa-power-off"></i> Logout</a>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="sidebar">
        <div class="scrollbar-inner sidebar-wrapper">
            <ul class="nav">
                <?php /*
                <li class="nav-item <?php if($link == '') { ?>active<?php } ?>">
                    <a href="/admin/">
                        <i class="la la-dashboard"></i>
                        <p>Заказы</p>
                    </a>
                </li>

                <li class="nav-item <?php if($link == 'customers') { ?>active<?php } ?>">
                    <a href="/admin/customers/">
                        <i class="la la-user"></i>
                        <p>Зарегистрированные клиенты</p>
                    </a>
                </li>

                <li class="nav-item <?php if($link == 'promocodes') { ?>active<?php } ?>">
                    <a href="/admin/promocodes/">
                        <i class="la la-bookmark"></i>
                        <p>Промокоды</p>
                    </a>
                </li>


				<li class="nav-item <?php if($link == 'categories') { ?>active<?php } ?>">
                    <a href="/admin/categories/">
                        <i class="la la-sitemap"></i>
                        <p>Категории</p>
                    </a>
                </li>
				<li class="nav-item <?php if($link == 'attributes') { ?>active<?php } ?>">
                    <a href="/admin/attributes/">
                        <i class="la la-ellipsis-h"></i>
                        <p>Атрибуты</p>
                    </a>
                </li>
                <li class="nav-item <?php if($link == 'products') { ?>active<?php } ?>">
                    <a href="/admin/products/">
                        <i class="la la-list"></i>
                        <p>Товары</p>
                    </a>
                </li>
				<!--
                <li class="nav-item <?php if($link == 'attributes') { ?>active<?php } ?>">
                    <a href="/admin/attributes/">
                        <i class="la la-adn"></i>
                        <p>Product Attributes</p>
                    </a>
                </li>
                <li class="nav-item <?php if($link == 'messages') { ?>active<?php } ?>">
                    <a href="/admin/messages/">
                        <i class="la la-send-o"></i>
                        <p>Messages ---</p>
                    </a>
                </li>-->
                <!--<li class="nav-item <?php if($link == 'subscribers') { ?>active<?php } ?>">
                    <a href="/admin/subscribers/">
                        <i class="la la-user-plus"></i>
                        <p>Subscribers</p>
                    </a>
                </li>-->
                <!--<li class="nav-item <?php if($link == 'slider') { ?>active<?php } ?>">
                    <a href="/admin/slider/">
                        <i class="la la-image"></i>
                        <p>Slider ---</p>
                    </a>
                </li>-->
                <li class="nav-item dropdown <?php if($link == 'menu' || $link == 'menu_mobile') { ?>active<?php } ?>">
                    <a href="#" class="dropdown-toggle" onClick="$(this).next().toggleClass('show');">
                        <i class="la la-reorder"></i>
                        <p>Меню</p>
                    </a>
                    <ul class="dropdown-menu <?php if($link == 'menu' || $link == 'menu_mobile' || $link == 'menu_footer') { ?>show<?php } ?>" style="position: relative;border:0;box-shadow:0 0 0 !important;margin-left:40px;">
                        <li class="nav-item <?php if($link == 'menu') { ?>active<?php } ?>"><a href="/admin/menu/"><i class="la la-ellipsis-h"></i><p>Верхнее меню</p></a></li>
                        <li class="nav-item <?php if($link == 'menu_mobile') { ?>active<?php } ?>"><a href="/admin/menu_mobile/"><i class="la la-mobile"></i><p>Меню дополнительное</p></a></li>
                        <li class="nav-item <?php if($link == 'menu_footer') { ?>active<?php } ?>"><a href="/admin/menu_footer/"><i class="la la-mobile"></i><p>Нижнее меню</p></a></li>
                    </ul>
                </li>
				<!--<li class="nav-item <?php if($link == 'blog') { ?>active<?php } ?>">
                    <a href="/admin/blog/">
                        <i class="la la-file-text-o"></i>
                        <p>Блог</p>
                    </a>
                </li>-->
                <li class="nav-item <?php if($link == 'slider') { ?>active<?php } ?>">
                    <a href="/admin/slider/">
                        <i class="la la-image"></i>
                        <p>Баннеры</p>
                    </a>
                </li>
				<li class="nav-item <?php if($link == 'pages') { ?>active<?php } ?>">
                    <a href="/admin/pages/">
                        <i class="la la-database"></i>
                        <p>Текстовые страницы</p>
                    </a>
                </li>

				 <li class="nav-item dropdown <?php if($link == 'page') { ?>active<?php } ?>">
                    <a href="#" class="dropdown-toggle" onClick="$(this).next().toggleClass('show');">
                        <i class="la la-desktop"></i>
                        <p>Страницы (статический контент)</p>
                    </a>
                    <ul class="dropdown-menu <?php if($link == 'page') { ?>show<?php } ?>" style="position: relative;border:0;box-shadow:0 0 0 !important;margin-left:40px;">
                        <li class="nav-item <?php if($last_link == 'home') { ?>active<?php } ?>"><a href="/admin/page/home/"><i class="la la-desktop"></i><p>Главная</p></a></li>
                        <!--<li class="nav-item <?php if($last_link == 'blog') { ?>active<?php } ?>"><a href="/admin/page/blog/"><i class="la la-desktop"></i><p>Блог</p></a></li>-->
                    </ul>
                </li>

				<li class="nav-item <?php if($link == 'settings') { ?>active<?php } ?>">
                    <a href="/admin/settings/">
                        <i class="la la-cog"></i>
                        <p>Настройки</p>
                    </a>
                </li>

                */ ?>
                <li class="nav-item <?php if($link == 'video') { ?>active<?php } ?>">
                    <a href="/admin/video/">
                        <i class="la la-image"></i>
                        <p>Videos</p>
                    </a>
                </li>
                <?php if($data['user']->superadmin == 1) { ?>
                <li class="nav-item <?php if($link == 'users') { ?>active<?php } ?>">
                    <a href="/admin/users/">
                        <i class="la la-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <?php } ?>


            </ul>
        </div>
    </div>
