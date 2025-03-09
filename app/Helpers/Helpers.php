<?php
if (!function_exists('site_title')) {
    function site_title($pageTitle = '')
    {
        $appName = config('app.name');

        return $pageTitle ? " {$appName} | {$pageTitle}" : $appName;
    }
}

if (!function_exists('formatSize')) {
    function formatSize($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $index = 0;

        while ($size >= 1024 && $index < count($units) - 1) {
            $size /= 1024;
            $index++;
        }

        return round($size, $precision) . ' ' . $units[$index];
    }
}

if (!function_exists('getFileIcon')) {
    function getFileIcon($fileName, $isFolder = false)
    {
        $basePath = asset('assets/images/media/file-manager/');

        $defaultFileIcon = $basePath . '/file.png';
        $defaultFolderIcon = $basePath . '/folder.png';

        if ($isFolder) {
            return file_exists(public_path('assets/images/media/file-manager/folder.png'))
                ? $defaultFolderIcon
                : $defaultFileIcon;
        }

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $icons = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp', 'webp'],
            'video' => ['mp4', 'avi', 'mkv', 'mov', 'wmv', 'flv'],
            'audio' => ['mp3', 'wav', 'ogg', 'aac', 'flac'],
            'pdf' => ['pdf'],
            'word' => ['doc', 'docx'],
            'excel' => ['xls', 'xlsx'],
            'powerpoint' => ['ppt', 'pptx'],
            'zip' => ['zip', 'rar', '7z'],
            'code' => ['php', 'html', 'css', 'js', 'py', 'java', 'cpp'],
            'text' => ['txt', 'md', 'log']
        ];

        foreach ($icons as $icon => $extensions) {
            if (in_array($extension, $extensions)) {
                $iconPath = $basePath . '/' . $icon . '.png';

                return file_exists(public_path('assets/images/media/file-manager/' . $icon . '.png'))
                    ? $iconPath
                    : $defaultFileIcon;
            }
        }

        return $defaultFileIcon;
    }
}

if (!function_exists('setActiveRoute')) {
    function setActiveRoute($routes)
    {
        return request()->routeIs(is_array($routes) ? $routes : [$routes]) ? 'active' : '';
    }
}

