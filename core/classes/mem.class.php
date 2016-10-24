<?php

defined('PHPVIET') or exit('404 - Not Found');

class Phpviet_Library_Cache_Mem {

    protected $aMemcache = array();
    private $aServerIP = array();
    private $app = "vnedu_";

    function getIndex($s = "") {
        $s = md5($s);
        $t = 0;
        for ($i = 0; $i < strlen($s); ++$i)
            $t += ord($s[$i]);
        $countSV = count($this->aServerIP);
        return $countSV ? $t % count($this->aServerIP) : 0;
    }

    public function __construct() {
        //$db = phpviet::getLib("data.gateway");
        $mMemcache = phpviet::getLib("model.core.memcache");
        $this->aServerIP = $mMemcache->get();
    }

    private function connect($i) {
        $countSV = count($this->aServerIP);
        if ($countSV == 0)
            return false;
        $i = $i % $countSV;

        $this->aServerIP[$i]["port"] = (int) $this->aServerIP[$i]["port"] ? (int) $this->aServerIP[$i]["port"] : 11211;
        $this->aServerIP[$i]["time_out"] = (int) $this->aServerIP[$i]["time_out"] ? (int) $this->aServerIP[$i]["time_out"] : 3600;

        $this->aMemcache[$i] = false;
        if (class_exists("Memcache")) {
            $this->aMemcache[$i] = new Memcache();
            if (!@$this->aMemcache[$i]->connect($this->aServerIP[$i]["ip"], $this->aServerIP[$i]["port"])) {
                //$db = phpviet::getLib("data");
                //$db->query("update phpviet_memcache set active = 0 where id={$this->aServerIP[$i]["id"]["id"]}");
                $this->aMemcache[$i] = false;
                $sServerIP = $_SERVER["SERVER_ADDR"];
                $sMemcacheIP = $this->aServerIP[$i]["ip"];
                phpviet::sysLog("memcache", "SERVER IP $sServerIP - MEMCACHE IP $sMemcacheIP");
            }
        } else if (class_exists("Memcached")) {
            $this->aMemcache[$i] = new Memcached();
            if (!@$this->aMemcache[$i]->addServer($this->aServerIP[$i]["ip"], $this->aServerIP[$i]["port"])) {
                //$db = phpviet::getLib("data");
                //$db->query("update phpviet_memcache set active = 0 where id={$this->aServerIP[$i]["id"]["id"]}");
                $this->aMemcache[$i] = false;
            }
        }
        return $this->aMemcache[$i];
    }

    public function get($sName) {
    	$time1 = microtime();
        if (count($this->aServerIP) == 0)
            return false;
        if (!$sName)
            return false;
        $sName = PHPVIET_DATA_GATEWAY_HOST.$sName;
        $i = $this->getIndex($sName);
        $sName = $this->app . "$sName";
        if (!isset($this->aMemcache[$i]))
            $this->aMemcache[$i] = $this->connect($i);
        if (!$this->aMemcache[$i])
            return false;
        $returnVal = $this->aMemcache[$i]->get($sName);
        $time2 = microtime() - $time1;
        if ($time2 >= 1) error_log("GET MEMCACHE CHAM: [". $time2 ."]". " memcache .$i");
        if (is_array($returnVal))
            return count($returnVal) > 0 ? $returnVal : false;
        else
            return $returnVal;
    }

    public function set($sName, $oValue, $iTime = false) {
        if (count($this->aServerIP) == 0)
            return false;
        if (!$sName)
            return false;
        $sName = PHPVIET_DATA_GATEWAY_HOST.$sName;
        $i = $this->getIndex($sName);
        $sName = $this->app . "$sName";
        if (!isset($this->aMemcache[$i]))
            $this->aMemcache[$i] = $this->connect($i);
        if (!$this->aMemcache[$i])
            return false;
        $iTime = $iTime === false && (int) $this->aServerIP[$i]["time_out"] ? (int) $this->aServerIP[$i]["time_out"] : (int) $iTime;
        $iTime = $iTime ? $iTime : 3600;
        return $this->aMemcache[$i]->set($sName, $oValue, 0, $iTime);
    }

    public function delete($sName) {
        if (count($this->aServerIP) == 0)
            return false;
        if (!$sName)
            return false;
        $sName = PHPVIET_DATA_GATEWAY_HOST.$sName;
        $i = $this->getIndex($sName);        
        $sName = $this->app . "$sName";
        if (!isset($this->aMemcache[$i]))
            $this->aMemcache[$i] = $this->connect($i);
        if (!$this->aMemcache[$i])
            return false;
        return $this->aMemcache[$i]->delete($sName);
    }

    public function del($sName) {
        return $this->delete($sName);
    }

    public function flush() {
        $countSV = count($this->aServerIP);
        if ($countSV) {
            for ($i = 0; $i < $countSV; ++$i) {
                $this->aMemcache[$i] = $this->connect($i);
                if ($this->aMemcache[$i])
                    $this->aMemcache[$i]->flush();
            }
        }
    }

}