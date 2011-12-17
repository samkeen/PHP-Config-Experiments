<?php
/**
 * Original Author: sam
 * Date: 12/17/11
 * Time: 9:04 AM
 */

namespace clear;

/**
 * 
 */
class Config
{
	private $key_vals = array();
	
	function __construct($allowed_keys)
	{
		$this->key_vals = array_fill_keys($allowed_keys, null);
	}
	
	function __invoke($key, $val=null)
	{
		if( ! array_key_exists($key, $this->key_vals))
		{
			throw new \InvalidArgumentException("key [{$key}] not known");
		}
		$result = true;                  
		if(func_num_args()==2) // Setter 
		{                                
			$this->{$key} = $val;           
		}                                
		else // Getter                   
		{                                
			$result = $this->{$key};        
		}                                
		return $result;
	}
}
