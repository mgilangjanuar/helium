<?php
use system\App; 
$this->title = 'Homepage';
$this->registerJsFile('public/assets/js/validation-form.js');
?>

<div class="jumbotron text-center">
    <h1><?= $model->datas['welcome'] ?></h1>
    <p class="lead"><?= $model->datas['description'] ?></p>
    <p>
        <a class="btn btn-default" href="http://github.com/mgilangjanuar/helium">
            <i class="fa fa-github"></i> Fork Me!
        </a>
    </p>
</div>

<div class="row">

    <div class="col-sm-4">
        <h2>Hello</h2>
        <div class="well">
        
            <legend>Example Form</legend>
            <p>
                <strong>Datas</strong><br />
                Name: <?= $model->name ?><br />
                Email: <?= $model->email ?><br />
            </p>

            <form action="" method="post" class="form-horizontal use-validation">
                <input type="hidden" name="_validation" value="<?= App::$url->urlTo(App::$params['formValidation']) ?>">
                <div class="col-sm-12">
                    <div class="form-group">
                        <input name="Example[name]" 
                               class="form-control floating-label" 
                               type="text" 
                               placeholder="Name" />
                    </div>
                    <div class="form-group">
                        <input name="Example[email]" 
                               class="form-control floating-label" 
                               type="text" 
                               placeholder="Email" />
                    </div>
                </div>
                <button class="btn btn-primary">Submit</button>
            </form>

        </div>
    </div>

    <div class="col-sm-4">
        <h2>Hello</h2>
        <p class="text-justify">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        <p>
            <a class="btn btn-default" href="#!">This is link</a>
        </p>
    </div>

    <div class="col-sm-4">
        <h2>Hello</h2>
        <p class="text-justify">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
        <p>
            <a class="btn btn-default" href="#!">This is link</a>
        </p>
    </div>

</div>