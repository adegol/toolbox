<?php require '../../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Toolbox :: Pastebin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../../assets/css/bootstrap-responsive.css" rel="stylesheet">
    <style>
        body {margin-top:50px;}
        textarea[name="code"] {height:550px;}
    </style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="../../assets/js/html5shiv.js"></script>
    <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar navbar-inner">
            <a href="index.php" class="brand">Pastebin</a>
            <ul class="nav">
                <li class="divider-vertical"></li>
                <li><a href="index.php">Create new</a></li>
                <li class="divider-vertical"></li>
                <li>
                    <form method="get" action="" class="navbar-search form-inline">
                        <input type="text" class="search-query" name="query" placeholder="Find paste...">
                    </form>
                </li>
            </ul>
        </div>
    </div>