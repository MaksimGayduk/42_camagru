<?php
    /** @var \app\base\View $this */
    /** @var array $renderUnit - files to render */
    /** @var bool $useComponents */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/solid.css" integrity="sha384-+0VIRx+yz1WBcCTXBkVQYIBVNEFH1eP6Zknm16roZCyeNg2maWEpk/l/KsyFKs7G" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/fontawesome.css" integrity="sha384-jLuaxTTBR42U2qJ/pm4JRouHkEDHkVqH0T1nyQXn1mZ7Snycpf6Rl25VBNthU4z0" crossorigin="anonymous">

    <script src="/template/template.js"></script>
    <title>Camagru</title>

    <link rel="stylesheet" href="/template/template.css">


    <?php if ($renderUnit['style']): ?>
        <link rel="stylesheet" href="<?= $renderUnit['style'] ?>">
    <?php endif ?>

</head>
<body>

<?php
    if ($useComponents) {
        include(ROOT . '/template/header/header.php');
    }

    include($renderUnit['markUp']);
?>
    <?php if ($renderUnit['script']): ?>
        <script src="<?= $renderUnit['script'] ?>"></script>
    <?php endif ?>

    <?php foreach($this->jsFiles as $jsFile): ?>
        <script src="<?= $jsFile ?>"></script>
    <?php endforeach ?>

    <?php foreach($this->jsScripts as $jsScript): ?>
        <script><?= $jsScript ?></script>
    <?php endforeach ?>

<?php
    if ($useComponents) {
        include(ROOT . '/template/footer/footer.php');
    }
?>

<?php foreach($this->cssFiles as $cssFile): ?>
    <link rel="stylesheet" href="<?= $cssFile ?>">
<?php endforeach ?>
</body>
</html>
