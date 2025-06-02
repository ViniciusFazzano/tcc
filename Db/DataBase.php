<?php

namespace DB;

use \PDO;
use \PDOException;

class DataBase
{

    /**
     * Host de conexão com banco de dados
     * @var string
     */
    private static $host;

    /**
     * Nome do banco de dados
     * @var string
     */
    private static $name;

    /**
     * Usuário do banco
     * @var string
     */
    private static $user;

    /**
     * Senha de acesso ao banco de dados
     * @var string
     */
    private static $pass;

    /**
     * Porta de acesso ao banco
     * @var integer
     */
    private static $port;

    /**
     * nome da tabela a ser manipulado
     * @var string
     */
    private $table;

    /**
     * instancia de conexão com banco de dados
     * @var PDO
     */
    private $connection;

    public static function config($host, $name, $user, $pass, $port = 5432)
    {
        self::$host = $host;
        self::$name = $name;
        self::$user = $user;
        self::$pass = $pass;
        self::$port = $port;
    }

    /**
     * Define a tabela e instancia e conexão
     * 
     */
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    public function setTable(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    private function setConnection()
    {
        try {
            $this->connection = new PDO('pgsql:host=' . self::$host . ';dbname=' . self::$name . ';port=' . self::$port, self::$user, self::$pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // LANÇA UMA EXCEPTION SE ALGO DER ERRADO
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }


    /**
     * MÉTODO RESPONSÁVEL POR EXECUTAR QUERIES DENTRO DO BANCO DE DADOS
     *
     * @param string $query
     * @param array $params
     * @return PDO
     */
    public function execute($query, $params = [])
    {
        try {
            $statement = $this->connection->prepare($query);

            $statement->execute($params);

            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * MÉTODO RESPONSAVEL POR INSERIR DADOS NO BANCO 
     * @param array
     * @return integer id inserido
     */
    public function insert($values)
    {
        //DADOS DA QUERY
        $fields = array_keys($values);

        $binds = array_pad([], count($fields), '?'); //array_pad(Array inicial de valores para ser preenchido, Nova tamanho do array, Valor para preencher se input é menor que length)

        //MOSNTA A QUERY
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';

        $params = array_values($values);
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        $this->execute($query, array_values($values));

        //RETORNAO ID INSERIDO
        return $this->connection->lastInsertId();
    }

    public function insertSemId($values)
    {
        //DADOS DA QUERY
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?'); //array_pad(Array inicial de valores para ser preenchido, Nova tamanho do array, Valor para preencher se input é menor que length)

        //MOSNTA A QUERY
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";exit;

        $this->execute($query, array_values($values));

        //RETORNAO ID INSERIDO
        // return $this->connection->lastInsertId();
    }

    /**
     * MÉTODO REPONSÁVEL POR EXECUTAR UMA CONSULTA NO BANCO
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return PDOStatement
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        //dados da query
        // print_r($where);
        $where = isset($where) ? 'WHERE ' . $where : '';
        $order = isset($order) ? 'ORDER BY ' . $order : '';
        $limit = isset($limit) ? 'LIMIT ' . $limit : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";


        return $this->execute($query);
    }

    /**
     * MÉTODO REPONSÁVEL POR EXECUTAR UMA CONSULTA NO BANCO
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @return PDOStatement
     */
    public function selectBind($where = null, $order = null, $limit = null, $fields = '*', $values = null)
    {
        $where = isset($where) ? 'WHERE ' . $where : '';
        $order = isset($order) ? 'ORDER BY ' . $order : '';
        $limit = isset($limit) ? 'LIMIT ' . $limit : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        return $this->execute($query, array_values($values));
    }

    /**
     * Consulta de joins
     *
     * @param string $where
     * @param string $fields
     * @param string $join
     * @return PDOStatement
     */
    public function selectJoin($where = null, $fields = '*', $join = null, $groupBy = null, $values = null, $orderBy = null)
    {

        $where = isset($where) ? 'WHERE ' . $where : '';
        $join = isset($join) ? 'INNER JOIN ' . $join : '';
        $groupBy = isset($groupBy) ? 'GROUP BY ' . $groupBy : '';
        $orderBy = isset($orderBy) ? 'ORDER BY ' . $orderBy : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $join . ' ' . $where . ' ' . $groupBy . ' ' . $orderBy;
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // exit;

        return $this->execute($query, array_values($values));
    }

    /**
     * Consulta de joins
     *
     * @param string $where
     * @param string $fields
     * @param string $join
     * @return PDOStatement
     */
    public function selectJoinPersonalizavel($where = null, $fields = '*', $join = null, $groupBy = null, $values = null, $orderBy = null)
    {

        $where = isset($where) ? 'WHERE ' . $where : '';
        $join = isset($join) ? ' ' . $join : '';
        $groupBy = isset($groupBy) ? 'GROUP BY ' . $groupBy : '';
        $orderBy = isset($orderBy) ? 'ORDER BY ' . $orderBy : '';

        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $join . ' ' . $where . ' ' . $groupBy . ' ' . $orderBy;

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // exit;

        return $this->execute($query, array_values($values));
    }

    //Método responsável por executar atualizações no banco de dados
    public function update($where, $values)
    {
        //DADOS DA QUERY
        $fields = array_keys($values);

        //MONTA A QUERY
        $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // exit;

        //EXECUTAR A QUERY
        $this->execute($query, array_values($values));

        //RETORNA SUCESSO
        return true;
    }

    //Método responsável por executar atualizações no banco de dados
    public function update2($where, $values, $valoresWhere)
    {
        //DADOS DA QUERY
        $fields = array_keys($values);

        //MONTA A QUERY
        $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;

        $values = array_merge($values, $valoresWhere);
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // exit;

        //EXECUTAR A QUERY
        $this->execute($query, array_values($values));

        //RETORNA SUCESSO
        return true;
    }

    //Método responsável por executar a atualização de mais de uma banco de dados
    public function updateWithConnection($table, $where, $values)
    {
        // DADOS DA QUERY
        $fields = array_keys($values);

        // MONTA A QUERY
        $query = 'UPDATE ' . $table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";

        // EXECUTAR A QUERY
        $this->execute($query, array_values($values));

        // RETORNA SUCESSO
        return true;
    }

    //Método responsável por excluir dados do banco
    public function delete($where, $valuesWhere)
    {
        //MONTA A QUERY
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // exit;

        //EXECUTA A QUERY
        $this->execute($query, array_values($valuesWhere));

        //RETORNA SUCESSO
        return true;
    }

    /**
     * Consulta de joins
     *
     * @return PDOStatement
     */
    public function selectQueryCompleta(string $query, array $values)
    {
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // exit;

        return $this->execute($query, $values);
    }
}
