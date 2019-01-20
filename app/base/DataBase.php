<?php

namespace app\base;

use app\components\CaseTranslator;

class DataBase extends Application
{
    private $pdo;
    private $tableName;

    private static $dbInstance;

    /**
     * DataBase constructor.
     * @param array|null $config
     */
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

    /**
     * @return string|null
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return array
     */
    public function selectAll(): array
    {
        $query = "SELECT * FROM $this->tableName;";
        return $this->executeQuery($query);
    }

    /**
     * @param array $data
     * @param string|null $operator
     * @return array
     */
    public function selectAllWhere(array $data, string $operator = null): array
    {
        $data = CaseTranslator::keysTo('snake', $data);
        $whereString =  $this->prepareWhereData($data, $operator);
        $query = "SELECT * FROM $this->tableName WHERE ${whereString};";

        return $this->executeQuery($query, $data);
    }

    /**
     * @param array $data
     * @param string|null $operator
     * @return string
     */
    private function prepareWhereData(array $data, string $operator = null): string {
        $shouldSeparate = count($data) - 1;
        $operator = $operator ?: 'AND';
        $res = '';
        foreach (array_keys($data) as $key) {
            $res .= $key . ' = :'. $key;
            if ($shouldSeparate--) {
                $res .= ' ' . $operator . ' ';
            }
        }

        return $res;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $data = CaseTranslator::keysTo('snake', $data);
        $insertData = $this->prepareInsertData($data);
        $columns    = $insertData['columns'];
        $holders    = $insertData['holders'];

        $query = "INSERT INTO $this->tableName ($columns) VALUES ($holders);";

        return $this->executeQuery($query, $data, false);
    }

    /**`
     * @param array $data
     * @return array
     */
    private function prepareInsertData(array $data): array
    {
        $keys = array_keys($data);
        $insertData['columns'] = implode(', ', $keys);
        $insertData['holders'] = ':' . implode(', :', $keys);

        return $insertData;
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
     * @param array $setData
     * @param array $whereData
     * @param string|null $operator
     * @return bool
     */
    public function update(array $setData, array $whereData, string $operator = null): bool
    {
        $setData = CaseTranslator::keysTo('snake', $setData);
        $whereData = CaseTranslator::keysTo('snake', $whereData);
        $setString = $this->prepareSetData($setData);
        $whereString = $this->prepareWhereData($whereData, $operator);
        $query = "UPDATE $this->tableName SET ${setString} WHERE ${whereString}";

        return $this->executeQuery($query, array_merge($setData, $whereData), false);
    }

    /**
     * @param array $setData
     * @return string
     */
    private function prepareSetData(array $setData) {
        $shouldSeparate = count($setData) - 1;
        $setString = '';
        foreach ($setData as $column => $value) {
            $setString .= $column . ' = :' . $column;
            if ($shouldSeparate--) {
                $setString .= ', ';
            }
        }

        return $setString;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function rowExists(array $data, string $operator = 'and'): bool
    {
        $data = CaseTranslator::keysTo('snake', $data);
        $whereString =  $this->prepareWhereData($data, $operator);
        $query = "SELECT count(*) FROM $this->tableName WHERE ${whereString};";
        return $this->executeQuery($query, $data)[0]['count'];
    }

    /**
     * @param string $query
     * @param array|null $data
     * @param bool $fetch
     * @return array|bool
     */
    public function executeQuery(string $query, array $data = null, $fetch = true)
    {
        $stm = $this->pdo->prepare($query);
        $dbResult = $stm->execute($data);
        return $fetch ? $stm->fetchAll() : $dbResult;
    }

}
