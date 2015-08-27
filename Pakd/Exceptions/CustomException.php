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
namespace GowonDesigns\Pakd\Exceptions;

interface IException
{
    /* Protected methods inherited from Exception class */
    public function getMessage();                 // Exception message
    public function getCode();                    // User-defined Exception code
    public function getFile();                    // Source filename
    public function getLine();                    // Source line
    public function getTrace();                   // An array of the backtrace()
    public function getTraceAsString();           // Formated string of trace

    /* Overrideable methods inherited from Exception class */
    public function __toString();                 // formated string for display
    public function __construct($message = null, $code = 0);
}

/**
 * Custom Exception Abstract
 *
 * @package GowonDesigns\Pakd
 * @author  Gowon Patterson
 * @since   0.1.0
 */
abstract class CustomException extends \Exception implements IException
{
    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown

    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
        . "{$this->getTraceAsString()}";
		
	// 	do {
    //     printf("%s:%d %s (%d) [%s]\n", $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode(), get_class($e));
    // } while($e = $e->getPrevious());
    }
}