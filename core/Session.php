<?php

namespace Core;

/**
 * Session Class
 * Handles session management
 */
class Session
{
    /**
     * Start session if not already started
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $appConfig = require dirname(__DIR__) . '/config/app.php';
            
            // Set session configuration
            ini_set('session.cookie_httponly', $appConfig['session']['httponly'] ? '1' : '0');
            ini_set('session.cookie_secure', $appConfig['session']['secure'] ? '1' : '0');
            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_samesite', 'Lax');
            
            // Set session save path
            if (isset($appConfig['session']['path'])) {
                $sessionPath = $appConfig['session']['path'];
                if (!is_dir($sessionPath)) {
                    mkdir($sessionPath, 0777, true);
                }
                session_save_path($sessionPath);
            }
            
            session_start();
            
            // Regenerate session ID periodically
            self::regenerateId();
        }
    }

    /**
     * Set session value
     * 
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     * 
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session key
     * 
     * @param string $key
     */
    public static function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy session
     */
    public static function destroy()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            
            // Delete session cookie
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
            
            session_destroy();
        }
    }

    /**
     * Regenerate session ID
     */
    public static function regenerateId()
    {
        // Only regenerate every 30 minutes to avoid excessive regeneration
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }

    /**
     * Set flash message
     * 
     * @param string $type
     * @param string $message
     */
    public static function flash($type, $message)
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
     * Check if user is logged in
     * 
     * @return bool
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get logged in user ID
     * 
     * @return int|null
     */
    public static function userId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get logged in user role
     * 
     * @return string|null
     */
    public static function userRole()
    {
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Check if user has specific role
     * 
     * @param string $role
     * @return bool
     */
    public static function hasRole($role)
    {
        return self::userRole() === $role;
    }

    /**
     * Get all session data
     * 
     * @return array
     */
    public static function all()
    {
        return $_SESSION;
    }
}
