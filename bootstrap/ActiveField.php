<?php

namespace voskobovich\admin\bootstrap;

use voskobovich\base\helpers\Html;


/**
 * Class ActiveField
 * @package app\widgets
 */
class ActiveField extends \yii\bootstrap\ActiveField
{
    /**
     * @param array $options
     * @param bool $enclosedByLabel
     * @return static
     */
    public function checkbox($options = [], $enclosedByLabel = false)
    {
        $this->options = [];

        if (!empty($options['parentOptions'])) {
            $this->options = $options['parentOptions'];
            unset($options['parentOptions']);
        }

        if (!empty($options['template'])) {
            $this->template = $options['template'];
            unset($options['template']);
        } else {
            $this->template = "{input}\n{label}\n{hint}\n{error}";
        }

        return parent::checkbox($options, $enclosedByLabel);
    }

    /**
     * @param array $options
     * @param bool $enclosedByLabel
     * @return static
     */
    public function radio($options = [], $enclosedByLabel = false)
    {
        $this->options = [];

        if (!empty($options['parentOptions'])) {
            $this->options = $options['parentOptions'];
            unset($options['parentOptions']);
        }

        if (!empty($options['template'])) {
            $this->template = $options['template'];
            unset($options['template']);
        } else {
            $this->template = "{input}\n{label}\n{hint}\n{error}";
        }

        return parent::radio($options, $enclosedByLabel);
    }

    /**
     * Переопределен ради класса HTML
     * @param array $items
     * @param array $options
     * @return $this
     */
    public function radioList($items, $options = [])
    {
        if (!empty($options['template'])) {
            $this->template = $options['template'];
            unset($options['template']);
        } else {
            $this->template = "{label}\n{input}\n{hint}\n{error}";
        }
        $this->options = ['class' => 'radioList-field'];
        if (!empty($options['parentOptions'])) {
            $this->options = $options['parentOptions'];
            unset($options['parentOptions']);
        }
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeRadioList($this->model, $this->attribute, $items, $options);
        return $this;
    }

    /**
     * @param array $items
     * @param array $options
     * @return $this
     */
    public function checkboxList($items, $options = [])
    {
        if (!empty($options['template'])) {
            $this->template = $options['template'];
            unset($options['template']);
        } else {
            $this->template = "{label}\n{input}\n{hint}\n{error}";
        }
        $this->options = ['class' => 'checkboxList-field'];
        if (!empty($options['parentOptions'])) {
            $this->options = $options['parentOptions'];
            unset($options['parentOptions']);
        }
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeCheckboxList($this->model, $this->attribute, $items, $options);
        return $this;
    }
} 