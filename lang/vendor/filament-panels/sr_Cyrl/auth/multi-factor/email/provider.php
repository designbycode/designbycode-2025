<?php

return [

    'management_schema' => [

        'actions' => [

            'label' => 'Кодови за верификацију е-поштом',

            'below_content' => 'Прими привремени код на вашу адресу е-поште да бисте верификовали идентитет при пријави.',

            'messages' => [
                'enabled' => 'Укључено',
                'disabled' => 'Искључено',
            ],

        ],

    ],

    'login_form' => [

        'label' => 'Пошаљите код на вашу адресу е-поште',

        'code' => [

            'label' => 'Унесите код од 6 цифара послат на вашу адресу е-поште',

            'validation_attribute' => 'код',

            'actions' => [

                'resend' => [

                    'label' => 'Пошаљите нови код е-поштом',

                    'notifications' => [

                        'resent' => [
                            'title' => 'Послали смо вам нови код е-поштом',
                        ],

                    ],

                ],

            ],

            'messages' => [

                'invalid' => 'Код који сте унели није исправан.',

            ],

        ],

    ],

];
