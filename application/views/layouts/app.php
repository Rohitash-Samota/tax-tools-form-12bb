<!doctype html>
<html lang="en-IN">

<head>
    <meta charset="utf-8">
    <title><?= html_escape($page_title ?? '') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="copyright" content="Copyright (c) 2016 Tax2Win" />
    <meta name="doc-type" content="Public" />
    <meta name="robots" content="index, follow, all" />
    <meta name="language" content="EN-US" />
    <meta name="description" content="Generate Form 12BB online for FY 2019-20. This form helps you in claiming maximum tax benefits using HRA, LTA, Tax Saving Deductions, Investments etc.">
    <meta name="distribution" content="Global" />
    <meta name="last-modified" content="Sunday, 13 September 2016 10:33:43 GMT" />
    <meta name="classification" content="Tax Filing" />
    <link rel="stylesheet" href="https://tax2win.in/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://tax2win.in/assets-new/css/style-new.css">
    <link rel="stylesheet" href="https://tax2win.in/assets-new/css/font-awesome/font-awesome.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body class="<?= isset($body_class) ? $body_class : '' ?>">

    <?php $this->load->view('layouts/header'); ?>

    <?= isset($content) ? $content : '' ?>

    <?php $this->load->view('layouts/footer'); ?>
    <script src="https://tax2win.in/assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="https://tax2win.in/assets/js/tax_tools.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://tax2win.in/assets-new/js/jquery-1.12.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <?php if (!empty($scripts) && is_array($scripts)): ?>
        <?php foreach ($scripts as $src): ?>
            <script src="<?= $src ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>