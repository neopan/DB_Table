<?php

require_once 'PEAR.php';
require_once 'DB.php';
require_once 'DB/Table.php';
require_once 'Var_Dump.php';

error_reporting(E_ALL);

class example extends DB_Table {
	
	var $col = array(
		'xvarchar' => array(
			'type'    => 'varchar',
			'size'    => 128,
			'require' => false,
			'qf_type' => 'autocomplete',
			'qf_vals' => array(
				'this',
				'that',
				'other',
				'another'
			)
		),
		'xbool' => array(
			'type'    => 'boolean'
		),
		'xchar' => array(
			'type'    => 'char',
			'size'    => 10,
			'require' => true
		),
		'xclob' => array(
			'type'    => 'clob',
			'require' => false
		),
		'xsmint' => array(
			'type'    => 'smallint',
			'require' => false,
			'qf_client' => true
		),
		'xint' => array(
			'type'    => 'integer',
			'require' => true
		),
		'xbigint' => array(
			'type'    => 'bigint',
			'require' => false
		),
		'xdecimal' => array(
			'type'    => 'decimal',
			'size'    => 5,
			'scope'   => 2,
			'require' => false
		),
		'xsingle' => array(
			'type'    => 'single',
			'require' => false
		),
		'xdouble' => array(
			'type'    => 'double',
			'require' => false
		),
		'xdate' => array(
			'type'    => 'date',
			'default' => "'0001-01-01'",
			'require' => false
		),
		'xtime' => array(
			'type'    => 'time',
			'default' => "'00:00:00'",
			'require' => false
		),
		'xtimestamp' => array(
			'type'    => 'timestamp'
		)
	);
	
	var $idx = array(
		'id' => array(
			'type' => 'unique',
			'cols' => array('xint')
		),
		'multi' => array(
			'type' => 'normal',
			'cols' => array('xdate', 'xtime', 'xchar')
		)
	);
	
	var $sql = array(
		'list' => array(
			'select' => '*',
			'get'    => 'row'
		)
	);
}

$opts = parse_ini_file('setup.ini', true);

$db = DB::Connect($opts['dsn']);

$example =& new example(
	$db,
	$opts['example']['table'],
	$opts['example']['create']
);

if ($example->error) {
	Var_Dump::display($example->error);
	die();
}

if ($opts['example']['display']) {
	Var_Dump::display($example);
}

if ($opts['example']['fetch']) {
	$example->fetchmode = DB_FETCHMODE_ASSOC;
	$result = $example->select('list');
	Var_Dump::display($result);
}

$form =& $example->getForm(null, 'mydata', null, false);
$form->addElement('submit', 'op', 'Submit');

echo "<html><head><title>bogotest</title></head><body>\n";

if ($form->validate()) {

	$values = $form->exportValues();
	Var_Dump::display($values['mydata']);
	
	$result = $example->insert($values['mydata']);
	Var_Dump::display($result);
	
}

$form->display();

echo "\n<hr />\n";
$list = $example->select('list');
Var_Dump($list);

echo "\n</body></html>";

?>