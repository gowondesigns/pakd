<?php
/**
 * Pakd
 *
 * @author      Gowon Patterson <info@gowondesigns.com>
 * @copyright   2015 Gowon Designs Ltd. Co.
 * @link        http://www.gowondesigns.com
 * @version     0.1.0
 * @package     GowonDesigns\Pakd
 */
namespace GowonDesigns\Pakd;

/**
 * Pakd
 * @package  Pakd
 * @author   Gowon Patterson
 * @since    0.1.0
 */
class Pakd
{
    /**
     * @const string
     */
    const VERSION = '0.1.0';
    
    /********************************************************************************
    * PSR-0 Autoloader
    *
    * Do not use if you are using Composer to autoload dependencies.
    *******************************************************************************/
    
    /**
     * Pakd PSR-0 autoloader
     */
    public static function autoload($className)
    {
        $thisClass = str_replace(__NAMESPACE__.'\\', '', __CLASS__);
        $baseDir = __DIR__;
        if (substr($baseDir, -strlen($thisClass)) === $thisClass) {
            $baseDir = substr($baseDir, 0, -strlen($thisClass));
        }
        $className = ltrim($className, '\\');
        $fileName  = $baseDir;
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        if (file_exists($fileName)) {
            require $fileName;
        }
    }
    /**
     * Register Pakd's PSR-0 autoloader
     */
    public static function registerAutoloader()
    {
        spl_autoload_register(__NAMESPACE__ . "\\Pakd::autoload");
    }
	
	/********************************************************************************
    * Instantiation and Configuration
    *******************************************************************************/
    
	/**
     * Constructor
     * @param  array $userSettings Associative array of application settings
     */
	public function __construct($configurationPath = NULL)
	{
		
	}
    
    /********************************************************************************
    * Runner
    *******************************************************************************/
    
    /**
     * Run
     *
     * This method invokes the Pakd stack
     */
    public function run()
    {
        set_error_handler(array('\Pakd\Pakd', 'handleErrors'));
        
        render();
        
        restore_error_handler();
    }
    
    
    public function render(){
        try {
            list($content, $compress) = self::Initialize();

            // This process is necessary for determining the proper length of the compressed content
            // Reference http://stackoverflow.com/a/816057
            ob_start();
            ob_start($compress);
            echo $content;
            ob_end_flush();

            header('Content-Length: ' . ob_get_length());
            if (self::$_enableDebug) {
                self::GenerateDebugHeaders();
            }
            
            ob_end_flush();
        }
        catch(\Exception $e)
        {
            $exception = new \ReflectionClass($e);
            self::AddDebugHeader("X-Pakd-Exception", $exception->getName());
            self::AddDebugHeader("X-Pakd-Exception-Msg", $e->getMessage());
            header('HTTP/1.1 500 Internal Server Error');
            self::GenerateDebugHeaders();
        }

        exit;
    }
    /********************************************************************************
    * Error Handling and Debugging
    *******************************************************************************/
    
    /**
     * Convert errors into ErrorException objects
     *
     * This method catches PHP errors and converts them into \ErrorException objects;
     * these \ErrorException objects are then thrown and caught by Slim's
     * built-in or custom error handlers.
     *
     * @param  int            $errno   The numeric type of the Error
     * @param  string         $errstr  The error message
     * @param  string         $errfile The absolute path to the affected file
     * @param  int            $errline The line number of the error in the affected file
     * @return bool
     * @throws \ErrorException
     */
    public static function handleErrors($errno, $errstr = '', $errfile = '', $errline = '')
    {
        if (!($errno & error_reporting())) {
            return;
        }
        throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    }
    
    private function AddDebugHeader($header, $message) {
        self::$_debugMessages[$header] = $message;
    }

    private function GenerateDebugHeaders() {
        foreach(self::$_debugMessages as $header => $message) {
            header(sprintf("%s : %s", $header, $message));
        }
    }
}