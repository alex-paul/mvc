<?php

final class Application
{
    private static $oInstance = null;
    private $sRequest;
    private $aRoute = array();
    private function __construct () {}
    private $sControllerFilePath;

    public static function getInstance()
    {
        if (null === static::$oInstance) {
            self::$oInstance = new Application();
        }

        return self::$oInstance;
    }

    private function __clone() {}
    public function setRequest($sRequest = '')
    {
        if($sRequest == '') {
            $this->sRequest = $_SERVER['REQUEST_URI'];
        } else {
            $this->sRequest = $sRequest;
        }
        return $this;
    }

    private function prepareRequest()
    {
        $aRequest = explode('/', $this->sRequest);
        foreach ($aRequest as $sKey => &$sVal) {
            if (trim($sVal) == '') {
                unset($aRequest[$sKey]);
            }
        }
        $aRequest = array_values($aRequest);
        /**
         * remove index.php from the request
         */
        if (isset($aRequest[0]) && $aRequest[0] == 'index.php') {
            array_shift($aRequest);
        }
        return $aRequest;
    }

    /**
     * Translates the request url into controller, action and parameters.
     *
     */
    private function dispatch()
    {

        $aRequest = $this->prepareRequest();

        if (isset($aRequest[0]) && $aRequest[0] != '') {
            $sController = $aRequest[0] . 'Controller';
        } else {
            $sController = 'Index' . 'Controller';
        }

        if (isset($aRequest[1]) && $aRequest[1] != '') {
            $sAction = $aRequest[1] . 'Action';
        } else {
            $sAction = 'Index' . 'Action';
        }
        $aParameters = array();
        if (count($aRequest) >= 2) {
            $sKey = '';
            $sVal = '';
            for ($i = 2; $i <= count($aRequest); $i++) {
                if ($i % 2 == 0 && isset($aRequest[$i])) {
                    $sKey = $aRequest[$i];
                } elseif(isset($aRequest[$i])) {
                    $sVal = $aRequest[$i];
                }
                if ($sKey != '' & $sVal != '') {
                    $aParameters[$sKey] = $sVal;
                }
            }
        }

        $aRoute['controller'] = ucfirst($sController);
        $aRoute['action']     = $sAction;
        $aRoute['parameters'] = $aParameters;
        $this->aRoute = $aRoute;

        $this->checkRoute();
    }

    /**
     * Checks to see if controller file exists for the current request
     *
     * @return bool
     */
    private function checkControllerFileExists()
    {
        $aControllerPath = explode(DIRECTORY_SEPARATOR, __DIR__);
		array_pop($aControllerPath);
        $sControllerPath  = join(DIRECTORY_SEPARATOR, $aControllerPath) . DIRECTORY_SEPARATOR . 'application'.DIRECTORY_SEPARATOR.'controllers'. DIRECTORY_SEPARATOR;	
        $sControllerFilePath = $sControllerPath . $this->aRoute['controller'] . '.php';
        $this->sControllerFilePath = $sControllerFilePath;		
        return file_exists($sControllerFilePath);
    }

    /**
     * Requires the controller file if it exists.
     */
    private function requireController()
    {
        require_once($this->sControllerFilePath);
    }

    /**
     * Checks if the corresponding class is defined in the requested controller.
     *
     * @return bool
     */
    private function checkControllerClassExists()
    {
        return class_exists($this->aRoute['controller']);
    }

    /**
     * Checks is the corresponding method exists in the requested controller
     *
     * @return bool
     */
    private function checkMethodExists()
    {
        return method_exists($this->aRoute['controller'], $this->aRoute['action']);
    }

    private function checkRoute()
    {
        if (!$this->checkControllerFileExists()) {
            throw new \Exception('File for the '. $this->aRoute['controller'] .' wasn\'t found');
        }

        /**
         * We must require te controller at this level in order to check for the controller class and the requested
         * action
         */
        $this->requireController();

        if (!$this->checkControllerClassExists()) {
            throw new \Exception('Class '. $this->aRoute['controller'] .' wasn\'t found');
        }

        if (!$this->checkMethodExists()) {
            throw new \Exception('Method '. $this->aRoute['controller'] .' wasn\'t found for the controller ' . $this->aRoute['controller']);
        }

    }

    /**
     * Entry point of the application. The the dispatch is called here, and also the method of the controller is called.
     *
     */
    public function run()
    {
        /**
         * We dispatch the request into controller, method and parameters.
         */
        $this->dispatch();

        /**
         * We pass the computed parameters to the controller
         */
        $oController = new $this->aRoute['controller']();
        $oController->setParameters($this->aRoute['parameters']);

        /**
         * We want to allow the init method to be executed just before the method from the request.
         * The default is empty. If needed override in the class that extends the Controller class.
         */
        $oController->init();
        /**
         * We can let the method from the controller to execute now.
         */
        $sActionName = $this->aRoute['action'];
        $oController->$sActionName();
    }

    public static function getServerCorePath()
    {
        $aPathElements = explode(DIRECTORY_SEPARATOR, __DIR__);
        array_pop($aPathElements);
        $sPath  = join('/', $aPathElements) ;
        return $sPath;
    }
}

