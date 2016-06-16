# kak-itemSelect
Yii2 Widget - with columns and a list with the ability to drag items


property widget
=======
* $items array;
* $labelFrom  string
* $labelTo string
* $itemAttributeId string
* $itemOptions array;
* $itemView null|string|closure
* $viewParams array;
* $searchFilter bool
* $searchFilterOptions array
* $templateItem string

example
=======
usage model
```php
<?=$form->field($postModel,'geo_list_id')->widget('kak\widgets\itemselect\ItemSelect',[
    'itemAttributeId' => 'id',
    'items' => Country::find()->where(['status' => 1 ])->all(),
    'templateItem' => '<div><img src="{%=o.flag_url%}" style="width:50px; margin-right:5px">{%=o.name%}</div>'
]);?>

```
usage hardcore ((:
```php
<?=kak\widgets\itemselect\ItemSelect::widget([
    'itemAttributeId' => 'id',
    'name' => 'ItemSelect[]',
    'value' => [3,5,7],        // select items by itemAttributeId
    'items' => [
        ['id' => 1 , 'name' => 'Foo1', 'flag_url' => '' ],
        ['id' => 2 , 'name' => 'Foo2', 'flag_url' => '' ],
        ['id' => 3 , 'name' => 'Foo3', 'flag_url' => '' ],
        ['id' => 4,  'name' => 'Foo4', 'flag_url' => '' ],
        ['id' => 5 , 'name' => 'Foo5', 'flag_url' => '' ],
        ['id' => 6 , 'name' => 'Foo5', 'flag_url' => '' ],
    ],
    'templateItem' => '<div><img src="{%=o.flag_url%}" style="width:50px; margin-right:5px">{%=o.name%}</div>'
   
]);?>
```
usage item view 
```php
<?=kak\widgets\itemselect\ItemSelect::widget([
    'itemAttributeId' => 'id',
    'name' => 'ItemSelect[]',
    'value' => [3,5,7],        // select items by itemAttributeId
    'items' => [
        ['id' => 1 , 'name' => 'Foo1', 'flag_url' => '' ],
        ['id' => 2 , 'name' => 'Foo2', 'flag_url' => '' ],
        ['id' => 3 , 'name' => 'Foo3', 'flag_url' => '' ],
        ['id' => 4,  'name' => 'Foo4', 'flag_url' => '' ],
        ['id' => 5 , 'name' => 'Foo5', 'flag_url' => '' ],
        ['id' => 6 , 'name' => 'Foo5', 'flag_url' => '' ],
    ],
    'itemView' => '_item-select'
]);?>

```

usage item view function
```php
<?=kak\widgets\itemselect\ItemSelect::widget([
    'itemAttributeId' => 'id',
    'name' => 'ItemSelect[]',
    'value' => [ 1,2,3,4,5],        // select items by itemAttributeId
    'items' => [
        ['id' => 1 , 'name' => 'Foo1', 'flag_url' => '' ],
        ['id' => 2 , 'name' => 'Foo2', 'flag_url' => '' ],
        ['id' => 3 , 'name' => 'Foo3', 'flag_url' => '' ],
        ['id' => 4,  'name' => 'Foo4', 'flag_url' => '' ],
        ['id' => 5 , 'name' => 'Foo5', 'flag_url' => '' ],
        ['id' => 6 , 'name' => 'Foo5', 'flag_url' => '' ],
    ],
    'itemView' => function($item,$index,$direction,$widget){
        return '<div><img src="'.$item['flag_url'].'" style="width:50px; margin-right:5px">'.$item['name'].'</div>'
    }
]);?>

```

ActiveForm 
```php
<?=$form->field($model,'subject_ids')->widget(kak\widgets\itemselect\ItemSelect::className(),[
    'itemAttributeId' => 'id',
    'items' => \app\models\Service::find()->all(),
    'itemView' => function($item,$index, $direction, $widget){
        $positionName   = \yii\helpers\ArrayHelper::getValue($item,'id','');
        $positionValue  =  $direction ? \yii\helpers\ArrayHelper::getValue($item,'position',0) : 0;
        $selectDropDown = Html::dropDownList('position['.$positionName.']',$positionValue,[
            '1' => 'head',
            '2' => 'center',
            '3' => 'footer',
        ]);
        return '<div class="row">
            <div class="col-xs-6">'.\yii\helpers\ArrayHelper::getValue($item,'name').'</div>
            <div class="col-xs-6">
            '.$selectDropDown.'</div>
        </div>';
    }
]);?>

```







