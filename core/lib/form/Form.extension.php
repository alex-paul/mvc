<?php 

/**
 * @package Form
 * @author Alexandru Paul <rainelf@gmail.com>
 * @version 1.0
 */
 
require_once('Form.validation.php');
require_once('Form.input.php');

class Form 
{
    private $_sName;
	private $_oForm;
	private $_validMethods = array ('post', 'get', 'put');
	public $sHtml;
	
	public function __construct($sMethod='post', $sAction = '', $aExtra = array())
	{
		if (!is_string($sMethod) || !in_array(strtolower($sMethod), $this->_validMethods) ) {
			throw new \Exception("Invalid method specified");
		} else {
			$this->_oForm = new \stdClass();
			$this->_oForm->method = strtolower($sMethod);
			$this->_oForm->action = $sAction;
			$this->_oForm->extra = $aExtra;
			$this->_oForm->elements = array();
		}
        $this->declareInputs();
	} 
	
	/**
	 * Method that writes the open tag for a form, including mehtod, action and several other parameters
	 * contained in the array $aExtra specified in the constructor.
	 */
	public function openForm() 
	{
		$this->sHtml = '<form';
		if (isset($this->_oForm->method)) {
			$this->sHtml .= ' method="' . $this->_oForm->method . '"';
		}
		if (isset($this->_oForm->action)) {
			$this->sHtml .= ' action="' . $this->_oForm->action . '"';
		}
		if (isset($this->_oForm->extra) && is_array($this->_oForm->extra)) {
			foreach ($this->_oForm->extra as $sAttribute => $sValue) {
				$this->sHtml .= ' ' . $sAttribute = '="' . $sValue . '"';
			}
		}
		$this->sHtml .= ">";	
		$this->printCurrentHtml();	
	}
	
	/**
	 * Method that writes the tag for closing the form.
	 */
	public function closeForm ()
	{
		$this->sHtml = '</form>';
		$this->printCurrentHtml();
		
	}

    /**
     * Adds a new input to the form. The elements(inputs) are kept in $this->_oForm->elements.
     *
     * @param $aOptions
     * @return $this
     * @throws Exception
     */
    public function addInput ($aOptions)
	{
        if (!isset($aOptions) || !is_array($aOptions)) {
            throw new \Exception('Invalid options array for the input.');
        }

        $oInput = new FormInput();

        if (isset($aOptions['name'])) {
            $oInput->setName($aOptions['name']);
        } else {
            throw new \Exception('The input you want to add must have a name!');
        }

		if (isset($aOptions['type'])) {
            $oInput->setType($aOptions['type']);
        }

        if (isset($aOptions['value'])) {
            $oInput->setValue($aOptions['value']);
        }

        if (isset($aOptions['label'])) {
            $oInput->setLabel($aOptions['label']);
        }

        if (isset($aOptions['extra'])) {
            $oInput->setExtra($aOptions['extra']);
        }

        if (isset($aOptions['options'])) {
            $oInput->setOptions($aOptions['options']);
        }

		array_push($this->_oForm->elements, $oInput);

        return $this;
	}

    /**
     * Retrieves the input from the $this->_oForm->elements array, based on the $sElementName. If the input is not found
     * the method throws an error.
     *
     * @param string $sElementName
     * @return FormInput
     * @throws Exception
     */
    public function getInput($sElementName)
    {
        if(!is_string($sElementName)) {
            throw new \Exception('Invalid parameter given as input name.');
        }

        if(!isset($this->_oForm->elements) && !is_array($this->_oForm->elements)) {
            throw new \Exception('Invalid form elements container.');
        }

        foreach ($this->_oForm->elements as $oInput) {
            if ($oInput instanceof FormInput && $oInput->getName() == $sElementName) {
                return $oInput;
            }
        }

        throw new \Exception('The requested input was not found.');
    }

	
	/**
	 * Method used to print various parts of the form. Each element that is to be printed has the
	 * html in $this->sHtml property.
	 */
	public function printCurrentHtml() {
		echo $this->sHtml;	
	}
	
	public function setValidation() 
	{
		$oFormValidation = new FormValidation($this);
	}

    /**
     * Method that will be overriden. Here will be added all the inputs. The method is automatically called in the form's
     * constructor.
     * @param $sFormName string|null
     */
    public function declareInputs($sFormName = null)
    {
        if (null != $sFormName) {
            $this->_sName = $sFormName;
        }
    }
	
}
