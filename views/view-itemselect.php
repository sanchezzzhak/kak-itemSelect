<?php
    use yii\helpers\Html;

    /** @var $this \yii\web\View */
    /** @var $context \kak\widgets\itemselect\ItemSelect */
    $context = $this->context
?>
<?=Html::beginTag('script',['id' => 'itemselect-' . $context->options['id'], 'type' => 'text/x-tmpl']); ?>
    <?=$context->templateItem?>
<?=Html::endTag('script'); ?>

<?=Html::beginTag('div',$context->options);?>
    <div class="col-xs-12 col-md-5">
        <?=(empty($context->labelFrom)) ? '': Html::label($context->labelFrom)?>
        <?php if($context->searchFilter):?>
            <div class="itemselect-input-search" data-search=".itemselect-list-from"><?=Html::input('text','','',$context->searchFilterOptions)?></div>
        <?php endif;?>
        <div class="itemselect-list-from">
            <?=$context->renderList($context::DIRECTION_FROM) ?>
        </div>
    </div>
    <div class="col-xs-12 col-md-2 text-center">
        <div class="form-group">
            <button type="button" class="btn btn-default btnTo">
                <i class="hidden-xs hidden-sm glyphicon glyphicon-chevron-left"></i>
                <i class="visible-xs visible-sm glyphicon glyphicon-chevron-up"></i>
            </button>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default btnFrom">
                <i class="hidden-xs hidden-sm glyphicon glyphicon-chevron-right"></i>
                <i class="visible-xs visible-sm glyphicon glyphicon-chevron-down"></i>
            </button>
        </div>
    </div>
    <div class="col-xs-12 col-md-5">
        <?=(empty($context->labelTo)) ? '': Html::label($context->labelTo)?>
        <?php if($context->searchFilter):?>
            <div class="itemselect-input-search" data-search=".itemselect-list-to"><?=Html::input('text','','',$context->searchFilterOptions)?></div>
        <?php endif;?>
        <div class="itemselect-list-to">
            <?=$context->renderList($context::DIRECTION_TO) ?>
        </div>
    </div>

<?=Html::endTag('div')?>