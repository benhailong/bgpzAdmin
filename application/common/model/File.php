<?php
/**
 * User: HDS
 * Date: 2016/7/22
 * Time: 16:36
 * Description:
 */

namespace app\common\model;

class File extends \think\Model
{
    Protected $autoCheckFields = FALSE;
    /*
     * 文件上传时的错误
     */
    static $errorMsg = array(
        UPLOAD_ERR_INI_SIZE    => '文件大小超出服务器最大限制',
        UPLOAD_ERR_FORM_SIZE   => '文件大小超出浏览器最大限制',
        UPLOAD_ERR_PARTIAL     => '文件只有部分被上传',
        UPLOAD_ERR_NO_FILE     => '没有文件被上传',
        UPLOAD_ERR_NO_TMP_DIR  => '找不到临时文件夹',
        UPLOAD_ERR_CANT_WRITE  => '文件写入失败',
        'illegalFile'          => '非法文件',
        'mkdirFail'            => '创建文件夹失败',
        'canNotWrite'          => '文件夹不可写',
        'noAllowedMimeType'    => '未定义允许的mime类型',
        'tmpFileIsNotReadable' => '临时文件不可读',
        'notAllowedMimeType'   => '暂时不支持该格式',
        'fileSizeWrong'        => '上传文件大小不符合',
    );

    /**
     * 各种文件的mime类型(有多个值是因为浏览器差异)
     * @var unknown
     */
    public static $mimeTypes = array(
        'jpg' => 'image/jpeg,image/pjpeg',
        'gif' => 'image/gif',
        'png' => 'image/png,image/x-png'
    );

    public static $conf = array(

        'user'          => array(
            'subDir'      => '/user/',
            'allowedType' => 'jpg,png',
        ),
        'avatar'        => array(
            'subDir'      => '/avatar/',
            'allowedType' => 'jpg,png',
        ),
        'variety' => array(
            'subDir'      => '/variety/',
            'allowedType' => 'jpg,png',
        ),
        'record'        => array(
            'subDir'      => '/record/',
            'allowedType' => 'jpg,png',
        ),
        'package'         => array(
            'subDir'      => '/package/',
            'allowedType' => 'jpg,png',
        ),
        'company'         => array(
            'subDir'      => '/company/',
            'allowedType' => 'jpg,png',
        ),
        'cook'         => array(
            'subDir'      => '/cook/',
            'allowedType' => 'jpg,png',
        ),
        'ceremony'         => array(
            'subDir'      => '/ceremony/',
            'allowedType' => 'jpg,png',
        ),
        'platform'         => array(
            'subDir'      => '/platform/',
            'allowedType' => 'jpg,png',
        ),
        'wine'         => array(
            'subDir'      => '/wine/',
            'allowedType' => 'jpg,png',
        ),
        'configure'         => array(
            'subDir'      => '/configure/',
            'allowedType' => 'jpg,png',
        ),
        'waiter'=>array(
            'subDir'      => '/waiter/',
            'allowedType' => 'jpg,png',
        ),
        'html'=>array(
            'subDir'      => '/html/',
            'allowedType' => 'jpg,png,gif',
        ),
    );


    /**
     * 检查上传文件(是否存在，是否超出大小限制，是否是上传的文件等)
     * 注意需要使用全等于(===)来比较此函数的返回值
     * @param int $errCode
     * @return boolean|string
     */
    static function chkUploadFile($errCode, $filename)
    {

        if (UPLOAD_ERR_OK == $errCode) {
            return is_uploaded_file($filename) ? TRUE : self::$errorMsg['illegalFile'];
        }
        return self::$errorMsg[$errCode];
    }

    /**
     * 检查上传文件类型
     */
    static function isAllowedType($filename, $subject)
    {
        if (empty(self::$conf[$subject]['allowedType'])) return 'noAllowedMimeType';
        if (!is_readable($filename)) return 'tmpFileIsNotReadable';
        $mimeType = fileInfo($filename);
        $allowedType = explodeAdvanced(self::$conf[$subject]['allowedType']);
        array_flip($allowedType);
        foreach ($allowedType as $key => &$type) {
            if (!isset(self::$mimeTypes[$type])) {
                unset($allowedType[$key]);
                continue;
            }
            $type = self::$mimeTypes[$type];
        }
        $allowedType = join(',', $allowedType);
        if (FALSE === stripos($allowedType, $mimeType)) return 'notAllowedMimeType';
        return TRUE;
    }

    //检查上传文件大小
    static function isAllowedSize($fileSize, $subject)
    {
        if (empty(self::$conf[$subject]['maxsize'])) return true;

        $maxSize = self::$conf[$subject]['maxsize'];
        if ($fileSize > $maxSize) return 'fileSizeWrong';

        return TRUE;
    }

    /**
     * 保存文件
     */
    public function saveFile($key, $subject)
    {
        if (empty($subject)) return FALSE;
        $md5ed = md5_file($_FILES['file']['tmp_name'][$key]) . rand(0000, 9999);
        $savePath = FILE_PATH . trim(self::$conf[$subject]['subDir'], '/') . '/';
        $md5Path = strtolower($md5ed[0] . '/' . $md5ed[strlen($md5ed) - 1]) . '/';
        $saveName = $md5ed . strrchr($_FILES['file']['name'][$key], '.');//将文件的md5值作为文件名
        return TRUE === $this->moveFile($_FILES['file']['tmp_name'][$key], $savePath . $md5Path, $saveName) ? $md5Path . $saveName : FALSE;
    }

    /**
     * 保存文件 - 不能直接访问目录
     */
    public function saveFile_noContent($inputname = 'file', $subject)
    {
        if (empty($subject)) return FALSE;
        $md5ed = md5_file($_FILES[$inputname]['tmp_name']) . rand(0000, 9999);
        $savePath = FILE_PROTECTED_PATH . trim(self::$conf[$subject]['subDir'], '/') . '/';
        $md5Path = strtolower($md5ed[0] . '/' . $md5ed[strlen($md5ed) - 1]) . '/';
        $saveName = $md5ed . strrchr($_FILES[$inputname]['name'], '.');//将文件的md5值作为文件名
        return TRUE === $this->moveFile($_FILES[$inputname]['tmp_name'], $savePath . $md5Path, $saveName) ? $md5Path . $saveName : FALSE;
    }


    /**
     * 将文件移动到指定目录
     */
    private function moveFile($file, $savePath, $saveName)
    {
        $moveTo = $savePath . '/' . $saveName;
        if (is_file($moveTo)) {//该文件已经存在
            return TRUE;
        }
        if (mkdirAuto($savePath)) {
            if (is_writable($savePath)) {
                return move_uploaded_file($file, $moveTo);
            } else {
                return self::$errorMsg['canNotWrite'];
            }
        } else {
            return self::$errorMsg['mkdirFail'];
        }
    }


    /**
     * 将数据库中的图片path转换成可以访问的实际地址
     * @param unknown 从数据库中取出的path
     * @param string 二层文件夹名称
     * @return string 可以访问的src地址
     */
    public static function src($path, $subject = 'proj')
    {
        return FILE_DIR_NAME . trim(self::$conf[$subject]['subDir'], '/') . '/' . $path;
    }


    /**
     * 单例
     */
    private static $instance;

    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}