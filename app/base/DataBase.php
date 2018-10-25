<?php

namespace app\base;

use app\components\CaseTranslator;

class DataBase extends Application
{
    private $pdo;
    private $tableName;

    private static $dbInstance;

    private function __construct(array $config=null)
    {
        $this->getConnection($config);
    }

    /**
     * @param string|null $tableName
     * @param array|null $config
     * @return DataBase
     */
    public static function getInstance(string $tableName=null, array $config=null): DataBase
    {
        if (!self::$dbInstance)
            self::$dbInstance = new self($config);

        if ($tableName !== null) {
            self::$dbInstance->useTable($tableName);
        }
        return self::$dbInstance;
    }

    /**
     * @param array|null $config
     */
    private function getConnection(array $config=null): void
    {
        $config = $config ? $config : self::$config['db'];

        $driver     = $config['driver'];
        $host       = $config['host'];
        $dbname     = $config['dbname'];
        $user       = $config['user'];
        $password   = $config['password'];

        $dsn = "{$driver}:host={$host};dbname={$dbname};user={$user};password={$password}";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->pdo = new \PDO($dsn, $user, $password, $options);
    }

    /**
     * @param string $tableName
     * @return DataBase
     */
    public function useTable(string $tableName): DataBase
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return array
     */
    public function selectAll(): array
    {
        $query = "SELECT * FROM \"$this->tableName\";";
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $rowData = $stm->fetchAll();
        return $rowData;
    }

    /**
     * @param array $data
     * @param string|null $operator
     * @return array
     */
    public function selectAllWhere(array $data, string $operator=null): array
    {
        $whereString =  $this->prepareWhereData($data, $operator);
        $query = "SELECT * FROM \"$this->tableName\" WHERE ${whereString};";
        $stm = $this->pdo->prepare($query);
        $stm->execute(array_values($data));
        $dbData = $stm->fetchAll();
        return $dbData;
    }

    /**
     * @param array $data
     * @param string|null $operator
     * @return string
     */
    private function prepareWhereData(array $data, string $operator=null)
    {
        if (count($data) > 1 && $operator === null) {
            $operator = 'AND';
        }
        return implode(" = ? ${operator} ", array_keys($data)) . ' = ?';
    }

    /**
     * @param array $data
     * @return bool
     */
    public function rowExists(array $data): bool
    {
        return count($this->selectAllWhere($data));
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $insertData = $this->prepareInsertData($data);
        $columns    = $insertData['columns'];
        $holders    = $insertData['holders'];
        $values     = $insertData['values'];

        $query = "INSERT INTO \"$this->tableName\" ($columns) VALUES ($holders);";
        $stm = $this->pdo->prepare($query);

        return $stm->execute($values);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insertIfNotExists(array $data): bool
    {
        $value = reset($data);
        $column = key($data);

        if (!$this->rowExists([$column => $value])) {
            return $this->insert($data);
        }
        return false;
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareInsertData(array $data): array
    {
        $keys = CaseTranslator::arrayTo('snake', array_keys($data));
        $insertData['columns'] = implode(', ', $keys);
        $insertData['holders'] = str_repeat('?, ', count($data) - 1) . '?';
        $insertData['values']  = array_values($data);

        return $insertData;
    }

    /**
     * @param string $query
     * @return array
     */
    public function executeQuery(string $query): array
    {
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $dbData = $stm->fetchAll();
        return $dbData;
    }

}
