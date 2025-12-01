<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex">

    <?php require_once 'app/views/components/sidebars.php'; ?>
    <?php require_once 'app/views/components/modalInvite.php'; ?>
    <?php require_once 'app/views/components/forum/modalInviteForum.php'; ?>  

    <div class="w-full flex flex-1 justify-center ">
        <?php require_once $contentViewForum; ?>
    </div>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>
</body>

</html>