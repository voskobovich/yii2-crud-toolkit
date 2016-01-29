<?php

namespace voskobovich\crud\forms;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;


/**
 * Class FindableFormAbstract
 * @package voskobovich\crud\forms
 */
abstract class FindableFormAbstract extends Model
{
    /**
     * Editable model class name
     * @var string
     */
    public $modelClass;

    /**
     * Default attribute for print error
     * @var string
     */
    public $modelDefaultAttribute;

    /**
     * Default scenario for editable model
     * @var string
     */
    public $modelScenario = ActiveRecord::SCENARIO_DEFAULT;

    /**
     * @var ActiveRecord
     */
    protected $_model;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->modelClass) {
            throw new InvalidConfigException('Property "modelClass" can not be empty.');
        }

        if (!is_subclass_of($this->modelClass, ActiveRecord::className())) {
            throw new InvalidConfigException('Property "modelClass" must be implemented ' . ActiveRecord::className());
        }

        if (!$this->modelDefaultAttribute) {
            throw new InvalidConfigException('Property "modelDefaultAttribute" can not be empty.');
        }

        parent::init();
    }

    /**
     * @param $id
     * @return null|ActiveRecord
     */
    public function findOne($id)
    {
        /** @var ActiveRecord $model */
        $model = $this->modelClass;
        $this->_model = $model::findOne($id);

        return $this;
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($runValidation && !$this->validate($attributeNames)) {
            Yii::info('Model not saved due to validation error.', __METHOD__);
            return false;
        }

        $this->_model->setAttributes($this->getAttributes());

        if (!$this->_model->save()) {
            $this->populateErrors($this->_model, $this->modelDefaultAttribute);
            return false;
        }

        return true;
    }

    /**
     * @param ActiveRecord $model
     * @param string $defaultAttribute
     * @param array $attributesMap
     */
    public function populateErrors(ActiveRecord $model, $defaultAttribute, $attributesMap = [])
    {
        /** @var Model $this */
        $errors = $model->getErrors();

        foreach ($errors as $attribute => $messages) {
            $attribute = isset($attributesMap[$attribute])
                ? $attributesMap[$attribute]
                : $attribute;
            if (false === $this->hasProperty($attribute)) {
                if (!method_exists($this, 'hasAttribute')) {
                    $attribute = $defaultAttribute;
                } elseif (false === $this->hasAttribute($attribute)) {
                    $attribute = $defaultAttribute;
                }
            }
            foreach ($messages as $mes) {
                $this->addError($attribute, $mes);
            }
        }
    }
}