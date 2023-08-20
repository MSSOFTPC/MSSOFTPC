<?php function head($title=null){ 

    if(!isset($title)){
        $title =  'IQ Advanced CMS';
    }
    
    ?>
    
<!DOCTYPE html>
<html lang="en">

<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Primary Meta Tags -->
<title><?php echo  $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="title" content="<?php echo  $title; ?>">
<meta name="author" content="MSSOFTPC.COM">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="iqcms.mssoftpc.com">
<meta property="og:title" content="<?php echo  $title; ?>">
<meta property="og:image" content="<?php echo IQ_data('logo');?>">

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo IQ_data('logo'); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo IQ_data('logo'); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo IQ_data('logo'); ?>">
<link rel="manifest" href="<?php echo IQ_data('logo'); ?>">
<link rel="mask-icon" href="<?php echo IQ_data('logo'); ?>" color="#ffffff">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">

<!-- Sweet Alert -->
<link type="text/css" href="<?php echo adminassets('sweetalert2/dist/sweetalert2.min.css', 'vendor'); ?>" rel="stylesheet">

<!-- Top Admin Bar -->
<link type="text/css" href="<?php echo adminassets('top_admin_bar.css', 'css'); ?>" rel="stylesheet">

<!-- Notyf -->
<link type="text/css" href="<?php echo adminassets('notyf/notyf.min.css', 'vendor'); ?>" rel="stylesheet">

<!-- Volt CSS -->
<link type="text/css" href="<?php echo adminassets('volt.css', 'css'); ?>" rel="stylesheet">
<link type="text/css" href="<?php echo adminassets('new.css', 'css'); ?>" rel="stylesheet">

<!-- widget -->
<link type="text/css" href="<?php echo adminassets('widget.css', 'css'); ?>" rel="stylesheet">

<!-- icons -->
<!-- https://remixicon.com/-->
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.0.0/fonts/remixicon.css" rel="stylesheet">

<script src="<?php echo adminassets('sweetalert2/dist/sweetalert2.all.min.js','vendor') ?>"></script>
<script src="<?php echo adminassets('notyf/notyf.min.js','vendor') ?>"></script>

<!-- icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

<script src="https://cdn.tiny.cloud/1/vooc73d8w6m5om1vauilk8mns64fxecwqbgvxy1ef73a6gdp/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<!-- new links -->
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<?php add_action('IQ_admin_head'); ?>
</head>

<body>
<?php } ?>