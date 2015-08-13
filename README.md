# kak-itemSelect
Yii2 Widget - with columns and a list with the ability to drag items

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