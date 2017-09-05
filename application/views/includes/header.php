<!DOCTYPE html>
<html>
<head>
    <title><?php echo isset($title) ? $title : 'Phone Book Login'; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <!-- CDN -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jqc-1.12.4/dt-1.10.15/datatables.min.css"/>
    <!-- local -->
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/local-assets/css/style.css');?>">
    <link href="--><?php echo site_url( 'assets/bootstrap-datepicker-1.6.4-dist/css/bootstrap-datepicker.css' );?>">

    <script
            src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
</head>
<body>
