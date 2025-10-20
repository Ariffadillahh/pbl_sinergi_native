<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex">

    <?php require_once 'app/views/components/sidebars.php'; ?>


    <div class="relative flex h-screen bg-white ">

        <main class="flex-1 overflow-y-auto p-3 md:p-5">
            <?php require_once $contentViewSettings; ?>
        </main>

    </div>

    <?php require_once __DIR__ . '/../components/forums/modalCreateForum.php'; ?>
    <?php require_once 'app/views/components/modalFinishSetup.php'; ?>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>
</body>

</html>