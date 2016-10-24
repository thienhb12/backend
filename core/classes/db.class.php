<?php
/**
 * class 
 * @version 1.0
 * 
 * @author 
 * 
 * @date 01 Oct 2008
 * @desc Features:
 * - provide method oriented tables
 * 		+ insert
 * 		+ update with PK
 * 		+ update with FK
 * 		+ delete with PK
 * 		+ delete with FK
 * 		+ empty table
 * - provide element for Quick Form
 * - provide element for Datagrid
 * 
 * @desc Purpose
 * - Cung cap cac ham xu ly chung doi voi tat ca cac Module
 * - Tao chuan xu ly chung cho cac Module
 *
 */
class Anpro_Db_Base
{
	private $table;
	private $showColumns;
	
	private $primaryKey;
	private $hasPrimaryKey = false;
	
	private $foreignKey = array();
	private $hasForeignKey = false;
	
	private $indexKey = array();
	private $uniqueKey = array();
	
	private $fields = array();
	
	private $startVal = '(';
	private $endVal = ')';
	
	private $pearDb;
	
	public function __construct(&$pearDb)
	{
		$this->pearDb = $pearDb;
		$this->pearDb->setFetchMode(DB_FETCHMODE_ASSOC);
	}
	/**
	 * setTable
	 *
	 * @param string $table
	 */
	public function setTable($table)
	{
		$this->table = $table;
		$this->hasPrimaryKey = false;
		$this->hasForeignKey = false;
		$this->analyzeTable();
	}
	
	/**
	 * getTable	
	 */
	public function getTable(){
		return $this -> table;
	}
	
	public function getFields ()
	{
		return $this->fields;
	}
	/**
	 * setTable
	 *
	 * @param string $table
	 */
	public function setPrimaryKey($pk)
	{
		$this->primaryKey = $pk;
	
	}
	/**
	 * analyzeTable
	 * 
	 * @desc
	 * Field Attributes:
	 * Field 	Type 	Collation 	Null 	Key 	Default 	Extra 	Privileges 	Comment 
	 * 
	 * Input Tag Attributes for HTML Form:
	 * InputType
	 * Title
	 * Value
	 * maxlength
	 * ...Open atributes
	 *
	 */
	public function analyzeTable()
	{
		$this->showColumns = $this->pearDb->query("SHOW FULL COLUMNS FROM {$this->table}");
		if(PEAR::isError($this->showColumns))
		{
			die($this->showColumns->getMessage());
		}
		else 
		{
			
			while($row = &$this->showColumns->fetchRow())
			{
				$this->fields[$row['Field']] = $row;
				
				$arrayInputType = $this->setInputType($row['Type']);
				$this->fields[$row['Field']] = array_merge($this->fields[$row['Field']], $arrayInputType);
				$this->fields[$row['Field']]['Attributes']['id'] = $row['Field'];
				$this->fields[$row['Field']]['Attributes']['title'] = $row['Comment'];
				
				switch ($row['Key'])
				{
					case 'MUL': 
						$this->indexKey[] = $row['Field'];
						break;
					case 'UNI';
						$this->uniqueKey[] = $row['Field'];
						break;
					case 'PRI';
						if ($row['Extra'] == 'auto_increment')
						{
							$this->primaryKey = $row['Field'];
							$this->hasPrimaryKey = true;
						}
						else 
						{
							$this->foreignKey[] = $row['Field'];
							$this->hasForeignKey = true;
						}
						break;
				}
				
			}
			
			//$this->test($this->fields);
			
		}
	}
	/**
	 * isPrimaryKey
	 *
	 * @param string $field
	 * @return boolean
	 * @desc check input field is primary key
	 */
	public function isPrimaryKey($field)
	{
		return ($this->hasPrimaryKey && $this->primaryKey == $field) ? true : false;
	}

	/**
	 * isForeignKey
	 *
	 * @param string $field
	 * @return boolean
	 * @desc check input field is foreign key
	 */
	public function isForeignKey($field)
	{
		return ($this->hasForeignKey && $this->inArray($field,$this->foreignKey)) ? true : false;
	}
	/**
	 * isUniqueKey
	 *
	 * @param string $field
	 * @return boolean
	 * @desc check input field is unique key
	 */
	public function isUniqueKey($field)
	{
		return $this->inArray($field, $this->uniqueKey);
	}
	/**
	 * isIndexKey
	 *
	 * @param string $field
	 * @return boolean
	 * @desc check input field is index key
	 */
	public function isIndexKey($field)
	{
		return $this->inArray($field, $this->indexKey);
	}
	/**
	 * isField
	 *
	 * @param string $field
	 * @return boolean
	 * @desc check input field is field of table
	 */
	public function isField($field)
	{
		return array_key_exists($field, $this->fields) ? true : false;
	}
	
	public function getRow($id)
	{
		$sSql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = '{$id}'";
		$row = $this->pearDb->getRow($sSql);
		if (PEAR::isError($row))
		{
			return false;
		}
		else 
		{
			return $row;
		}
	}

	public function getById($id)
	{
		$sSql = "SELECT * FROM {$this->table} WHERE id = '{$id}' AND status = 1";
		$row = $this->pearDb->getRow($sSql);
		if (PEAR::isError($row))
		{
			return false;
		}
		else 
		{
			return $row;
		}
	}

	public function getAll($where = '')
	{
		$sSql = "SELECT * FROM {$this->table}";
		if($where != '') {
			$sSql .= " WHERE ".$where;
		}

		$list = $this->pearDb->getAll($sSql);
		if (PEAR::isError($row))
		{
			return false;
		}
		else 
		{
			return $list;
		}
	}

	function getAssoc($fieds = '', $where = null) {
		$sql = "SELECT ";
		if (is_array($fieds)) {
			$sql .= implode(", ", $fieds);
		} else if ($fieds != '') {
			$sql .= " $fieds ";
		} else if ($fieds == '' or empty($fieds)) {
			$sql .= " * ";
		}
		$sql .= " FROM ". $table;
		if ($where !== null) {
			$sql .= " WHERE ". $where;
		}
		$res = $this -> pearDb -> getAssoc($sql, false);
		if(PEAR :: isError($res)){
            return false;
        }else{
        	return $res;
        }
	}


	function getOne($fieds = '', $where = null, $table = null) {
		if($table == null) {
			$table = $this -> table;
		}
		$sql = "SELECT ";
		if (is_array($fieds)) {
			$sql .= implode(", ", $fieds);
		} else if ($fieds != '') {
			$sql .= " $fieds ";
		} else if ($fieds == '' or empty($fieds)) {
			$sql .= " * ";
		}
		$sql .= " FROM ". $table;
		if ($where != null) {
			$sql .= " WHERE " . $where;
		}
		$res = $this -> pearDb -> getOne($sql);
		if(PEAR :: isError($res)){
            return false;
        }else{
        	return $res;
        }
	}

	/**
	 * insert
	 *
	 * @param array $aData - Array Insert Data
	 * @return mixed - false if error && $insertId if sucess
	 */
	public function insert($aData)
	{
		$sSql = "INSERT INTO {$this->table} SET ";
		
		$sInsert = "";
		$doInsert = false;
		foreach ($aData as $field => $value)
		{
			if($this->isField($field))
			{
				$sInsert .= "`{$field}`='{$this->prepareInput($value)}',";
				$doInsert = true;
			}
		}
		if($doInsert)
		{
			//remove last comma
			$sInsert = substr($sInsert,0,strlen($sInsert)-1);
			
			$sSql .= $sInsert;
			$result = $this->pearDb->query($sSql);
			if (PEAR::isError($result))
			{
				return false;
			}
			else 
			{
				$insertId = $this->pearDb->getOne( "SELECT last_insert_id()" );
				if (PEAR::isError($insertId))
				{
					return false;
				}
				else 
				{
					return $insertId;
				}
				
			}
			
		}
		return false;
	}
	
	/**
	 * updateWithPk
	 *
	 * @param Integer $id - Primary Key
	 * @param array $aData - Array Update Data
	 * @return boolean
	 * 
	 * @desc Update with Primary Key
	 */
	public function updateWithPk($id, $aData)
	{
		$sSql = "UPDATE {$this->table} SET ";
		
		$sUpdate = "";
		$doUpdate = false;

		foreach ($aData as $field => $value)
		{
			if($this->isField($field) && !$this->isPrimaryKey($field))
			{
				$sUpdate .= "`{$field}`='{$this->prepareInput($value)}',";
				$doUpdate = true;
			}
		}
		
		if($doUpdate)
		{
			//remove last comma
			$sUpdate = substr($sUpdate,0,strlen($sUpdate)-1);
			
			$sSql .= $sUpdate;
			$sSql .= " WHERE {$this->primaryKey} in ({$id})";
			$result = $this->pearDb->query($sSql);
			
			if (PEAR::isError($result))
			{
				return false;
			}
			else 
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * updateWithFk
	 *
	 * @param Array $aFk - Array Foreign Key
	 * @param Array $aData - Array Update Data
	 * @return boolean
	 * 
	 * @desc Update with Foreign Keys
	 */
	public function updateWithFk($aFk, $aData)
	{
		$sSql = "UPDATE {$this->table} SET ";
		
		$sUpdate = "";
		$sWhere = " WHERE ";
		$doUpdate = false;
		$foundOneFkey = false;
		foreach ($aData as $field => $value)
		{
			if($this->isField($field) && !$this->isPrimaryKey($field) && !$this->isForeignKey($field))
			{
				$sUpdate .= "`{$field}`='{$this->prepareInput($value)}',";
				$doUpdate = true;
			}
		}
		
		foreach ($aFk as $field => $value)
		{
			if($this->isForeignKey($field))
			{
				$sWhere .= "`{$field}`='{$this->prepareInput($value)}',";
				$foundOneFkey = true;
			}
		}
		
		if($doUpdate && $foundOneFkey)
		{
			//remove last comma
			$sUpdate = substr($sUpdate,0,strlen($sUpdate)-1);
			$sWhere = substr($sWhere,0,strlen($sWhere)-1);
			
			$sSql .= $sUpdate . $sWhere;
			
			$result = $this->pearDb->query($sSql);
			
			if (PEAR::isError($result))
			{
				return false;
			}
			else 
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * deleteWithPk
	 *
	 * @param integer $id
	 * @return boolean
	 */
	public function deleteWithPk ($str_id)
	{
		pre($str_id);
		die();
		$result = $this->pearDb->query("DELETE FROM {$this->table} WHERE {$this->primaryKey} in ({$str_id})");
		return (PEAR::isError($result)) ? false : true;
	}
	
	/**
	 * deleteWithFk
	 *
	 * @param array $aFk
	 * @return boolean
	 * 
	 * @desc
	 * Delete with Foreign Keys
	 * Delete if found at least one FK
	 */
	public function deleteWithFk ($aFk)
	{
		$sSql = "DELETE FROM {$this->table} WHERE 1 ";
		
		$sWhere = "";
		$foundOneFkey = false;
		
		foreach ($aFk as $field => $value)
		{
			if($this->isForeignKey($field))
			{
				$sWhere .= "AND {$field}='{$this->prepareInput($value)}' ";
				$foundOneFkey = true;
			}
		}
		
		if($foundOneFkey)
		{
			$sSql .= $sWhere;
			$result = $this->pearDb->query($sSql);
			
			return (PEAR::isError($result)) ? false : true;
		}
		return false;
	}
	
	/**
	 * deleteWithFk
	 *
	 * @param array $aFk
	 * @return boolean
	 * 
	 * @desc
	 * Delete with Foreign Keys
	 * Delete if found at least one FK
	 */
	public function countFkey ($Fkey,$table)
	{
		$sSql = "SELECT count(*) FROM {$table} WHERE 1 ";
		
		$sWhere = "";
		$foundOneFkey = false;
		$result = 0;
		foreach ($Fkey as $field => $value)
		{			
				$sSql .= "AND {$field}='{$this->prepareInput($value)}' ";
				$foundOneFkey = true;
			
		}
		
		if($foundOneFkey)
		{
			$result = $this->pearDb->getOne($sSql);
		}
		return $result;
	}
	/**
	 * setInputType
	 *
	 * @param string $datatype - Data Type of Field on DB
	 * @return array Atribute - Array Attribute of HTML Input Tag
	 */
	private function setInputType($datatype)
	{
		$posStartVal = strpos($datatype, $this->startVal);
		$posEndVal = strpos($datatype, $this->endVal);
		if($posStartVal === false)
		{
			$type = $datatype;
			$val = false;
		}
		else 
		{
			$lengthVal = $posEndVal - $posStartVal - strlen($posStartVal);
			
			$type = substr($datatype, 0, $posStartVal);
			$val = substr($datatype, $posStartVal + 1, $lengthVal);
		}
		
		$type = strtolower($type);
		$array = array();
		$arrayAttr = array();
	
		
		switch ($type)
		{
			case 'varchar':
			case 'char':
				$array['InputType'] = 'text';
				
				if($val !== false)
				{
					//$arrayAttr['class'] = 'txtbox';
					$arrayAttr['maxlength'] = intval($val);
				}
				break;
			case 'text':
			case 'tinytext':
			case 'midiumtext':
			case 'longtext':
			case 'blob':
			case 'tinyblob':
			case 'midiumblob':
			case 'longblob':
				$array['InputType'] = 'textarea';
				break;
			case 'int':
			case 'smallint':
			case 'mediumint':
			case 'bigint':
				$array['InputType'] = 'text';
				if($val !== false)
				{
					$arrayAttr['maxlength'] = intval($val);
				}
				break;
			case 'tinyint':
				$array['InputType'] = 'checkbox';
				$array['TrueValue'] = 1; //or Yes
				break;
			case 'float':
			case 'double':
			case 'decimal':
				$array['InputType'] = 'text';
				if($val !== false)
				{
					$arrayAttr['maxlength'] = intval($val);
				}
				break;
			case 'time':
			case 'date':
			case 'datetime':
				//$array['InputType'] = 'date';
				//date is pear type
				//datetime is bsg type :)
				$array['InputType'] = 'datetime';
				break;
			case 'enum':
				$array['InputType'] = 'select';
				$val = str_replace("'",'',$val);
				$val = str_replace('"','',$val);
				$array['Options'] = explode(',',$val);
				break;
			default:
				$array['InputType'] = 'hidden';
				break;
		}
		
		if(isset($arrayAttr['maxlength']))
		{
			$arrayAttr['size'] = (round(sqrt($arrayAttr['maxlength']) * 2.5));
		}
		
		$array['Attributes'] = $arrayAttr;
		
		return $array;
	}
	
	/**
	 * prepareInput
	 *
	 * @param string $value
	 * @return string $value
	 * 
	 * @desc process data before input to db (remove tags, remove javascript, add slashes,...)
	 */
	private function prepareInput ($value)
	{
		$value = stripslashes($value);
		return addcslashes($value,"'");
	}
	
	private function inArray($needle, $array)
	{
		return in_array($needle, $array) ? true : false;
	}

	function querySQL($sql)
	{
		return $result = $this->pearDb->query($sql);
	}

	function getOneSQL($sql)
	{
		return $this->pearDb->getOne($sql);
	}

	private function test ($value)
	{
		echo "<pre>";
		if(is_array($value))
			print_r($value);
		else 
			echo $value;
		echo "</pre>";
	}
}

?>