<?php
class FormInput
{
    /** @var  string - the name of the input */
    private $sName;
    /** @var  string $sType - type of the input */
    private $sType = 'text';
    /** @var  mixed $mValue - Value(s) of the input/seelct - could be an array for multiple select */
    private $mValue;
    /** @var  string $sLabel - the label of the input */
    private $sLabel;

    /** @var  array $aExtra - array of key/val pairs that would be written on the input/select tag */
    private $aExtra;

    /** @var  array $aOptions - array with options for the drop down (key/val => val=>label) */
    private $aOptions;

    /** @var array $_validInputTypes - valid types for inputs */
    private $_validInputTypes = array ('text', 'hidden', 'radio', 'checkbox', 'submit', 'image', 'select');

    const SELECT_INPUT_TYPE = 'select';

    /** @var  string $html - used for displaying the html for an input */
    private $sHtml = '';

    public function __construct() {}

    public function setName($sName)
    {
        $this->sName = $sName;
        return $this;
    }

    public function getName()
    {
        return $this->sName;
    }

    public function setType($sType)
    {
        if (in_array($sType, $this->_validInputTypes)) {
            $this->sType = $sType;
            return $this;
        }
        throw new \Exception('Invalid input type specified!');
    }

    public function getType()
    {
        return $this->sType;
    }

    public function setLabel($sLabel)
    {
        $this->sLabel = $sLabel;
        return $this;
    }

    public function getLabel()
    {
        return $this->sLabel;
    }

    public function setValue($mValue)
    {
        $this->mValue = $mValue;
        return $this;
    }

    public function getValue()
    {
        return $this->mValue;
    }

    public function setExtra($aExtra)
    {
        if (!is_array($aExtra)) {
            throw new \Exception('Invalid parameter specified as extra. Extra parameter must be an array.');
        }
        $this->aExtra = $aExtra;
        return $this;
    }

    public function getExtra()
    {
        return $this->aExtra;
    }

    public function setOptions($aOptions)
    {
        if (!is_array($aOptions)) {
            throw new \Exception('Invalid parameter specified as options. Options parameter must be an array.');
        }
        $this->aOptions = $aOptions;
        return $this;
    }

    public function getOptions()
    {
        return $this->aOptions;
    }

    /**
     * Prints the html code responsible for rendering an input. If the input has the type select, then another method
     * is called (buildSelectInput)
     */
    public function buildInput()
    {
        if($this->getType() == self::SELECT_INPUT_TYPE) {
            $this->buildSelectInput();
            return;
        }


        $this->sHtml = '<input type="' . $this->getType(). '" name="'.$this->getName().'"';
        $this->sHtml .= ' value="'.$this->getValue().'"';
        if(is_array($this->aExtra)) {
            foreach($this->aExtra as $sKey => $sVal) {
                if ($sVal != '' && $sKey != '') {
                    $this->sHtml .= ' ' . $sKey .= '="' . $sVal . '"';
                } else {
                    $this->sHtml .= ' ' . $sKey . ' ';
                }
            }
        }

        $this->sHtml .= ' />';
        echo $this->sHtml;
    }

    /**
     * Prints the html code responsible for rendering a select input.
     *
     * @return string
     */
    public function buildSelectInput()
    {

        $this->sHtml = '<select name="'.$this->getName().'"';

        if(isset($this->aExtra) && is_array($this->aExtra)) {
            foreach($this->aExtra as $sKey => $sVal) {
                if ($sVal != '' && $sKey != '') {
                    $this->sHtml .= ' ' . $sKey .= '="' . $sVal . '"';
                } else {
                    $this->sHtml .= ' ' . $sKey . ' ';
                }
            }
        }
        $this->sHtml .= '>';

        if (isset($this->aOptions) && is_array($this->aOptions)) {
            foreach ($this->aOptions as $sKey => $sVal) {
                $this->sHtml .= '<option value="'.$sKey.'"';

                    if (is_array($this->mValue) && in_array($sVal, $this->mValue)) {
                        $this->sHtml .= ' selected="selected" ';
                    } elseif(is_string($this->mValue) && $this->mValue == $sVal) {
                        $this->sHtml .= ' selected="selected" ';
                    }

                $this->sHtml .= '>' .$sVal . '</option>';
            }
        }

        $this->sHtml .= '</select>';
        echo $this->sHtml;
    }
}
