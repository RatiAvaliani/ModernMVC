<?php
namespace Libs;

class Database {
    protected $db;
    protected $result;
    protected $info;
    protected $statement = array();
    protected $table     = array();
    protected $key       = array();

    /*
     * database config
     */
    protected static $config = array(
        'driver' => 'mysql',
        'host'   => 'localhost',
        'port'   =>  3307,
        'fetch'  => 'stdClass'
    );

    /*
     * this class instances
     */
    protected static $instance  = array();

    /*
     *  names of the parameters (witch are db config parameters)
     */
    protected static $arguments = array( 'driver', 'host', 'database', 'user', 'password' );

    /**
     * @param $name
     * @param $config
     * @return mixed|static
     * gets a static call gets the name and the new config,
     * tests if there is a instance already and returns if true
     * in the config variable takes static config function
     * witch is filtering arg names and passed parameters
     */
    static public function __callStatic ($name, $config ) {
        if ( isset( static::$instance[ $name ] ) ) return static::$instance[ $name ];

        $config = array_merge(
            static::config(),
            array_filter(
                array_combine(
                    static::$arguments,
                    $config +
                        array_fill(
                                0,
                                count( static::$arguments),
                                null
                        )
                )
            )
        );

        return static::$instance[ $name ] = new static( $config[ 'driver' ], $config[ 'host' ], $config[ 'database' ], $config[ 'user' ], $config[ 'password' ] );
    }


    /**
     * Database constructor.
     * @param $driver
     * @param $host
     * @param $database
     * @param $user
     * @param null $password
     * getting config, setting a exception default header
     * crates instance of PDO
     * combines parameters to the input,
     * un sets the password
     */
    public function __construct ($driver, $host, $database, $user, $password = null ) {
      //  set_exception_handler(array( __CLASS__, 'safe_exception' ) );

        $this->db = new \pdo( $driver . ':host=' . $host . ';dbname=' . $database, $user, $password, array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ) );

        //restore_exception_handler();

        $this->db->exec('SET NAMES "UTF8"');
        $this->info = (object) array_combine( static::$arguments, func_get_args() );

        unset($this->info->password);
    }

    /**
     * @param null $key
     * @param null $value
     * @return array|mixed|null
     * filtering config and passed parameters
     */
    public static function config ($key = null, $value = null ) {
        if ( ! isset( $key ) ) return static::$config;

        if ( isset( $value ) ) return static::$config[ (string) $key ] = $value;

        if ( is_array( $key ) ) return array_map( 'static::config', array_keys( (array) $key ), array_values( (array) $key ) );

        if ( isset( static::$config[ $key ] ) ) return static::$config[ $key ];
    }

    /**
     * @param Exception $exception
     * dies and returns error passed from exception
     */
    public static function safe_exception (Exception $exception ) {
        die( 'Uncaught exception: ' . $exception->getMessage() );
    }

    /**
     * @return null
     * tests if return content from query is valid ("string")
     */
    public function __toString () {
        return $this->result ?
            $this->result->queryString :
            null;
    }

    /**
     * @param $sql
     * @return $this
     * gets sql command and returns raw data (this way you can use PDO functions on it or ...)
     */
    public function raw ($sql ) {
        $this->result = $this->db->query( $sql );
        return $this;
    }

    /**
     * @param $sql
     * @param array $params
     * @return $this
     * if passed sql code is no in the statements if wil run the sql code and pass args
     */
    public function query ($sql, array $params ) {
        $this->result = isset( $this->statement[ $sql ] ) ?
            $this->statement[ $sql ] :
            $this->statement[ $sql ] = $this->db->prepare( self::_uncomment( $sql ) );

        $this->result->execute( $params );

        return $this;
    }

    public function select ( $table, $fields = '*', $sqlAddon = NULL, $where = null, $order = null, $limit = null ) {
        $sql = 'SELECT ' . self::_fields( $fields ) . ' FROM ' . $this->_table( $table );

        if ( $sqlAddon ) $sql .= $sqlAddon;

        if ( $where && $where = $this->_conditions( $where ) ) $sql .= ' WHERE ' . $where->sql;

        if ( $order ) $sql .= ' ORDER BY ' . ( is_array( $order ) ? implode( ', ', $order ) : $order );

        if ( $limit ) $sql .= ' LIMIT ' . $limit;

        return $where ?
            $this->query( $sql, $where->params ) :
            $this->raw( $sql );
    }

    static protected function _is_plain ( $data ) {
        if ( ! is_scalar( $data ) )
            return false;
        return is_string( $data ) ? ! preg_match( '/\W/i', $data ) : true;
    }

    static protected function _is_list ( array $array ) {
        foreach ( array_keys( $array ) as $key )
            if ( ! is_int( $key ) )
                return false;
        return true;
    }

    static protected function _uncomment ( $sql ) {
        return trim( preg_replace( '@(([\'"`]).*?[^\\\]\2)|((?:\#|--).*?$|/\*(?:[^/*]|/(?!\*)|\*(?!/)|(?R))*\*\/)\s*|(?<=;)\s+@ms', '$1', $sql ) );
    }

    static protected function _params ( $data, $operator = '=', $glue = ', ' ) {
        $params = is_string( $data) ? array( $data ) : array_keys( (array) $data );
        foreach ( $params as &$param )
            $param = implode( ' ', array( self::_escape( $param ), $operator, ':' . $param ) );
        return implode( $glue, $params );
    }

    static protected function _escape ( $field ) {
        return self::_is_plain( $field ) ?
            '`' . $field  . '`' :
            $field;
    }

    static protected function _extract ( $table, $type = 'table' ) {
        static $infos = array(
            'database' => '@(?:(`?)(?P<database>\w+)\g{-2})\.(`?)(?P<table>\w+)\g{-2}(?:\.(`?)(?P<field>\w+)\g{-2})?@',
            'table'    => '@(?:(`?)(?P<database>\w+)\g{-2}\.)?(?:(`?)(?P<table>\w+)\g{-2})(?:\.(`?)(?P<field>\w+)\g{-2})?@',
            'field'    => '@(?:(`?)(?P<database>\w+)\g{-2}\.)?(?:(`?)(?P<table>\w+)\g{-2}\.)?(`?)(?P<field>\w+)\g{-2}@'
        );

        if ( ! isset( $infos[ $type ] ) || ! preg_match( $infos[ $type ], $table, $match ) ) return;

        $match = array_filter( array_intersect_key( $match, $infos ) );
        return $match[ $type ];
    }

    static protected function _alias ( array $alias ) {
        foreach ( $alias as $k => $v ) {
            $_alias[] = self::_escape( $v ) . ( is_string( $k ) ? ' AS '. self::_escape( $k ) : '' );
        }

        return $_alias;
    }

    static protected function _fields ( $fields ) {
        if ( empty( $fields ) ) return '*';

        if ( is_string( $fields ) ) return $fields;

        return implode( ', ', self::_alias ( $fields ) );
    }

    //@todo
    static protected function _conditions ( array $conditions ) {
        $sql    = array();
        $params = array();
        $i      = 0;

        foreach ( $conditions as $condition => $param ) {
            if ( is_string( $condition ) ) {
                for ( $keys = array(), $n = 0; false !== ( $n = strpos( $condition, '?', $n ) ); $n ++ ) {
                    $condition = substr_replace( $condition, ':' . ( $keys[] = '_' . ++ $i ), $n, 1 );
                }

                if ( ! empty( $keys ) ) {
                    $param = array_combine( $keys, (array) $param );
                }

                if ( self::_is_plain( $condition ) ) {
                    $param = array( $condition => (string) $param );
                    $condition = self::_params( $condition );
                }

                $params += (array) $param;
            } else $condition = $param;

            $sql[] = $condition;
        }
        return (object) array(
            'sql'    => '( ' . implode( ' ) AND ( ', $sql ) . ' )',
            'params' => $params
        );
    }

    protected function _table ( $table, $escape = true ) {
        return $escape ?
            self::_escape( $this->_database( $table ) )  . '.' . self::_escape( self::_extract( $table, 'table' )  ) :
            $this->_database( $table )  . '.' . self::_extract( $table, 'table' );
    }

    protected function _database ( $table = null ) {
        return self::_extract( $table, 'database' ) ?:
            $this->info->database;
    }

    static protected function _column ( array $data, $field ) {
        $column = array();
        foreach ( $data as $key => $row )
            if ( is_object( $row ) && isset( $row->{$field} ) ) $column[ $key ] = $row->{$field};

            else if ( is_array( $row ) && isset( $row[ $field ] ) ) $column[ $key ] = $row[ $field ];

            else $column[ $key ] = null;

            return $column;
    }

    static protected function _index ( array $data, $field ) {
        return array_combine(
            self::_column( $data, $field ),
            $data
        );
    }

    public function create ( $table, array $data ) {
        $keys = array_keys( $data );
        $sql  = 'INSERT INTO ' . $this->_table( $table ) . ' (' . implode( ', ', $keys ) . ') VALUES (:' . implode( ', :', $keys ) . ')';
        return $this->query( $sql, $data );
    }

    public function read ( $table, $id, $key = null ) {
        $key = $key ?: current( $this->key( $table ) );
        $sql = 'SELECT * FROM ' . $this->_table( $table ) . ' WHERE ' . self::_params( $key );
        return $this->query( $sql, array( ':' . $key => $id ) );
    }

    public function update ( $table, $data, $value = null, $id = null, $key = null ) {
        if ( is_array( $data ) ) {
            $key  = $id;
            $id   = $value;
        } else $data = array( $data => $value );

        $key = $key ?: current( $this->key( $table ) );

        if ( is_null( $id ) && isset( $data[ $key ] ) && ! ( $id = $data[ $key ] ) ) throw new Exception( 'No `' . $key . '` key value to update `' . $table . '` table, please specify a key value' );

        $sql = 'UPDATE ' . $this->_table( $table ) . ' SET ' . self::_params( $data ) . ' WHERE ' . self::_params( $key );

        return $this->query( $sql, array_merge( $data, array( ':' . $key => $id ) ) );
    }

    public function delete ( $table, $id, $key = null ) {
        $key = $key ?: current( $this->key( $table ) );
        $sql = 'DELETE FROM ' . $this->_table( $table ) . ' WHERE ' . self::_params( $key );

        return $this->query( $sql, array( ':' . $key => $id ) );
    }

    public function fetch ( $class = null ) {
        if ( ! $this->result ) throw new Exception( 'Can\'t fetch result if no query!' );

        return $class === false ?
            $this->result->fetch( PDO::FETCH_ASSOC ) :
            $this->result->fetchObject( $class ?: self::config( 'fetch' ) );
    }

    public function all ( $class = null ) {
        if ( ! $this->result ) throw new Exception( 'Can\'t fetch results if no query!' );

        return $class === false ?
            $this->result->fetchAll( PDO::FETCH_ASSOC ) :
            $this->result->fetchAll( PDO::FETCH_CLASS, $class ?: self::config( 'fetch' ) );
    }

    public function column ( $field, $index = null ) {
        $data   = $this->all( false );
        $values = self::_column( $data, $field );

        return is_string( $index ) ?
            array_combine( self::_column( $data, $index ), $values ) :
            $values;
    }

    public function key ( $table ) {
        $table = $this->_table( $table, false );

        if ( self::config( $table . ':PK' ) ) return self::config( $table . ':PK' );

        else if ( isset( $this->key[ $table ] ) ) return $this->key[ $table ];

        $keys = array_keys( self::_column( $this->fields( $table ), 'key' ), 'PRI' );

        if ( empty( $keys ) ) throw new Exception( 'No primary key on ' . $this->_table( $table ) . ' table, please set a primary key' );

        return $this->key[ $table ] = $keys;
    }

    public function fields ( $table ) {
        $table = $this->_table( $table, false );
        if ( isset( $this->table[ $table ] ) ) return $this->table[ $table ];

        $sql = 'SELECT 
				`COLUMN_NAME`                                               AS `name`, 
				`COLUMN_DEFAULT`                                            AS `default`, 
				NULLIF( `IS_NULLABLE`, "NO" )                               AS `null`, 
				`DATA_TYPE`                                                 AS `type`, 
				COALESCE( `CHARACTER_MAXIMUM_LENGTH`, `NUMERIC_PRECISION` ) AS `length`, 
				`CHARACTER_SET_NAME`                                        AS `encoding`, 
				`COLUMN_KEY`                                                AS `key`, 
				`EXTRA`                                                     AS `auto`, 
				`COLUMN_COMMENT`                                            AS `comment`
			FROM `INFORMATION_SCHEMA`.`COLUMNS`
			WHERE 
				`TABLE_SCHEMA` = ' . $this->quote( self::_database( $table ) ) . ' AND 
				`TABLE_NAME` = ' . $this->quote( self::_extract( $table )  ) . '
			ORDER BY `ORDINAL_POSITION` ASC';
        $fields = $this->db->query( $sql );

        if ( ! $fields->rowCount() ) throw new Exception( 'No ' . $this->_table( $table ) . ' table, please specify a valid table' );

        return $this->table[ $table ] = self::_index( $fields->fetchAll( PDO::FETCH_CLASS ), 'name' );
    }

    public function quote ( $value ) {
        return is_null( $value ) ?
            'NULL' :
            $this->db->quote( $value );
    }

    public function database ( $table = null ) {
        return $this->_table( $table, true ) ?:
            $this->info->database;
    }

    public function id () {
        return $this->db->lastInsertId();
    }

    public function count () {
        return $this->result ?
            $this->result->rowCount() :
            null;
    }

    public function escape_value( $value ) {
        if ($value){
            $value = stripslashes( $value );
            $value = htmlentities( $value );
            $value = addslashes( $value );
            return $value;
        } else {
            return null;
        }

    }
}
