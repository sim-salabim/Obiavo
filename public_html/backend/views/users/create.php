<?php
use yii\helpers\Url;

$form = [
    [
        'panel-title' => 'Пользователь',
        'columns' => [
            // attribute:typeField:label
            ['attributes' => 'email:text:Email', 'model' => $user],
            ['attributes' => 'first_name:text:Имя', 'model' => $user],
            ['attributes' => 'last_name:text:Фамилия', 'model' => $user],
            ['attributes' => 'patronymic:text:Отчество', 'model' => $user],
            [
                'attributes' => 'patronymic:button-input:Сгенерировать пароль',
                'model' => $user,
                'options' => [
                    'btn' => [
                        'class' => 'btn btn-info senddata',
                        'data-link' => Url::toRoute('generate-password'),
                        'data-input' => '#form-update',
                    ],
                    'input' => [
                        'id' => 'password-input',
                        'name' => 'User[password]',
                        'class' => 'form-control'
                    ]
                ],
                'params' => [
                    'btn-text' => 'Сгенерировать'
                ]
            ],
        ]
    ],
];

$saveUrl = $toUrl;

echo $this->render('/templates/form',compact('form','saveUrl'));