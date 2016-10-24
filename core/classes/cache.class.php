<?php

defined('SBCMS') or exit('404 - Not Found');

class cache
{
    protected static $_aCache = array();
    
    protected $cacheFolder = "";

    protected $aCacheDelete = array();
    
    protected $sigleServer = true;


    public function __construct($sigleServer = true)
    {
        $this->sigleServer = $sigleServer;
        
        $this->cacheFolder = DIR_ROOT_CACHE;
    }
    
    public function cacheData()
    {
        $this->cacheFolder = DIR_ROOT_CACHE;
    }
    
    public function setRoot($path) {
        if(is_dir($path))
            $this->cacheFolder = $path;
    }
    
    public function get($sId, $folder = "")
    {
        $hash = md5($sId . $folder);
        if (isset(self::$_aCache[$hash]))
        {
            return self::$_aCache[$hash];
        }
        $sName = $this->_getName($sId, $folder);

        if (!is_file($sName)){
            return false;
        }

        $aContent = false;
        
        require $sName;

        if (!isset($aContent))
        {
            $aContent = false;
        }

        self::$_aCache[$hash] = $aContent;
        
        return $aContent;
    }
    
    public function save($sId, $mContent, $folder = "")
    {
        if (!is_dir($this->cacheFolder . $folder . '/'))
            mkdir($this->cacheFolder . $folder . '/', 0777 , true);
            
        $sContent = '<?php defined(\'SBCMS\') or exit(\'404 - Not Found\'); ?>' . "\n";
        $sContent .= '<?php $aContent = ' . var_export($mContent, true) . '; ?>';
        if ($rOpen = @fopen($this->_getName($sId, $folder), 'w+'))
        {
            fwrite($rOpen, $sContent);
            fclose($rOpen);
            return true;
        }
        else return false;
    }
    
     public function set($sId, $mContent, $folder = "")
    {
       $this ->save($sId, $mContent, $folder);
    }

    public function delete($sName = '', $folder = "")
    {
        $sName = $sName . "";
        
        $sName = $this->_getName($sName, $folder);
        if (file_exists($sName))
        {
            @unlink($sName);
        }
        
        self::$_aCache = array();
        return true;
    }

    public function getAll($folder = "")
    {
        static $aFiles = array();

        if ($aFiles)
        {
            return $aFiles;
        }

        if ($hDir = @opendir($this->cacheFolder . $folder . '/'))
        {
            while ($sFile = readdir($hDir))
            {
                if ($sFile == '.'
                        || $sFile == '..'
                        || $sFile == '.svn'
                        || $sFile == '.htaccess'
                        || $sFile == 'index.html'
                        || $sFile == 'debug.php'
                )
                {
                    continue;
                }

                $aFiles[] = array(
                    'id' => md5($sFile),
                    'name' => $sFile,
                    'size' => filesize($this->cacheFolder . $folder . '/' . $sFile),
                    'date' => filemtime(($this->cacheFolder . $folder . '/' . $sFile)),
                    'type' => 'File'
                );
            }
            closedir($hDir);

            return $aFiles;
        }

        return array();
    }

    private function _getName($sFile, $folder = "")
    {
        return $this->cacheFolder . $folder . '/' . $sFile . '.php';
    }
}

?>
