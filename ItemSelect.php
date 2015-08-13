<?php
namespace kak\widgets\itemselect;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ItemSelect extends \yii\widgets\InputWidget
{
    const DIRECTION_FROM = 0;
    const DIRECTION_TO = 1;

    public $items = [];

    public $labelFrom;
    public $labelTo;

    public $itemAttributeId = 'id';


    /** @var string template item */
    public $templateItem = '<div>{%=o.name%}</div>';

    public function init()
    {
        parent::init();

        Html::addCssClass($this->options,'row');

        $this->options['id'] =  $this->getId();
        $this->options['data-inputname'] =  $this->getHiddenInputName();
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->options['id'] = $this->getId();
        $this->registerAssets();
        $this->getView()->registerJs("jQuery('#{$this->options['id']}').kakItemSelect()");

        return $this->render('view-itemselect');
    }

    /**
     * @param $item
     * @return mixed
     */
    public function renderItem($item)
    {
        $clip = $this->templateItem;
        $clip = preg_replace_callback('#\{\%\=o\.([a-z0-9\.\_]+)%}#ism',function($matches) use($item){
            $name = trim($matches[1]);
            $value = ArrayHelper::getValue($item,$name);
            return $value;
        },$clip);
        return preg_replace('#\{\%\=o(.*)\%\}#ism','',$clip);
    }

    public function getHiddenInputName()
    {
        return $this->hasModel() ? (
            $this->model->formName() . '['.$this->attribute.'][]'
        ): $this->name;
    }

    /**
     * @param int $direction DIRECTION_FROM|DIRECTION_TO
     * @return string
     */
    public function renderList($direction = 0){

        $name = $this->getHiddenInputName();

        $values = $this->hasModel() ? (array)$this->model->{$this->attribute} : (array)$this->value;
        $html = '';
        foreach ($this->items as $item) {
            $value = ArrayHelper::getValue($item,$this->itemAttributeId);
            if($direction  == self::DIRECTION_FROM && in_array($value,$values)) {
                 continue;
            }
            if($direction == self::DIRECTION_TO && !in_array($value,$values) ) {
                continue;
            }

            $input = ($direction) ? Html::hiddenInput($name, $value) : '';
            $html.= Html::tag('div', $input . $this->renderItem($item), [
                'class'   => 'itemselect-item',
                'data-id' =>  $value ]
            );
        }
        return $html;
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