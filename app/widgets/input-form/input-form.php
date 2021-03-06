<?php
/** @var \app\widgets\inputForm\InputForm $this */
/** @var \app\base\View $this->view  */

use app\components\CaseTranslator;

$widgetName = CaseTranslator::toKebab($this->widgetName);
$this->view->registerCssFile('/widgets/input-form/input-form.css', true);
?>

<div class="<?= $this->name ?>">
    <form class="<?= $widgetName ?>" action="<?= $this->action ?>" method="<?= $this->method ?>">
        <input type="hidden" name="formName" value="<?= $this->name ?>"/>
        <?php if ($this->header): ?>
            <h2 class="<?= $widgetName . '__header' ?>"><?= $this->tittle ?></h2>
            <hr class="<?= $widgetName . '__line' ?>">
        <?php endif; ?>

        <?php if ($this->description): ?>
            <div class="alert alert-primary description" role="alert">
                <?= $this->description ?>
            </div>
        <?php endif; ?>

        <div class="<?= $widgetName . '__container' ?>">
            <?php $i = 1 ?>
            <?php foreach($this->inputs as $field): ?>
                <div class="<?=  $widgetName . '__unit-' . $i++ ?>">
                    <?= $field->render([
                        'formWidgetName'    => $widgetName,
                        'formSubmitted'     => $this->submitted
                    ]) ?>
                </div>
            <?php endforeach ?>

            <?php if ($this->result): ?>
                <div class="alert result <?= 'alert-' . $this->resultStyle ?>" role="alert">
                    <?= $this->result ?>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center col-12">
                <input class="<?= $widgetName . '__submit' ?> btn btn-primary col-sm-4"
                       type="submit" value="Submit">
            </div>
        </div>



        <div class="<?= $widgetName . '__aggregate' ?>">&nbsp;</div>
    </form>
</div>

<?php

$inputs = [];
foreach ($this->inputs as $name => $field) {
    if (!$field->getChecks()) {
        continue;
    }
    $inputs[$name] = [
        'id'     => $field->getId(),
        'unique' => $field->isUnique(),
    ];
}
$inputs = json_encode($inputs);

    $this->view->registerJsScript(<<<JS

if (window.inputFields === undefined) {
    var inputFields = {};
};

inputFields = Object.assign(inputFields, $inputs);
JS
);
