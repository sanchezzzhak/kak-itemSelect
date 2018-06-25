<?php

namespace kak\widgets\itemselect;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\web\JsExpression;

class ItemSelect extends \yii\widgets\InputWidget
{
    const JS_KEY = 'kak/itemselect/';

    const SELECT_FROM = 0;
    const SELECT_TO = 1;

    /**
     * event js result(elm, direction 0|1)
     */
    const EVENT_SELECT_ITEM = 'itemselect:select';
    /**
     * event js result(elm, direction 0|1)
     */
    const EVENT_UNSELECT_ITEM = 'itemselect:unselect';
    /**
     * event js result(elm, direction 0|1)
     */
    const EVENT_MOVE_ITEM = 'itemselect:moveitem';
    /**
     * @var array items list select
     */
    public $items = [];
    /**
     * @var string
     */
    public $labelFrom;
    /**
     * @var string
     */
    public $labelTo;
    /**
     * @var string
     */
    public $labelUnselectAll = 'Unselect all';
    /**
     * @var string
     */
    public $labelSelectAll = 'Select all';
    /**
     * @var array events list
     */
    public $events = [];
    /**
     * @var string
     */
    public $itemAttributeId = 'id';
    /**
     * @var array the HTML attributes for the container of the rendering result of each data item.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     */
    public $itemOptions = [];

    /**
     * @var string|callable the name of the view for rendering each data item, or a callback (e.g. an anonymous function)
     * for rendering each data item. If it specifies a view name, the following variables will
     * be available in the view:
     *
     * - `$model`: mixed, the data model
     * - `$index`: integer, the zero-based index of the data item
     * - `$direction`: integer, SELECT_FROM|SELECT_TO
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
    public $viewItem = null;

    /**
     * @var array additional parameters to be passed to [[viewItem]] when it is being rendered.
     * This property is used only when [[viewItem]] is a string representing a view name.
     */
    public $viewParams   = [];

    /**
     * @var bool
     * show/hidden search filter
     */
    public $searchFilter = true;
    /**
     * @var bool
     * show/hidden hint select items
     */
    public $selectHint = false;

    /**
     * @var bool move item by click
     */
    public $moveItemClick  = false;


    public $searchFilterOptions = [
        'class' => 'form-control',
        'placeholder' => 'search item',
    ];

    /** @var string template item */
    public $templateItem = '<div>{%=o.name%}</div>';
    public $templateForm = '@vendor/kak/itemselect/src/views/main-tempate';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        Html::addCssClass($this->itemOptions, 'itemselect-item');
        $this->options['data-inputname'] = $this->getHiddenInputName();
        $this->options['data-search-filter'] = $this->searchFilter ? 1 : 0;
        $this->options['data-select-hint'] = $this->selectHint ? 1 : 0;
        $this->options['data-move-click'] = $this->moveItemClick ? 1 : 0;
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->registerAssets();
        $this->registerEvents();
        return $this->render($this->templateForm);
    }

    /**
     * @param $item
     * @return mixed
     */
    public function renderTemplateItem($item)
    {
        $clip = $this->templateItem;
        $clip = preg_replace_callback('#\{\%\=o\.([a-z0-9\.\_]+)%}#ism', function ($matches) use ($item) {
            $name = trim($matches[1]);
            $value = ArrayHelper::getValue($item, $name, '');
            return $value;
        }, $clip);
        return preg_replace('#\{\%\=o(.*)\%\}#ism', '', $clip);
    }
    public function getHiddenInputName()
    {
        return $this->hasModel()
            ? sprintf('%s[%s][]', StringHelper::basename(get_class($this->model)), $this->attribute)
            : $this->name;
    }

    /**
     * Render items
     * @param int $select
     * @return string
     */
    public function renderList($select = 0)
    {
        $name = $this->getHiddenInputName();
        $values = $this->hasModel() ? (array)$this->model->{$this->attribute} : (array)$this->value;

        $result = [];
        foreach ($this->items as $index => $item) {

            $value = ArrayHelper::getValue($item, $this->itemAttributeId, []);
            if ($select == static::SELECT_FROM && in_array($value, $values)) {
                continue;
            }
            if ($select == static::SELECT_TO && !in_array($value, $values)) {
                continue;
            }

            $input = $select ? Html::hiddenInput($name, $value) : '';
            if ($this->viewItem === null) {
                $content = $this->renderTemplateItem($item);
            } elseif (is_string($this->viewItem)) {
                $content = $this->getView()->render($this->viewItem, array_merge([
                    'item' => $item,
                    'index' => $index,
                    'direction' => $select,
                    'widget' => $this,
                ], $this->viewParams));

            } else {
                $content = call_user_func($this->viewItem, $item, $index, $select, $this);
            }
            $result[] = Html::tag('div', $input . $content, array_merge(['data-id' => $value], $this->itemOptions));
        }
        return implode("\n", $result);
    }

    /**
     * Registers Assets
     */
    protected function registerAssets()
    {
        $views = $this->getView();

        budles\TmplAsset::register($views);
        budles\ItemSelectAsset::register($views);

        $views->registerJs(sprintf("jQuery('#%s').kakItemSelect()", $this->options['id']));
    }

    /**
     * Register events.
     */
    protected function registerEvents()
    {
        $view = $this->getView();
        $selector = '#' . $this->options['id'];
        if (count($this->events) > 0) {
            $js = [];
            foreach ($this->events as $event => $callback) {
                if (is_array($callback)) {
                    foreach ($callback as $function) {
                        if (!$function instanceof JsExpression) {
                            $function = new JsExpression($function);
                        }
                        $js[] = sprintf("jQuery('%s').on('%s', %s);", $selector, $event, $function);
                    }
                } else {
                    if (!$callback instanceof JsExpression) {
                        $callback = new JsExpression($callback);
                    }
                    $js[] = sprintf("jQuery('%s').on('%s', %s);", $selector, $event, $callback);
                }
            }
            if (count($js) > 0) {
                $js = implode("\n", $js);
                $view->registerJs($js, $view::POS_READY, self::JS_KEY .'events/'. $this->options['id']);
            }
        }
    }



}