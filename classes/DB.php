<?php
namespace MVC;

class DB
{		
    protected $_rDB;		
    protected $_sTbl;
    
    /**		 
     * 
     * @param PDO $rDB Обект с връзка към базата данни
     * @param string $sTable Име на таблица
     * @throws Exception Грешка
     */
    function __construct(PDO $rDB, $sTable)
    {
        if(empty($rDB)) 
        {
            throw new Exception();
        }
        
        if(empty($sTable))
        {
            throw new Exception();
        }
        
        $this->_rDB = $rDB;
        $this->_sTbl = $sTable;
    }
    
    protected function getFields()
    {
        $fields = $this->select("SHOW FIELDS FROM {$this->_sTbl}");
        
        $row = array();
        
        foreach ($fields as $field) {
            $row[$field['Field']] = "";
        }
        
        return $row;
    }
    
    /**
     * 
     * @param string $sQuery
     * @return PDOStatement
     */
    public function query($sQuery)
    {
        return $this->_rDB->query($sQuery);
    }
    
    /**
     * 
     * @param string $sQuery
     * @param bool $bAssoc
     * @return array
     */
    public function select($sQuery, $bAssoc = false) 
    {
        $result = $this->query($sQuery);

        $result->setFetchMode(PDO::FETCH_ASSOC);

        $resultSet = $result->fetchAll();

        $rows = array();

        if ($bAssoc) {
            foreach ($resultSet as $row) {
                $key = array_shift($row);

                if(count($row) == 1)
                {
                    $row = reset($row);
                }

                $rows[$key] = $row;
            }
        } else {
            $rows = $resultSet;
        }

        return $rows;
    }
    
    /**
     * 
     * @param string $sQuery
     * @return array
     */
    public function selectRow($sQuery)
    {
        $result = $this->select($sQuery);
        return reset($result);
    }
    
    /**
     * 
     * @param string $sQuery
     * @param string $sField
     * @return array
     */
    public function selectField($sQuery, $sField = '')
    {
        $row = $this->selectRow($sQuery);
        
        return !empty($sField) ? (!empty($row[$sField]) ? $row[$sField] : null) : reset($row);
    }
    
    /**
     * 
     * @param int $nID
     * @throws Exception
     * @return array()
     */
    public function get($nID)
    {
        if (!(int)$nID) {
            throw new Exception();
        }
        
        $stmt = $this->_rDB->prepare("SELECT * FROM {$this->_sTbl} WHERE id = :id");
        
        $stmt->bindParam(':id', $nID, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * 
     * @param array $aData
     * @throws Exception
     */
    public function update(&$aData)
    {
        if (empty($aData)) {
            throw new Exception();
        }
        
        foreach ($aData as $value) {
            if (!is_scalar($value)) {
                throw new Exception();
            }
        }
        
        $row = $this->getFields();
        
        foreach ($row as $key => $val) {
            if (!array_key_exists($key, $aData)) {
                unset($row[$key]);
                continue;
            }				
            
            $row[$key] = $aData[$key];
        }
        
        if (empty($row)) {
            throw new Exception();
        }

        if (!empty($row['id'])) { #update
            $this->doUpdate($row);
        } else { #insert
            $this->doInsert($row);
            $aData['id'] = $this->_rDB->lastInsertId();
        }
    }
    
    protected function doUpdate($row)
    {
        $aFields = array();
            
        foreach ($row as $key => $val) {
            if ($key != 'id') {
                $aFields[] = "`$key` = :$key";
            }
        }

        $sQuery = sprintf("UPDATE {$this->_sTbl} SET %s WHERE `id` = :id", implode(',', $aFields));

        $stmt = $this->_rDB->prepare($sQuery);

        $stmt->execute($row);
    }
    
    protected function doInsert($row)
    {
        $keys = array_keys($row);

        $valuesKeys = array();

        foreach ($row as $key => $value) {
            $valuesKeys[] = ":$key";
        }

        $sQuery = sprintf("INSERT INTO {$this->_sTbl}(`%s`) VALUES (%s)", implode('`,`', $keys), implode(',', $valuesKeys));
        
        $stmt = $this->_rDB->prepare($sQuery);

        $stmt->execute($row);
    }
    
    /**
     * @param int $nID
     * @throws Exception
     */
    public function delete($nID)
    {
        if (!is_numeric($nID)) {
            throw new Exception();
        }
        
        $field = $this->selectRow("SHOW FIELDS FROM {$this->_sTbl} WHERE FIELD like 'to_arc'");
        
        if (!empty($field)) {
            $data = array(
                'id' => $nID,
                'to_arc' => 1	
            );
            
            $this->update($data);
        } else {
            $stmt = $this->_rDB->prepare("DELETE FROM {$this->_sTbl} WHERE `id` = :id LIMIT 1");
            $stmt->bindParam(":id", $nID, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}