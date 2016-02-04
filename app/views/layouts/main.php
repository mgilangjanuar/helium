<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?= $this->title ?> | Helium</title>
    <?php foreach ($this->assets->getCss() as $css): ?>
        <?= $css ?>
    <?php endforeach ?>
</head>
<body>

    <div class="container">
        <?php require $__render__ ?>
    </div>

    <footer class="panel">
        <div class="container-fluid">
            <p class="pull-right">
                <strong>Helium</strong> &copy; <?= date('Y') ?>
            </p>
        </div>
    </footer>
    
    <?php foreach ($this->assets->getJs() as $js): ?>
        <?= $js ?>
    <?php endforeach ?>
</body>
</html>