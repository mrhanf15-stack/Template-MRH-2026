<?php
/* =====================================================================
   MRH 2026 Template – Dashboard Kern (Modul-Loader & Config-Manager)
   
   Modulares System für Template-Erweiterungen.
   Jedes Modul liegt in admin/mrh_dashboard/modules/{modul_id}/
   und wird automatisch erkannt und geladen.
   
   Pfad: templates/tpl_mrh_2026/admin/includes/mrh_dashboard.php
   ===================================================================== */

if (!defined('_VALID_XTC') && !defined('DIR_FS_CATALOG')) {
    return;
}

class MRH_Dashboard {
    
    private static $instance = null;
    private $modules = [];
    private $tpl_dir;
    private $modules_dir;
    private $config_dir;
    
    /**
     * Singleton
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->tpl_dir = DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/';
        $this->modules_dir = $this->tpl_dir . 'admin/mrh_dashboard/modules/';
        $this->config_dir = $this->tpl_dir . 'config/';
        
        // Config-Verzeichnis sicherstellen
        if (!is_dir($this->config_dir)) {
            mkdir($this->config_dir, 0755, true);
        }
        
        $this->discoverModules();
    }
    
    /**
     * Module aus dem modules/ Ordner automatisch erkennen
     */
    private function discoverModules() {
        if (!is_dir($this->modules_dir)) {
            return;
        }
        
        $dirs = glob($this->modules_dir . '*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $moduleJson = $dir . '/module.json';
            if (file_exists($moduleJson)) {
                $meta = json_decode(file_get_contents($moduleJson), true);
                if ($meta && isset($meta['id'])) {
                    $meta['_path'] = $dir . '/';
                    $meta['_active'] = $this->isModuleActive($meta['id']);
                    $this->modules[$meta['id']] = $meta;
                }
            }
        }
        
        // Nach sort_order sortieren
        uasort($this->modules, function($a, $b) {
            $sa = isset($a['sort_order']) ? $a['sort_order'] : 99;
            $sb = isset($b['sort_order']) ? $b['sort_order'] : 99;
            return $sa - $sb;
        });
    }
    
    /**
     * Prüft ob ein Modul aktiv ist (Standard: aktiv)
     */
    public function isModuleActive($moduleId) {
        $statusFile = $this->config_dir . 'dashboard_modules.json';
        if (file_exists($statusFile)) {
            $status = json_decode(file_get_contents($statusFile), true);
            if (isset($status[$moduleId])) {
                return (bool)$status[$moduleId];
            }
        }
        return true; // Standard: aktiv
    }
    
    /**
     * Modul aktivieren/deaktivieren
     */
    public function setModuleActive($moduleId, $active) {
        $statusFile = $this->config_dir . 'dashboard_modules.json';
        $status = [];
        if (file_exists($statusFile)) {
            $status = json_decode(file_get_contents($statusFile), true) ?: [];
        }
        $status[$moduleId] = (bool)$active;
        file_put_contents($statusFile, json_encode($status, JSON_PRETTY_PRINT));
    }
    
    /**
     * Alle registrierten Module zurückgeben
     */
    public function getModules() {
        return $this->modules;
    }
    
    /**
     * Ein bestimmtes Modul zurückgeben
     */
    public function getModule($moduleId) {
        return isset($this->modules[$moduleId]) ? $this->modules[$moduleId] : null;
    }
    
    /**
     * Modul-Konfiguration lesen (JSON-basiert, keine DB nötig)
     */
    public static function getConfig($moduleId, $key = null, $default = null) {
        $instance = self::getInstance();
        $configFile = $instance->config_dir . 'module_' . $moduleId . '.json';
        
        if (!file_exists($configFile)) {
            return $key === null ? [] : $default;
        }
        
        $config = json_decode(file_get_contents($configFile), true) ?: [];
        
        if ($key === null) {
            return $config;
        }
        
        return isset($config[$key]) ? $config[$key] : $default;
    }
    
    /**
     * Modul-Konfiguration schreiben
     */
    public static function setConfig($moduleId, $key, $value) {
        $instance = self::getInstance();
        $configFile = $instance->config_dir . 'module_' . $moduleId . '.json';
        
        $config = [];
        if (file_exists($configFile)) {
            $config = json_decode(file_get_contents($configFile), true) ?: [];
        }
        
        $config[$key] = $value;
        
        return file_put_contents(
            $configFile, 
            json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ) !== false;
    }
    
    /**
     * Gesamte Modul-Konfiguration speichern
     */
    public static function saveConfig($moduleId, $config) {
        $instance = self::getInstance();
        $configFile = $instance->config_dir . 'module_' . $moduleId . '.json';
        
        return file_put_contents(
            $configFile, 
            json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        ) !== false;
    }
    
    /**
     * Admin-Panel eines Moduls rendern
     */
    public function renderModuleAdmin($moduleId) {
        $module = $this->getModule($moduleId);
        if (!$module || !$module['_active']) {
            return '';
        }
        
        $adminFile = $module['_path'] . (isset($module['admin_file']) ? $module['admin_file'] : 'admin.php');
        if (file_exists($adminFile)) {
            ob_start();
            include($adminFile);
            return ob_get_clean();
        }
        
        return '';
    }
    
    /**
     * Output eines Moduls für das Frontend generieren
     */
    public function renderModuleOutput($moduleId) {
        $module = $this->getModule($moduleId);
        if (!$module || !$module['_active']) {
            return '';
        }
        
        $outputFile = $module['_path'] . (isset($module['output_file']) ? $module['output_file'] : 'output.php');
        if (file_exists($outputFile)) {
            ob_start();
            include($outputFile);
            return ob_get_clean();
        }
        
        return '';
    }
    
    /**
     * Template-Pfad zurückgeben
     */
    public function getTplDir() {
        return $this->tpl_dir;
    }
    
    /**
     * Config-Pfad zurückgeben
     */
    public function getConfigDir() {
        return $this->config_dir;
    }
}
