Yii2 CRUD Toolkit
===

It is a framework to quickly build controllers for your Yii2 application.

Example
---

Your controller can look like this

```php
<?php

namespace app\controllers;

use voskobovich\alert\helpers\AlertHelper;
use voskobovich\crud\actions\UpdateAction;
use voskobovich\crud\actions\ViewAction;
use Yii;
// and more namespases ...

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        $successCallback = function () {
            Yii::$app->session->setFlash('success', 'Saved successfully!');
        };

        $errorCallback = function () {
            Yii::$app->session->setFlash('error', 'Error saving!');
        };

        $webUser = Yii::$app->user;

        return [
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => ProfileUpdateForm::className(),
                'primaryKey' => $webUser->id,
                'redirectUrl' => false,
                'successCallback' => $successCallback,
                'errorCallback' => $errorCallback,
            ],
            'password' => [
                'class' => UpdateAction::className(),
                'modelClass' => ProfilePasswordForm::className(),
                'primaryKey' => $webUser->id,
                'redirectUrl' => ['password'],
                'viewFile' => 'password',
                'successCallback' => function () {
                    Yii::$app->session->setFlash('success', 'Password changed');
                },
                'errorCallback' => $errorCallback,
            ],
            'photo' => [
                'class' => UploadAction::className(),
                'modelClass' => ProfilePhotoForm::className(),
                'primaryKey' => $webUser->id,
                'viewFile' => 'photo',
                'redirectUrl' => false,
                'successCallback' => false,
                'errorCallback' => false,
            ],
            'photo-delete' => [
                'class' => UpdateAction::className(),
                'modelClass' => ProfilePhotoDeleteForm::className(),
                'primaryKey' => $webUser->id,
                'viewFile' => false,
                'redirectUrl' => ['update'],
                'successCallback' => false,
                'errorCallback' => false,
            ],
            'contacts' => [
                'class' => ViewAction::className(),
                'modelClass' => User::className(),
                'loadedModel' => $webUser->identity,
                'viewFile' => 'contacts',
            ],
        ];
    }
}
```

Action Params
---

| Param name      | Description                                                                                                                                                                                                     |
| --------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| modelClass      | Class name of your ActiveRecord model or form which extend `voskobovich\base\forms\FindableFormAbstract` from [yii2-base-toolkit](https://github.com/voskobovich/yii2-base-toolkit).                            |
| viewFile        | The name of view file.                                                                                                                                                                                          |
| viewParams      | The view additional params.                                                                                                                                                                                     |
| redirectUrl     | The route which will be redirected after the user action.                                                                                                                                                       |
| scenario        | The scenario to be assigned to the model before it is validated and updated.                                                                                                                                    |
| primaryKeyParam | The name of the GET parameter that stores the primary key of the model.                                                                                                                                         |
| successCallback | Is called when a successful result.                                                                                                                                                                             |
| errorCallback   | Is called when a failed result.                                                                                                                                                                                 |
| beforeRun       | This method is called right before `run()` is executed. You may override this method to do preparation work for the action run. If the method returns false, it will cancel the action.                         |
| afterRun        | This method is called right after `run()` is executed. You may override this method to do post-processing work for the action run.                                                                              |
| loadedModel     | The previously loaded object of modelClass.                                                                                                                                                                     |
> This is only the basic parameters of the action. 
For details see the source code.

Installation
---

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist voskobovich/yii2-crud-toolkit "^3"
```

or add

```
"voskobovich/yii2-crud-toolkit": "^3"
```

to the require section of your `composer.json` file.

CODE ECOLOGY
---

To auto fix the code format:

```bash
./vendor/bin/php-cs-fixer fix
```
