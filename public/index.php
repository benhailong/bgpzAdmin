<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('PUBLIC_PATH', __DIR__);
define('RUNTIME', __DIR__ . '/../runtime/');
define('FILE_DIR_NAME', '/file/general/');
define('FILE_PATH', __DIR__ . FILE_DIR_NAME);
define('FILE_PROTECTED_PATH', __DIR__ . '/file/protected/');
define('TPL_PATH', __DIR__ . '/tpl/');
define('HTML_PATH', __DIR__ .'/file/html/');
define('HTML_PATH_P', __DIR__ );
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';