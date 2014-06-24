<?php

/**
 * @package FMVC
 * @author Alexandru Paul <rainelf@gmail.com>
 * @version 1.0
 */
 

class FormValidation
{
	private $_oForm;
	
	public function __construct(&$oForm) 
	{
		if(!is_object($oForm) && !$oForm instanceof Form) {
			throw new \system\FMVC_Exception("Invalid object specified as form!");
		}
		
		$this->_oForm = $oForm;		
		return $this;
	}
	
	public function validateForm()
	{
		if(!isset($this->_oForm->elements) && !is_array($oForm->elements))
		{
			throw new \system\FMVC_Exception("The form has no attributes to be validated");
		}
		$aElements = $oForm->elements; 
		$bValidForm = TRUE;
		foreach($aElements as &$oElement) 
		{
			if(isset($oElement->validationRules) && is_array($oElement->validationRules))
			{
				foreach ($oElement->validationRules as $aRule)
				{
					if(isset($aRule['type']))
					{
						$sValidationMethodName = 'validate' . ucfirst($aRule['type']);
						$sDefaultErrorMessage = 'getDefaultErrorMessage'. ucfirst($aRule['type']);
						if (!method_exists($this, $sValidationMethodName)) {
							throw new \system\FMVC_Exception("The specified validation rule does not exist!");
						}
						if (!method_exists($this, $sDefaultErrorMessage)) {
							throw new \system\FMVC_Exception("The specified validation rule does not exist!");
						}
						$bValid = $this->$sValidationMethodName($oElement->value);
						if($bValid === FALSE) {
							$sErrorMessage = '';
							if (isset($aRule['errorMessage'])) {
								$sErrorMessage = $aRule['errorMessage'];
							} else {
								$sErrorMessage = $this->$sValidationMethodName($oElement->name);
							}
							if (!isset($oElement->validationErrorMessages)) {
								$oElement->validationErrorMessages = array();
							}
							$oElement->validationErrorMessages [] = $sErrorMessage;
							$bValidForm = FALSE;
						}
					}
				}
			} 
		}
		
		return $bValidForm;
	}
	
	public function validateRequired($mValue)
	{
		if (isset($mValue) && is_string($mValue)) {
			return (isset($sValue) && trim($sValue) != '') ? TRUE : FALSE;	
		} elseif (is_array($mValue)) {
			return (count($mValue)) ? TRUE : FALSE;
		}
		
	}
	
	public function validateEmail($sValue)
	{
		return (filter_var($sValue, FILTER_VALIDATE_EMAIL)) ? TRUE : FALSE;
	}
	
	public function validateUrl($sValue)
	{
		return (filter_var($sValue, FILTER_VALIDATE_URL)) ? TRUE : FALSE;
	}
	
	public function validateBoolean($sValue)
	{
		return (filter_var($sValue, FILTER_VALIDATE_BOOLEAN)) ? TRUE : FALSE;
	}
	
	public function validateFloat($sValue)
	{
		return (filter_var($sValue, FILTER_VALIDATE_FLOAT)) ? TRUE : FALSE;
	}
	
	public function validateInt($sValue)
	{
		return (filter_var($sValue, FILTER_VALIDATE_INT)) ? TRUE : FALSE;
	}
	
	public function validateRegex($sValue, $sPattern)
	{
		return (filter_var($sValue, FILTER_VALIDATE_REGEXP, $sPattern)) ? TRUE : FALSE;
	}
	
	/**
	 * @var $sName string
	 */
	public function getDefaultErrorMessageRequired($sName)
	{
		return 'The field "' . $sName . '" is required.';
	}
	
	public function getDefaultErrorMessageeEmail($sName)
	{
		return 'The field "' . $sName . '" must be a valid email.';
	}
	
	public function getDefaultErrorMessageUrl($sName)
	{
		return 'The field "' . $sName . '" must be a valid url.';
	}
	
	public function getDefaultErrorMessageBoolean($sName)
	{
		return 'The field "' . $sName . '" must be boolean.';
	}
	
	public function getDefaultErrorMessageFloat($sName)
	{
		return 'The field "' . $sName . '" must be float.';
	}
	
	public function getDefaultErrorMessageInt($sName)
	{
		return 'The field "' . $sName . '" must be an integer.';
	}
	
	public function getDefaultErrorMessageRegex($sName)
	{
		return 'The field "' . $sName . '" does not match the specified regex.';
	}
	
}
