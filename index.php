<?php

function get_media_from_folder($folder) {
    $media = array();

    if (!file_exists($folder) || !is_dir($folder)) {
        return $media;
    }

    if ($handle = opendir($folder)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mov', 'avi', 'wmv', 'flv', 'ogv', 'ogg', 'webm'];
                if (in_array($extension, $allowedExtensions)) {
                    $media[] = $file;
                }
            }
        }
        closedir($handle);
    }

    return $media;
}

$folder = 'ing/'; // 文件夹名称

if ($media = get_media_from_folder($folder)) {
    $random_media = $media[array_rand($media)];
    $media_path = $folder . '/' . $random_media;

    if (!file_exists($media_path) || !is_readable($media_path)) {
        echo "媒体文件不存在或不可读";
    } else {
        // 根据文件扩展名设置Content-Type
        $extension = strtolower(pathinfo($media_path, PATHINFO_EXTENSION));
        $contentType = null;
        if ($extension == 'mp4') {
            $contentType = 'video/mp4';
        } else if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $contentType = 'image/' . $extension;
        }

        if ($contentType) {
            // 生成元数据文件
            $metadata = [
                'contentType' => $contentType,
                'duration' => '', // 视频时长，留空表示未知
            ];
            file_put_contents($folder . '/metadata.json', json_encode($metadata));

            header('Content-Type: ' . $contentType);
            header('Content-Disposition: inline');
            header('Content-Length: ' . filesize($media_path));

            readfile($media_path);
            exit;
        } else {
            echo "不支持的媒体文件类型";
        }
    }
} else {
    echo "没有找到媒体文件";
}
