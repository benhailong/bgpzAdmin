<?php
/**
 * User: HDS
 * Date: 2016/7/22
 * Time: 16:20
 * Description:
 */
namespace app\admin\controller;

use \think\Request;

class File extends \app\common\controller\Base
{
    public static $mimeType = array(
        'jpg' => 'image/jpeg'
    );

    public function uploadPic()
    {

        $dir = input('get.dir');

        if (empty($_FILES)) {
            return $this->api_error('FILE is empty.', 102);
        }

        $fModel = \app\common\model\File::instance();

        if (count($_FILES['file']['name']) > 1) {
            return $this->api_error('批量上传还未实现');
        }
        $chkResult = $fModel::chkUploadFile($_FILES['file']['error'][0], $_FILES['file']['tmp_name'][0]);
        if (TRUE !== $chkResult) {
            return $this->api_error($chkResult, null, $_FILES['file']['error'][0]);
        }
        $subject = input('request.subject', $dir);
        $chkResult = $fModel::isAllowedType($_FILES['file']['tmp_name'][0], $subject);
        if (TRUE !== $chkResult) {
            return $this->api_error($fModel::$errorMsg[$chkResult], null, $chkResult);
        }
        //大小限制
        $chkSize = $fModel::isAllowedSize($_FILES['file']['size'][0], $subject);
        if (TRUE !== $chkSize) {
            return $this->api_error($fModel::$errorMsg[$chkSize], null, $chkSize);
        }
        $saveName = $fModel->saveFile(0, $subject);
        if (FALSE === $saveName) {
            return $this->api_error('saveFail');
        }
        $return = array('stat' => 'succ', 'path' => $saveName);
        if ($_REQUEST['src_path']) {
            $return['src_path'] = $fModel::src($saveName, $subject);
        }
        $return['aa'] = $_FILES['file']['tmp_name'][0];
        $imgs = explode('.', $return['src_path']);

        $image = \think\Image::open(PUBLIC_PATH . '/' . $return['src_path']);

        // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
//        $image->thumb(50, 50)->save(PUBLIC_PATH . '/' . $imgs[0] . "_50_50." . $imgs[1]);
        return json($return);
    }

    public function deletePic()
    {
        $src = input('get.src');
        $act = input('get.act');
        $src2 = explode('.', $src);
        unlink(FILE_PATH . '/' . $act . '/' . $src);
        unlink(FILE_PATH . '/' . $act . '/' . $src2[0] . "_50_50." . $src2[1]);
    }
}