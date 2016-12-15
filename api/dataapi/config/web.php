<?php
$baseurl = dirname(__DIR__);
Yii::setAlias('@common', $baseurl . "/../common");

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log'
    ],
    'vendorPath' => "@common/vendor",
    'language' => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'aliases' => [
        '@utils' => '@common/utils'
    ],

    'modules' => [
        'test' => [
            'class' => 'app\modules\test\Module'
        ],
        'attendance' => [
            'class' => 'app\modules\attendance\Module'
        ],
        'cms' => [
            'class' => 'app\modules\cms\Module'
        ],
        'bs' => [
            'class' => 'app\modules\bs\Module'
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module'
        ],
        'member' => [
            'class' => 'app\modules\member\Module'
        ],
        'db' => [
            'class' => 'app\modules\db\Module'
        ],
        'coupon' => [
            'class' => 'app\modules\coupon\Module'
        ],
        'isorder' => [
            'class' => 'app\modules\isorder\Module'
        ],
        'supply' => [
            'class' => 'app\modules\supply\Module'
        ],
        'need' => [
            'class' => 'app\modules\need\Module'
        ],
        'informationdb' => [
            'class' => 'app\modules\informationdb\Module'
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'testas',
            'enableCsrfValidation' => false,   //跨域提交问题,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

        'user' => [
            'identityClass' => '',
            'enableAutoLogin' => false
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache'
        ],

        'errorHandler' => [
            'errorAction' => 'error/index'
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],

            ]

        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => [
                        'error',
                        'warning',
                        'trace',
                        'info'
                    ]
                ]
            ]
        ],
        'db' => require(__DIR__ . '/../../common/config/db.php'),


        'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'test/default'],
            ]
        ]
    ],
    'params' => require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module'
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module'
    ];
}

return $config;
