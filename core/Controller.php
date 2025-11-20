<?php

namespace Core;

/**
 * Base Controller Class
 * All controllers should extend this class
 */
abstract class Controller
{
    /**
     * Render a view
     * 
     * @param string $view View file path (without .php extension)
     * @param array $data Data to pass to the view
     * @param string $layout Layout file (default: 'layouts/main')
     */
    protected function view($view, $data = [], $layout = null)
    {
        // Extract data to variables
        extract($data);

        // Build view file path
        $viewFile = dirname(__DIR__) . '/resources/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }

        // If layout is specified
        if ($layout !== false) {
            $layoutFile = dirname(__DIR__) . '/resources/views/' . ($layout ?? 'layouts/main') . '.php';
            
            if (file_exists($layoutFile)) {
                // Start output buffering for content
                ob_start();
                require $viewFile;
                $content = ob_get_clean();
                
                // Render layout with content
                require $layoutFile;
            } else {
                // No layout found, render view directly
                require $viewFile;
            }
        } else {
            // No layout requested, render view directly
            require $viewFile;
        }
    }

    /**
     * Render JSON response
     * 
     * @param mixed $data
     * @param int $statusCode
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to another URL
     * 
     * @param string $url
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Redirect back to previous page
     */
    protected function back()
    {
        $previousUrl = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($previousUrl);
    }

    /**
     * Get POST data
     * 
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    protected function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     * 
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    protected function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Get request data (POST or GET)
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function input($key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /**
     * Check if request is POST
     * 
     * @return bool
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Check if request is GET
     * 
     * @return bool
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Check if request is AJAX
     * 
     * @return bool
     */
    protected function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Set flash message in session
     * 
     * @param string $type (success, error, warning, info)
     * @param string $message
     */
    protected function flash($type, $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get and clear flash message
     * 
     * @return array|null
     */
    protected function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Validate CSRF token
     * 
     * @param string|null $token
     * @return bool
     */
    protected function validateCsrf($token = null)
    {
        $token = $token ?? $this->post('csrf_token');
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Generate CSRF token
     * 
     * @return string
     */
    protected function generateCsrf()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Abort with error
     * 
     * @param int $code
     * @param string $message
     */
    protected function abort($code = 404, $message = 'Not Found')
    {
        http_response_code($code);
        $this->view('errors/' . $code, ['message' => $message], false);
        exit;
    }
}
