Yii2 Mandrill Module
================================

Install:

1. Config this module in your config file - protected/config/web.php like this:

```
'modules' => [
    .....
    'mail' => [
        'class' => 'voskobovich\mandrill\Module',
    ],
],
```

2. Run module migration by command

```
php yii migrate/up --migrationPath=@app/modules/mail/migrations
```