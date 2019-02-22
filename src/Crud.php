<?php
/**
 * Created by PhpStorm.
 * User: Szymon
 * Date: 08/01/2019
 * Time: 14:05
 */


/**
 * Class crud
 * @package App\Config
 */
class crud {
    /**
     * @var connectDb
     */
    private $connectDb;

    /**
     * crud constructor.
     * @param connectDb $connectDb
     */
    public function __construct(connectDb $connectDb)
    {
        $this->connectDb = $connectDb->openConnection();
    }

    /**
     * @param string $sql this is example we can put simple query "SELECT * FROM Table"
     * @return array $result
     */
    public function read($sql) {
        $data = $this->connectDb->prepare($sql);
        $data->execute();
        $result = $data->fetchAll();
        return isset($result) ? $result : "Is empty";
    }

    /**
     * Insert multiple rows inside table
     * @param array $multiArray two dimensional array as example [[1,2], [4,5],[7,8]]
     * @return int last insert Id inside table
     */
    public function insert($multiArray) {

        $table = 'company';
        $columns = array('company_id', 'company_name', 'company_city');
        $columns2 = implode(",",$columns);
        $pdo = $this->connectDb;
        $pdo->beginTransaction();
        $sql = "insert into $table ($columns2) values ";

        $paramArray = array();
        $sqlArray = array();

        foreach($multiArray as $row)
        {
            $sqlArray[] = '(' . implode(',', array_fill(0, count($row), '?')) . ')';
            foreach($row as $element)
            {
                $paramArray[] = $element;
            }
        }

        $sql .= implode(',', $sqlArray);
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute($paramArray);
            $count = $stmt->rowCount();

            //print $last_insert_id = $pdo->lastInsertId();
            $pdo->commit();

            return print("Inserted $count rows.\n");
        } catch (PDOException $e){
            $pdo->rollBack();
            echo $e->getMessage();
        }
        $pdo = null;
    }

    /**
     * delete multiple rows inside table
     * @param array $ids one dimensional
     * @return int number of deleted records
     */
    public function remove($ids)
    {
        $pdo = $this->connectDb;
        $id = array();
        foreach ($ids as $val) {
            $id[] = (int)$val;
        }
        $id = implode(',', $id);
        $stmt = $pdo->prepare("DELETE FROM users WHERE id IN ($id)");
        $stmt->execute();

        $countDel = $stmt->rowCount();
        return ($countDel != 0) ?  print $countDel . "rows DELETED successfully" :  print $countDel . "rows DELETED";
    }

    /**
     * update multiple rows inside table
     * $array = [
            "option"  => "value"
            ];
     * @param array $arrayUpdate
     * @return int number of updated records
     */
    public function update($arrayUpdate) {
        $pdo = $this->connectDb;
        $stmt = $pdo->prepare("UPDATE ini SET option = ? WHERE value = ?");
        foreach($arrayUpdate as $k => $v) {
            $id = $k;
            $column_value = $v;
            $stmt->execute(array($id,$column_value));
        }

        $rowCounter = $stmt->rowCount();
        return ($rowCounter != 0) ? print $rowCounter . " records UPDATE successfully" : print $rowCounter . " records UPDATED";
    }

    public function getConnect() {
        return $this->connectDb->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }
}

