<?php
/**
 * Lightemplate engine
 * (alpha version)
 * @author Thomas Collot
 */

namespace Light\App\Core;

use Light\App\Core\Request;

class Lightemplate
{
    /**
     * Base url var name
     * @var string
     * @see app/config/config.php
     */
    private static $base = 'base';
    /**
     * Static assign of template directory
     * @param string template directory
     */
    public static function base($value)
    {
        self::$base = $value;
    }

    /**
     * Assets var name
     * @var string
     * @see app/config/config.php
     */
    private static $assets = 'assets';
    /**
     * Static assign of template directory
     * @param string template directory
     */
    public static function assets($value)
    {
        self::$assets = $value;
    }

    /**
     * Template directory
     * @var string
     * @see app/config/config.php
     */
    private static $dir = null;
    /**
     * Static assign of template directory
     * @param string template directory
     */
    public static function dir($value)
    {
        self::$dir = $value;
    }

    /**
     * Cache directory
     * @var string
     * @see app/config/config.php
     */
    private static $cache = null;
    /**
     * Cache expired time
     * @var string
     * @see app/config/config.php
     */
    private static $expire = null;
    /**
     * Static assign of cache directory
     * @param string cache directory
     */
    public static function cache($dir, $time)
    {
        self::$cache = $dir;
        self::$expire = time() - $time;
    }
    /**
     * Do we use cache ?
     * @return boolean
     */
    private static function usecache()
    {
        return null !== self::$cache;
    }

    /**
     * Name of the compiled PHP file
     * @var string
     */
    private $name;

    /**
     * Content that will be displayed to the user
     * @var string
     */
    private $content;

    /**
     * Array of all vars past to the template file
     * @var associative array
     */
    private $vars;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->name = null;
        $this->content = null;
        $this->vars = array();

        // Assign of the base url & assets root
        $this->assign(self::$base, _base);
        $this->assign(self::$assets, _assets);
    }

    /**
     * Compile template file
     * @param $name file to compile
     * @param $parent name of parent template
     * @param $blocks array of child blocks
     */
    private function compile($name, $parent = null, $blocks = null)
    {
        $compiled = null;
        $code = null;

        // If a parent is set, we gonna load this file and replace the child blocks
        if (null !== $parent) {
            $code = file_get_contents(self::$dir . $parent);

            // Replace all child blocks
            foreach ($blocks as $key => $value) {
                $blockContent = null;

                foreach ($value as $string) {
                    $blockContent .= $string;
                }

                $code = str_replace("@block " . $key, $blockContent, $code);
            }
        } else {
            $code = file_get_contents(self::$dir . $name);
        }

        // All Lightemplate engine tags
        $tags = array(
            'comments'   => '(\/\/@\s*.*)',
            'use'        => '(@use.*)',
            'block'      => '(@block.*)',
            'if'         => '(@if.*)',
            'elseif'     => '(@elseif.*)',
            'else'       => '(@else)',
            'endif'      => '(@endif)',
            'foreach'    => '(@foreach.*)',
            'endforeach' => '(@endforeach)',
            'var'        => '(@\S+)'
        );

        $tags = "/" . join("|", $tags) . "/";

        // Split all the code with tags
        $code = preg_split($tags, $code, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $code = array_filter($code);

        // Set parent to null
        $parent = null;
        $blocks = array();

        // Loop on each code lines
        while ($string = array_shift($code)) {
            if (preg_match("/\/\/@\s*(.*)/", $string, $result) || false !== strpos($string, "@endblock")) {
                // Comments
                continue;
            } elseif (preg_match("/@use.*(\"|')(.*)(\"|')/", $string, $result)) {
                // Include template
                $parent = $result[2];
            } elseif (preg_match("/@block\s+(.*)/", $string, $result)) {
                // Block
                $blockName = $result[1];
                $blocks[$blockName] = array();

                while (($string = array_shift($code)) != "@endblock") {
                    array_push($blocks[$blockName], $string);
                }
            } elseif (preg_match("/@dump.*\((.*)\)/", $string, $result)) {
                // Dump
                $var = '$' . $result[1];

                $compiled .= '<?= var_dump(' . $var . '); ?>';
            } elseif (preg_match("/@if.*\(([!]?)(\w+)\)/", $string, $result)) {
                // "if isset or !isset"
                $operator = $result[1];
                $var = '$' . $result[2];

                if (null == $operator) {
                    $compiled .= '<?php if (isset(' . $var . ')): ?>';
                } else {
                    $compiled .= '<?php if (!isset(' . $var . ')): ?>';
                }
            } elseif (preg_match("/@if.*\((\w+|\w+)\s+(==|!=|>=|<=|>|<)\s+(\"\w+\"|'\w+'|\w+)\)/", $string, $result)) {
                // Conditionnal if
                $var = '$' . $result[1];
                $operator = $result[2];
                $condition = $result[3];
                $compiled .= '<?php if (' . $var . ' ' . $operator . ' ' . $condition . '): ?>';
            } elseif (preg_match("/@elseif.*\(([!]?)(\w+)\)/", $string, $result)) {
                // "else if isset or !isset"
                $operator = $result[1];
                $var = '$' . $result[2];

                if (null == $operator) {
                    $compiled .= '<?php elseif (isset(' . $var . ')): ?>';
                } else {
                    $compiled .= '<?php elseif (!isset(' . $var . ')): ?>';
                }
            } elseif (preg_match("/@elseif.*\((\w+|\w+)\s+(==|!=|>=|<=|>|<)\s+(\"\w+\"|'\w+'|\w+)\)/", $string, $result)) {
                // Conditionnal else if
                $var = '$' . $result[1];
                $operator = $result[2];
                $condition = $result[3];
                $compiled .= '<?php elseif (' . $var . ' ' . $operator . ' ' . $condition . '): ?>';
            } elseif (false !== strpos($string, '@else')) {
                // else
                $compiled .= '<?php else: ?>';
            } elseif (false !== strpos($string, '@endif')) {
                // end if
                $compiled .= '<?php endif; ?>';
            } elseif (preg_match("/@foreach.*\((\w+)\s+as+\s(\w+)\)/", $string, $result)) {
                // foreach
                $iterations = '$' . $result[1];
                $item = '$' . $result[2];
                $compiled .= '<?php foreach(' . $iterations . ' as ' . $item . '): ?>';
            } elseif (false !== strpos($string, '@endforeach')) {
                // end foreach
                $compiled .= '<?php endforeach; ?>';
            } elseif (preg_match_all('/@(\w+)->(\w+)|@(\w+)/', $string, $result)) {
                foreach ($result[0] as $key => $value) {
                    // Object case
                    if (null != $result[1][$key]) {
                        $var = '$' . $result[1][$key];
                        $attribute = $result[2][$key];
                        $string = str_replace($value, '<?= ' . $var . '->' . $attribute . ' ?>', $string);
                    } else { // Var case
                        $var = '$' . $result[3][$key];
                        $string = str_replace($value, '<?= ' . $var . ' ?>', $string);
                    }
                }

                $compiled .= $string;
            } else {
                $compiled .= $string;
            }
        }

        // If a parent is set, we need to compile the parent file with the child blocks
        if (null !== $parent) {
            $this->compile($name, $parent, $blocks);
        } else {
            $this->name = substr(basename($name), 0, -5) . '.light.php';

            if (self::usecache()) {
                // If the cache folder doesn't exist
                if (!is_dir(self::$cache)) {
                    mkdir(self::$cache, 0755, true);
                }

                file_put_contents(self::$cache . $this->name, $compiled);
            } else {
                file_put_contents(self::$dir . $this->name, $compiled);
            }
        }
    }

    /**
     * Assign a value on a template key
     * @param string key
     * @param string value
     */
    public function assign($key, $val)
    {
        if (is_array($key)) {
            $this->vars += $key;
        } else {
            $this->vars[$key] = $val;
        }
    }

    /**
     * Display the compiled template file
     * @param string template file
     */
    public function display($file)
    {
        if (self::usecache()) {
            $filename = self::$cache . substr(basename($file), 0, -5) . '.light.php';

            // If the file is not compiled yet OR the cache file is expired, compile it
            if (!file_exists($filename) || filemtime($filename) < self::$expire) {
                $this->compile($file);
            }
        } else {
            $this->compile($file);
            $filename = self::$dir . substr(basename($file), 0, -5) . '.light.php';
        }

        // Turn on the output buffering, extract template vars then include the compiled/cached file
        ob_start();
        extract($this->vars);
        include $filename;
        $this->content = ob_get_clean();

        // If we don't use cache engine, just delete the compiled file, we don't need it anymore
        if (!self::usecache()) {
            // Unlink compiled files
            unlink($filename);
        }

        // Display the content to the user
        echo $this->content;

        $this->init();
    }
}
