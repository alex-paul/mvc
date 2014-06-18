<?php

class Controller
{
    /** @var  array $aParameters */
    private $aParameters;

    /**
     * Sets the parameters for the skeleton controller class
     *
     * @param array $aParameters
     */
    public function setParameters($aParameters)
    {

        $this->aParameters = $aParameters;
    }

    /**
     * Returns the parameters array.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->aParameters;
    }

    /**
     * Method that is executed just before the method from the request is executed. Useful for initialization purposes.
     */
    public function init() {}

    /**
     * Passes the parameters set  in the controller action to the view file (parameters are put in an array, as the
     * 2nd parameter of the renderView method.
     *
     * @param string $sViewFilePath - view file path concatenated to the name of the file, but without extension (phtml)
     * @param array $aParameters - paramater array that is being passed to the view file
     * @throws Exception
     */
    private function passParametersAndRequireView($sViewFilePath, $aParameters)
    {
        if (!is_array($aParameters))
        {
            throw new \Exception('The parameters provided should be put into an array.');
        } elseif ( count ($aParameters) >= 1 ) {
            foreach ($aParameters as $key => $val)
            {
                $$key = $val;
            }
        }

        ob_start();                      // start capturing output
        get_defined_vars();
        ob_end_clean();
        require_once($sViewFilePath);
    }

    /**
     * Method that invokes the view file.
     *
     * @param string $sViewName
     * @param array $aParameters
     * @throws Exception
     */
    public function renderView($sViewName, $aParameters)
    {
        if (!isset($sViewName)) {
            throw new \Exception('View name not specified!');
        }

        $aViewsPath = explode('/', __DIR__);
        array_pop($aViewsPath);
        $sViewsPath  = join('/', $aViewsPath) . '/application/views/';
        $sViewFilePath = $sViewsPath . $sViewName . '.phtml';
        if (file_exists($sViewFilePath)) {
            $this->passParametersAndRequireView($sViewFilePath, $aParameters);


        } else {
            throw new \Exception('The specified view does not exist!');
        }

    }

    /**
     * @param string $sParameterName
     * @return null|string
     */
    public function getParameter($sParameterName)
    {
        if(is_array($this->aParameters) && array_key_exists($sParameterName,$this->aParameters[$sParameterName])) {
            return $this->aParameters[$sParameterName];
        }
        return null;
    }
}