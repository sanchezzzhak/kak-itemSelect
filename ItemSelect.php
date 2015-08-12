<?php
namespace kak\widgets\itemselect;

class ItemSelect extends \yii\widgets\InputWidget
{
    public $items = [];

    public $labelFrom;
    public $labelTo;

    public function run()
    {
        $this->options['id'] = $this->getId();

        $this->registerAssets();

        $this->getView()->registerJs("jQuery('#{$this->options['id']}').kakItemSelect()");


        return $this->render('view-itemselect');
    }

    /*
    * Registers Assets
    */
    public function registerAssets()
    {
        TmplAsset::register($this->getView());
        ItemSelectAsset::register($this->getView());
    }
}