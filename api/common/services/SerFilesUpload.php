<?php
/**
 * 开发工具: PhpStorm.
 * 作   者: mybook-lhp
 * 日   期: 16/11/3
 * 功能说明:
 */

namespace common\services;


class SerFilesUpload
{


    /**
     * 判断CURL是否支持curl_file_create函数
     * @param $filename
     * @param $contentType
     * @param $postname
     * @return \CURLFile|string
     */
    static private function getCurlValue($filename, $contentType, $postname)
    {

        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $contentType, $postname);
        }

        // Use the old style if using an older version of PHP
        $value = "@{$filename};filename=" . $postname;
        if ($contentType) {
            $value .= ';type=' . $contentType;
        }

        return $value;
    }


    /**
     * 跨域文件上传至远程服务器,支持多文件和单文上传
     * @param $Upurl  string      远程服务器接收地址
     * @param $data array       验证文件 类型:$data['type']; 大小 $data['size'] 保存路径:$data['path']
     * @param $filename string  临时文件路径及名称
     * @return string           成功返回远程服务器信息
     */
    static function arrCurlupload($Upurl, $filename, $data)
    {
        //判断是否有上传文件
        if (empty($_FILES)) {
            return false;
        }
        //判断是单文件上传还是多文件上传
        if (is_array(end($_FILES[array_keys($_FILES)[0]]))) {

            $arr = null;
            foreach ($_FILES[array_keys($_FILES)[0]] as $k => $v) {
                foreach ($v as $kk => $vv) {
                    if ($kk == $kk) {
                        $arr[$kk][$k] = $vv;
                    };
                }
            }

        } else {
            $arr[] = $_FILES[array_keys($_FILES)[0]];
        }

        //循环处理上传
        $re = null;
        foreach ($arr as $item) {
            $re[] = self::CurlUpload($Upurl, $filename, $data, $item);
        }

        return $re;

    }

    static function FileSave($filepath)
    {

        $patharr = explode('/', $_SERVER['DOCUMENT_ROOT']);
        array_pop($patharr);
        array_pop($patharr);
        $Files = self::getFiles();
        $filenames = [];
        foreach ($Files as $File) {
            $postdata = fopen($File['tmp_name'], "r");
            $ext = explode('/', $File['type']);
            $extension = end($ext);
            $filename = $filepath . uniqid() . "." . $extension;
            $fp = fopen(join('/', $patharr) . $filename, "w");
            while ($data = fread($postdata, 1024))
                fwrite($fp, $data);
            fclose($fp);
            fclose($postdata);
            $filenames[] = $filename;
        }
        return $filenames;
    }

    static function getFiles()
    {
        $i = 0;
        $files = [];
        foreach ($_FILES as $file) {
            if (is_string($file['name'])) {
                //如果是单文件
                $files[$i] = $file;
                $i++;
            } elseif (is_array($file['name'])) {
                //如果是多文件
                foreach ($file['name'] as $key => $val) {
                    $files[$i]['name'] = $file['name'][$key];
                    $files[$i]['type'] = $file['type'][$key];
                    $files[$i]['tmp_name'] = $file['tmp_name'][$key];
                    $files[$i]['error'] = $file['error'][$key];
                    $files[$i]['size'] = $file['size'][$key];
                    $i++;
                }
            }
        }
        return $files;
    }

    /**
     *
     * 跨域单文件上传至远程服务器
     * @param $Upurl  string      远程服务器接收地址
     * @param $data array       验证文件 类型:$data['type']; 大小 $data['size'] 保存路径:$data['path']
     * @param $filename string  临时文件路径及名称
     * @param null $Files
     * @return string           成功返回远程服务器信息
     *
     */
    static private function CurlUpload($Upurl, $filename, $data, $Files)
    {

        if (isset($data['type'])) {
            $type = explode(',', $data['type']);

            $Ftype = explode('/', $Files['type']);
            $Ftype = end($Ftype);
            if (!in_array($Ftype, $type)) {
                echo '上传类型不符合要求';
                exit;
            }
        }

        if (isset($data['size'])) {
            if ($Files['size'] > $data['size']) {
                echo '上传文件过大';
                exit;
            }
        }


        $data = json_encode($data);//编码data数据
        $filename = $filename . uniqid();

        move_uploaded_file($Files["tmp_name"], $filename);

        $cfile = self::getCurlValue($filename, $Files['type'], $Files['name']);
        //  初始化
        $re = null;
        $imgdata = array('myimage' => $cfile, 'data' => $data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $Upurl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $imgdata);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curl);
        $img = curl_multi_getcontent($curl);
        curl_close($curl);
        unlink($filename);
        if ($img) {
            $return['img'] = $img;
            $return['size'] = $Files['size'];
            $return['type'] = $Files['type'];
            $return['name'] = $Files['name'];

            return $return;
        } else {
            echo '远程服务器异常';
            exit;
        }
    }


    /**
     * 文件删除方法
     * @param $Upurl    string 远程文件服务器地址
     * @param $filename string 文件名称
     * @return bool     正确删除返回true
     */
    static public function delete($Upurl, $filename)
    {


        $files = array('delete' => $filename);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $Upurl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $files);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($curl);
        $re = curl_multi_getcontent($curl);
        curl_close($curl);
        return $re;
    }

}