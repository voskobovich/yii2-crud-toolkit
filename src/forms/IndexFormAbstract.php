<?php

namespace voskobovich\crud\forms;

use voskobovich\base\traits\ModelTrait;
use yii\base\Model;


/**
 * Class IndexFormAbstract
 * @package voskobovich\crud\forms
 */
abstract class IndexFormAbstract extends Model
{
    use ModelTrait;

    /**
     * Query building
     * @param $params
     * @return \yii\data\ActiveDataProvider
     */
    abstract public function search($params);
}