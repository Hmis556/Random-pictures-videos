function get_media_from_folder($folder) {
    $media = array();

    if ($handle = opendir($folder)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4'])) {
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
    $media_path = $folder . $random_media;

    if (!file_exists($media_path) || !is_readable($media_path)) {
        echo "媒体文件不存在或不可读";
    } else {
        // 根据文件扩展名设置Content-Type
        $extension = pathinfo($media_path, PATHINFO_EXTENSION);
        $contentType = null;
        if ($extension == 'mp4') {
            $contentType = 'video/mp4';
        } else if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $contentType = 'image/' . strtolower($extension);
        }

        if ($contentType) {
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
