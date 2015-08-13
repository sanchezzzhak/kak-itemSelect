<?php
namespace kak\widgets\itemselect;
use yii\web\AssetBundle;

class ItemSelectAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kak/itemselect/assets';

    public $js = [
        'kak.itemselect.js'
    ];

    public $css = [
        'kak.itemselect.css'
    ];

    public $depends = [
        'yii\jui\JuiAsset',
    ];
}