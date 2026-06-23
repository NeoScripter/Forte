<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?= slot('test/test', ['class' => 'mt-4']) ?>
        <p>This goes into the default slot</p>
    <?= end_slot() ?>
</body>

</html>
