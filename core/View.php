<?php

namespace Core;

/**
 * View Class
 * Helper class for rendering views
 */
class View
{
    /**
     * Render a view
     * 
     * @param string $view
     * @param array $data
     * @param string|null $layout
     */
    public static function render($view, $data = [], $layout = 'layouts/main')
    {
        extract($data);

        $viewFile = dirname(__DIR__) . '/resources/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }

        if ($layout !== false && $layout !== null) {
            $layoutFile = dirname(__DIR__) . '/resources/views/' . $layout . '.php';
            
            if (file_exists($layoutFile)) {
                ob_start();
                require $viewFile;
                $content = ob_get_clean();
                require $layoutFile;
            } else {
                require $viewFile;
            }
        } else {
            require $viewFile;
        }
    }

    /**
     * Check if flash message exists
     * 
     * @return bool
     */
    public static function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    /**
     * Get and clear flash message
     * 
     * @return array|null
     */
    public static function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Escape HTML
     * 
     * @param string $string
     * @return string
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Build asset URL
     * 
     * @param string $path
     * @return string
     */
    public static function asset($path)
    {
        $appConfig = require dirname(__DIR__) . '/config/app.php';
        $baseUrl = rtrim($appConfig['url'], '/');
        $path = ltrim($path ?? '', '/');
        return $baseUrl . '/' . $path;
    }
}
