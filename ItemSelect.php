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

    /**
     * @var array the HTML attributes for the container of the rendering result of each data item.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     */
    public $itemOptions  = [];

    /**
     * @var string|callable the name of the view for rendering each data item, or a callback (e.g. an anonymous function)
     * for rendering each data item. If it specifies a view name, the following variables will
     * be available in the view:
     *
     * - `$model`: mixed, the data model
     * - `$index`: integer, the zero-based index of the data item
     * - `$direction`: integer, DIRECTION_FROM|DIRECTION_TO
     * - `$widget`: this widget instance
     *
     * Note that the view name is resolved into the view file by the current context of the [[view]] object.
     *
     * If this property is specified as a callback, it should have the following signature:
     *
     * ```php
     * function ($item, $index, $direction, $widget)
     * ```
     */
    public $itemView     = null;

    /**
     * @var array additional parameters to be passed to [[itemView]] when it is being rendered.
     * This property is used only when [[itemView]] is a string representing a view name.
     */
    public $viewParams   = [];

    /**
     * @var bool
     * show/hidden search filter
     */
    public $searchFilter = true;
    /**
     * @var array the HTML attributes for the container of the rendering search filter
     * The "tag" element specifies the tag name of the container element and defaults to "input".
     */
    public $searchFilterOptions = [
        'placeholder' => 'search item',
    ];

    /** @var string template item */
    public $templateItem = '<div>{%=o.name%}</div>';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options,'row');
        Html::addCssClass($this->searchFilterOptions,'form-control');
        Html::addCssClass($this->itemOptions,'itemselect-item');

        $this->options['id'] =  $this->getId();
        $this->options['data-inputname'] =  $this->getHiddenInputName();
        $this->options['data-search-filter'] =  $this->searchFilter;
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
    public function renderTemplateItem($item)
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
        foreach ($this->items as $index => $item) {

            $value = ArrayHelper::getValue($item,$this->itemAttributeId);
            if($direction  == self::DIRECTION_FROM && in_array($value,$values)) {
                 continue;
            }
            if($direction == self::DIRECTION_TO && !in_array($value,$values) ) {
                 continue;
            }

            $input = ($direction) ? Html::hiddenInput($name, $value) : '';
            if($this->itemView === null ){
                $content = $this->renderTemplateItem($item);
            }
            elseif(is_string($this->itemView)){
                $content = $this->getView()->render($this->itemView, array_merge([
                    'item' => $item,
                    'index' => $index,
                    'direction' => $direction,
                    'widget' => $this,
                ], $this->viewParams));

            }else{
                $content = call_user_func($this->itemView, $item, $index, $direction, $this);
            }


            $html.= Html::tag('div', $input.$content, array_merge([
                'data-id' =>  $value ],$this->itemOptions)
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