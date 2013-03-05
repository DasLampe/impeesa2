<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2012 DasLampe <daslampe@lano-crew.org> |
// | Encoding:  UTF-8 |
// +----------------------------------------------------------------------+
class impeesaForm {
	private $tpl;
	private $error_msg;
	
	public function __construct()
	{
		$this->tpl			= impeesaTemplate::getInstance();
		$this->error_msg	= array();
	}
	
	public function GetForm(array $fields, $action, $method="post", $form_class="form")
	{
		$this->tpl->vars("form_class",	$form_class);
		$this->tpl->vars("fields",		$this->GetFormFields($fields));
		$this->tpl->vars("action",		$action);
		$this->tpl->vars("method",		$method);
		$this->tpl->vars("error_msg",	$this->GetErrorMsg());
		return $this->tpl->load("form");
	}
	
	public function SetErrorMsg($msg)
	{
		$this->error_msg[]	= $msg;
	}
	
	public function fillData(array &$data, $node, array $new_data) {
		if(!is_array($node)) {
			$node	= array($node);
		}
		
		foreach($data as &$array) {
			if(is_array($array) && isset($array[0]) && (
					(!is_array($node[0]) && $array[0] == $node[0]) ||
					(is_array($node[0]) && $array[0] == $node[0][0] && $array[1] == $node[0][1])
				)) {
				if(count($node) > 1)
				{
					array_shift($node); //Remove first node
					if($this->fillData($array, $node, $new_data) == true)
					{
						return true;
					}
				}
				else {
					//Find next array
					for($x=1;$x<count($array);$x++) {
						if(is_array($array[$x])) {
							$array[$x][]	= $new_data;
							return true;
						}
					}
					return false;
				}
			}
			elseif(is_array($array)) {
				if($this->fillData($array, $node, $new_data) == true) { //Exit if data insert
					return true;
				}
			}
		}
		return false;
	}
	
	public function Validation(array $fields, array $data)
	{
		foreach($fields as $field)
		{
			if($field[0] == "fieldset")
			{
				$this->Validation($field[2], $data);
				continue;
			}
			
			//check if field required, have value
			if(isset($field[4]) && $field[4] == True && empty($data[$field[2]]))
			{
				$this->error_msg[]	= 'Bitte Feld "'.$field[1].'" ausfüllen!';
			}
			
			if(!empty($data[$field[2]]))
			{ //Only check if field isn't empty (needed to fix Bug #19 (first comment))
				switch($field[0])
				{
					case "text":
						break;
					case "password":
						if(strlen($data[$field[2]]) < 6)
						{
							$this->error_msg[]	= 'Passwort aus Feld "'.$field[1].'" ist zu kurz. Mindeslänge 6 Zeichen!';
							$return = False;
						}
						if(preg_match("/^([a-z]{6,}|[0-9]{6,}|[^\w]{6,})$/i", $data[$field[2]]))
						{
							$this->error_msg[]	= 'Passwort aus Feld "'.$field[1].'" ist zu schwach. Bitte mindestens 2 Zeichengruppen verwenden (Sonderzeichen, Buchstaben, Zahlen)';
						}
						break;
					case "email":
						if(filter_var($data[$field[2]], FILTER_VALIDATE_EMAIL) == false)
						{
							$this->error_msg[]	= 'Bitte eine richtige Email-Adresse, im Feld "'.$field[1].'" angeben!';
							$return = False;
						}
						break;
					case "year":
						if(!is_numeric($data[$field[2]]) || strlen($data[$field[2]]) != 4) {
							$this->error_msg[]	= 'Inhalt des Feldes "'.$field[1].'" muss aus 4 Ziffern bestehen.';
						}
						break;
					case "number":
						if(!is_numeric($data[$field[2]])) {
							$this->error_msg[]	= 'Es dürfen nur Zahlen im Feld "'.$field[1].'" verwendet werden.';
						}
						break;
					case "textarea":
						break;
				}
			}
		}
		if(!empty($this->error_msg))
		{
			return false;
		}
		return true;
	}
	
	private function GetErrorMsg()
	{
		$return		= "";
		foreach($this->error_msg as $msg)
		{
			$this->tpl->vars("message", 	$msg);
			$return	.= $this->tpl->load("_form_error_msg");
		}
		$this->tpl->vars("error_message",		$return);
		return $this->tpl->load("_form_error");
	}
	
	private function GetFormFields(array $fields)
	{
		$form_fields	= "";
		foreach($fields as $field)
		{
			$form_fields	.= $this->GetFormField($field);
		}
		return $form_fields;
	}
	
	private function GetFormField(array $field)
	{
		$field[3] = (isset($field[3]) ? $field[3] : "");
		$field[4] = (isset($field[4]) ? $field[4] : "");
			
		$this->tpl->vars("label",		$field[1]);
		$this->tpl->vars("name",		$field[2]);
		$this->tpl->vars("value",		$field[3]);
		$this->tpl->vars("required",	$this->IsRequiredField($field[4]));
			
		switch(strtolower($field[0]))
		{
			case 'fieldset':
				$this->tpl->vars("fields",	$this->GetFormFields($field[2]));
				
				//redefine label
				$this->tpl->vars("label",		$field[1]);
				return $this->tpl->load("_form_fieldset");
				break;
			case 'select':
				$this->tpl->vars("fields", $this->GetFormFields($field[3]));
				
				//redefine some vars
				$this->tpl->vars("label",		$field[1]);
				$this->tpl->vars("name",		$field[2]);
				return $this->tpl->load("_form_select");
				break;
			case 'option':
				//redefine some vars
				$this->tpl->vars("value",		$field[2]);
				$this->tpl->vars("disabled",	$this->IsDisabledField($field[3]));
				$this->tpl->vars("selected",	$this->IsSelectedField($field[4]));
				
				return $this->tpl->load("_form_option");
				break;
			case 'checkbox':
				$this->tpl->vars("checked",		$this->IsCheckedField($field[5]));
				return $this->tpl->load("_form_checkbox");
				break;
			case 'text':
				return $this->tpl->load("_form_text");
				break;
			case 'email':
				return $this->tpl->load("_form_email");
				break;
			case 'year':
				$this->tpl->vars("max_number",	"4");
				return $this->tpl->load("_form_number");
				break;
			case 'time':
				return $this->tpl->load("_form_time");
				break;
			case 'static':
				return $this->tpl->load("_form_static");
				break;
			case 'password':
				return $this->tpl->load("_form_pass");
				break;
			case 'hidden':
				return $this->tpl->load("_form_hidden");
				break;
			case 'textarea':
				return $this->tpl->load("_form_textarea");
				break;
			case 'submit':
				return $this->tpl->load("_form_submit");
				break;
		}
	}
	
	private function IsRequiredField($required)
	{
		if($required == false || empty($required))
		{
			return '';
		}
		else
		{
			return 'required';
		}
	}
	
	private function IsDisabledField($disabled)
	{
		if($disabled == true)
		{
			return 'disabled="disabled"';
		}
		return '';
	}
	
	private function IsSelectedField($selected)
	{
		if($selected == true)
		{
			return 'selected="selected"';
		}	
		return '';
	}
	
	private function IsCheckedField($checked)
	{
		if($checked == true)
		{
			return 'checked="checked"';
		}
		return '';
	}
}
