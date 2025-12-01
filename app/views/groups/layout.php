<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
</head>

<body>
    <div class="relative flex h-screen bg-white ">

        <main class="flex-1 ">
            <?php require_once $contentView; ?>
        </main>

    </div>
    <?php require_once 'app/views/components/modalInvite.php'; ?>
    <?php require_once 'app/views/components/forum/modalInviteForum.php'; ?>
    <?php require_once __DIR__ . '/../components/groups/modalCreateGroup.php'; ?>
    <?php require_once 'app/views/components/modalFinishSetup.php'; ?>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>
</body>

</html>


