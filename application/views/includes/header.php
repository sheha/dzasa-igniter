<!doctype html>
<!--[if lt IE 7]>
<html ng-app="epam" class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""/> <![endif]-->
<!--[if IE 7]>
<html ng-app="epam" class="no-js lt-ie9 lt-ie8" lang=""/> <![endif]-->
<!--[if IE 8]>
<html ng-app="epam" class="no-js lt-ie9" lang=""/> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""/> <!--<![endif]-->
<head>
    <title><?php echo isset( $title ) ? $title : 'Phone Book Login'; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Phone Book Detailed View</title>
    <meta name="description"
          content="Phonebook type app with basic CRUD activities, running on CodeIgniter3, jQuery,
            AJAX and Bootstrap.Ismar Sehic" >
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
</head>

<!-- STYLES -->
<!-- local assets -->
    <link rel="stylesheet" type="text/css"
          href="<?php echo site_url( 'assets/local-assets/css/style.css' ); ?>">

<!-- CDN -->
<!-- BOOTSTRAP 3.3.7-->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous"><!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
          crossorigin="anonymous"><!-- Optional theme -->

<!-- datatables-->
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/dt-1.10.15/datatables.min.css"/>
    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">

</head>
<body>
