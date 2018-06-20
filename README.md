# kak-itemselect
Yii2 Widget - with columns and a list with the ability to drag items


property widget
-
* $items array;
* $labelFrom  string
* $labelTo string
* $itemAttributeId string
* $itemOptions array;
* $viewItem null|string|closure
* $viewParams array;
* $searchFilter bool
* $searchFilterOptions array
* $templateItem string

example usages
-
usage data model
```php
<?php 
    use kak\widgets\itemselect\ItemSelect;
    $countries = Country::find()->where(['status' => Country::STATUS_ACTIVE ])->all();
?>
...
<?=$form->field($model, 'geo_list')->widget(ItemSelect::class,[
    'itemAttribute' => 'id',
    'items' => $countries,
    'templateItem' => '<div><img src="{%=o.flag_url%}">{%=o.name%}</div>'
]);?>

```
usage data array
```php
<?=ItemSelect::widget([
    'itemAttributeId' => 'id',
    'name' => 'ItemSelect[]',  // input name
    'value' => [3,5,7],        // select items by itemAttributeId
    'items' => [
        ['id' => 1 , 'name' => 'Foo1', 'flag_url' => '' ],
        ['id' => 2 , 'name' => 'Foo2', 'flag_url' => '' ],
        ['id' => 3 , 'name' => 'Foo3', 'flag_url' => '' ],
        ['id' => 4,  'name' => 'Foo4', 'flag_url' => '' ],
        ['id' => 5 , 'name' => 'Foo5', 'flag_url' => '' ],
        ['id' => 6 , 'name' => 'Foo6', 'flag_url' => '' ],
    ],
    'templateItem' => '<div><img src="{%=o.flag_url%}">{%=o.name%}</div>'
   
]);?>
```
usage item view 
```php
<?=ItemSelect::widget([
    'itemAttribute' => 'id',
    'name' => 'ItemSelect[]',
    'value' => [3,5,7],        // select items by itemAttribute
    'items' => [
        ['id' => 1 , 'name' => 'Foo1', 'flag_url' => '' ],
        ['id' => 2 , 'name' => 'Foo2', 'flag_url' => '' ],
        ['id' => 3 , 'name' => 'Foo3', 'flag_url' => '' ],
        ['id' => 4,  'name' => 'Foo4', 'flag_url' => '' ],
        ['id' => 5 , 'name' => 'Foo5', 'flag_url' => '' ],
        ['id' => 6 , 'name' => 'Foo6', 'flag_url' => '' ],
    ],
    'viewItem' => '/controller-name/_item-select'
]);?>

```
usage item view function
```php
<?=ItemSelect::widget([
    'itemAttribute' => 'id',
    'name' => 'ItemSelect[]',
    'value' => [ 1,2,3,4,5],        // select items by itemAttribute
    'items' => [
        ['id' => 1 , 'name' => 'Foo1', 'flag_url' => '' ],
        ['id' => 2 , 'name' => 'Foo2', 'flag_url' => '' ],
        ['id' => 3 , 'name' => 'Foo3', 'flag_url' => '' ],
        ['id' => 4,  'name' => 'Foo4', 'flag_url' => '' ],
        ['id' => 5 , 'name' => 'Foo5', 'flag_url' => '' ],
        ['id' => 6 , 'name' => 'Foo6', 'flag_url' => '' ],
    ],
    'itemView' => function($item, $index, $select, $widget){
        return sprintf(
            '<div><img src="%s">%s</div>', 
            $item['flag_url'], 
            $item['name']
        );
    }
]);?>

```

usage from ActiveForm 
```php
<?php
    use app\models\Service;
    use yii\helpers\ArrayHelper;
    
    $servics = Service::find()->where(['status' => Service::STATUS_ACTIVE ])->all();
?>
...
<?=$form->field($model, 'subject_ids')->widget(ItemSelect::class, [
    'itemAttribute' => 'id',
    'items' => $servics,
    'viewItem' => function($item, $index, $select, $widget){
 
        $positionId = ArrayHelper::getValue($item, 'id', '');
        $positionValue = $select ? ArrayHelper::getValue($item, 'position', 0) : 0;
        $serviceName = ArrayHelper::getValue($item, 'name');
        
        $selectName = sprintf(
            '%s[%s]',
             basename(get_class($item)), 
             $positionId 
        );
        $selectItems = [
             '1' => 'head',
             '2' => 'center',
             '3' => 'footer',
        ];
        $selectWidget = Html::dropDownList($selectName, $positionValue, $selectItems);
        
        return sprintf(
            '<div class="row">
                <div class="col-xs-6">%s</div>
                <div class="col-xs-6">%s</div>
            </div>',
             $serviceName, 
             $selectWidget
        );
    }
]);?>

```
example template file
-

```php

```









