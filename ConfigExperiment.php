<?php
/**
 * Original Author: sam
 * Date: 12/17/11
 * Time: 9:04 AM
 */

namespace clear;

/**
 * This is a self enforcing Config
 * 
 */
class Config
{
	private $key_vals = array();
	
	function __construct(array $known_key_vals = null)
	{
		$this->key_vals = (array)$known_key_vals;
	}
	
	function known_keys(array $known_keys)
	{
		$this->key_vals = array_fill_keys($known_keys, null);
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
			$result = $this->key_vals[$key];        
		}                                
		return $result;
	}
	
	function __get($key)
	{
		if( ! array_key_exists($key, $this->key_vals))
		{
			throw new \InvalidArgumentException("key [{$key}] not known");
		}
		return $this;
	}
	
	function __call($name, $arguments)
	{
		switch($name)
		{
			case"can_be":
				
				break;
		}
	}
}

//$config = new Config(array('name'));
// alt0 all in one
$config = new Config(array(
	'cache' => array(
		'default' 	=> __DIR__."/cache_write/twig_cache",
		'validate'	=> ':regex%[\w/]%'
	),
	'auto_reload' 		=> true,
	'debug'				=> true,
	'strict_variables'	=> true,
	'autoescape'		=> true,
));
// alt1 seperate
$config = new Config(array(
	'keys' => array(
		'cache',
		'auto_reload',
		'debug',
		'strict_variables',
		'autoescape'
	),
	'defaults' => array(
		'cache' => __DIR__."/cache_write/twig_cache",
		'debug'	=> true,
	),
	'rules' => array(
		'cache' => ':regex%[\w/]%',
		'auto_reload' => ':boolean',
)));
// alt3 yaml
$config = new Config('
	cache: 
		default  : __DIR__./cache_write/twig_cache
		validate : regex%[\w/]%
	auto_reload      : true
	debug            : true
	strict_variables : true
	autoescape       : true'
);


$config->allowed(array(
	'cache' => ':regex%[\w/]%',
	'auto_reload' => ':boolean',
));


echo $config('cache');

$simple_config = new Config();
$simple_config->known_keys(array('name'));

//$config->name->can_be(array('bob', 'ted'));



$simple_config('name', 'bob');
echo $config('name');
echo $config->name;

/*
 * this is were this syntax excels over 
 * echo $config->user['name'] 
 * &&
 * $config->user['name'] = 'bob';
 */
echo $config('user.name');
$config('user.name', 'bob');


//$config('user', array('name' => 'bob'));
//
//echo $config('name');
//echo $config('user.name');
//
//echo $config('x');
/*
Fatal error: Uncaught exception 'InvalidArgumentException' with message 'key [x] not known' in /x/clear/lib/Config.php on line 27

InvalidArgumentException: key [x] not known in /x/clear/lib/Config.php on line 27
 */
