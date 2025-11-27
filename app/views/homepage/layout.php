<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex">

    <?php require_once 'app/views/components/sidebars.php'; ?>

    <div class="w-full flex flex-1 justify-center overflow-hidden">
        <?php require_once $contentViewPost; ?>

        <aside class="hidden lg:block w-[360px] p-4 flex-shrink-0 bg-white border-l border-gray-200">
            <?php require_once 'app/views/components/postingan/forYouPage.php' ?>
        </aside>
    </div>
    <?php require_once 'app/views/components/modalInvite.php'; ?>
    <?php require_once 'app/views/components/Forum/modalInviteForum.php'; ?>
    <?php require_once 'app/views/components/modalFinishSetup.php'; ?>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>
</body>

</html>