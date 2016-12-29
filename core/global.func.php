<?php
/**
 * 遍历获取目录下的指定类型的文件
 * @param $path
 * @param array $files
 * @return array
 */
function getfiles($path, &$files = array())
{
    if (!is_dir($path)) return null;
    $handle = opendir($path);
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            // $path2 = $path . '/' . $file;
            $path2 = $path . '/' . $file;
            if (is_dir($path2)) {
                getfiles($path2, $files);
            } else {
                if (preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i", $file)) {
                    if (substr($file, 0, 6) != 'small_') //不要缩略图
                        $files[] = $path2;
                }
            }
        }
    }
    return $files;
}

/**
 * 删除整个目录
 * @param $dir
 * @return bool
 */
function delDir($dir)
{
    //先删除目录下的所有文件：
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                delDir($fullpath);
            }
        }
    }
    closedir($dh);
    //删除当前文件夹：
    return rmdir($dir);
}