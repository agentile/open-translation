<?php
/**
 * SolateLite DB Abstract Connection Class For use in OT
 */
class OT_DB 
{
    /**
     * 
     * A PDO object for accessing the RDBMS.
     * 
     * @var object
     * 
     */
    protected $_pdo = null;
    
    /**
     * 
     * The PDO adapter DSN type.
     * 
     * This might not be the same as the Solar adapter type.
     * 
     * @var string
     * 
     */
    protected $_pdo_type = null;
    
    /**
     * 
     * A PDO-style DSN, for example, "mysql:host=127.0.0.1;dbname=test".
     * 
     * @var string
     * 
     */
    protected $_dsn;
    
    protected $_config = array();
    
    /**
     * __construct
     * Insert description here
     *
     * @param $config
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public function __construct($config = array())
    {
        $this->_config = $config;
        $this->_pdo_type = $config['type'];
        $this->_setDsn();
    }
    
    /**
     * 
     * Get the PDO connection object (connects to the database if needed).
     * 
     * @return PDO
     * 
     */
    public function getPdo()
    {
        $this->connect();
        return $this->_pdo;
    }
    
    /**
     * 
     * Sets the DSN value for the connection from the config info.
     * 
     * @return void
     * 
     */
    protected function _setDsn()
    {
        $this->_dsn = $this->_buildDsn($this->_config);
    }
    
    /**
     * 
     * Creates a PDO-style DSN.
     * 
     * For example, "mysql:host=127.0.0.1;dbname=test"
     * 
     * @param array $info An array with host, post, name, etc. keys.
     * 
     * @return string The DSN string.
     * 
     */
    protected function _buildDsn($info)
    {
        $dsn = array();
        
        if (! empty($info['host'])) {
            $dsn[] = 'host=' . $info['host'];
        }
        
        if (! empty($info['port'])) {
            $dsn[] = 'port=' . $info['port'];
        }
        
        if (! empty($info['name'])) {
            $dsn[] = 'dbname=' . $info['name'];
        }
        
        return $this->_pdo_type . ':' . implode(';', $dsn);
    }
    
    /**
     * 
     * Creates a PDO object and connects to the database.
     * 
     * Also sets the query-cache key prefix.
     * 
     * @return void
     * 
     */
    public function connect()
    {
        // if we already have a PDO object, no need to re-connect.
        if ($this->_pdo) {
            return;
        }
        
        // attempt the connection
        $this->_pdo = new PDO(
            $this->_dsn,
            $this->_config['user'],
            $this->_config['pass']
        );
        
        // retain connection info
        $this->_pdo->conn = array(
            'dsn'  => $this->_dsn,
            'user' => $this->_config['user'],
            'pass' => $this->_config['pass'],
            'type' => 'single',
            'key'  => null,
        );
        
        // post-connection tasks
        $this->_postConnect();
    }
    
    /**
     * 
     * After connection, set various connection attributes.
     * 
     * @return void
     * 
     */
    protected function _postConnect()
    {
        // always emulate prepared statements; this is faster, and works
        // better with CREATE, DROP, ALTER statements.  requires PHP 5.1.3
        // or later. note that we do this *first* (before using exceptions)
        // because not all adapters support it.
        $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        
        // always use exceptions
        $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        // force names to lower case
        $this->_pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
    }
    
    /**
     * 
     * Closes the database connection.
     * 
     * This isn't generally necessary as PHP will automatically close the
     * connection in the end of the script execution, but it can be useful
     * to free resources when a script needs to connect tomultiple databases
     * in sequence.
     * 
     * @return void
     * 
     */
    public function disconnect()
    {
        $this->_pdo = null;
    }
    
    /**
     * 
     * Prepares and executes an SQL statement, optionally binding values
     * to named parameters in the statement.
     * 
     * This is the most-direct way to interact with the database; you
     * pass an SQL statement to the method, then the adapter uses
     * [[php::PDO | ]] to execute the statement and return a result.
     * 
     * {{code: php
     *     $sql = Solar::factory('Solar_Sql');
     * 
     *     // $result is a PDOStatement
     *     $result = $sql->query('SELECT * FROM table');
     * }}
     * 
     * To help prevent SQL injection attacks, you should **always** quote
     * the values used in a direct query. Use [[Solar_Sql_Adapter::quote() | quote()]],
     * [[Solar_Sql_Adapter::quoteInto() | quoteInto()]], or 
     * [[Solar_Sql_Adapter::quoteMulti() | quoteMulti()]] to accomplish this.
     * Even easier, use the automated value binding provided by the query() 
     * method:
     * 
     * {{code: php
     *     // BAD AND SCARY:
     *     $result = $sql->query('SELECT * FROM table WHERE foo = $bar');
     *     
     *     // Much much better:
     *     $result = $sql->query(
     *         'SELECT * FROM table WHERE foo = :bar',
     *         array('bar' => $bar)
     *     );
     * }}
     * 
     * Note that adapters provide convenience methods to automatically quote
     * values on common operations:
     * 
     * - [[Solar_Sql_Adapter::insert()]]
     * - [[Solar_Sql_Adapter::update()]]
     * - [[Solar_Sql_Adapter::delete()]]
     * 
     * Additionally, the [[Solar_Sql_Select]] class is dedicated to
     * safely creating portable SELECT statements, so you may wish to use that
     * instead of writing literal SELECTs.
     * 
     * 
     * Automated Binding of Values in PHP 5.2.1 and Later
     * --------------------------------------------------
     * 
     * With PDO in PHP 5.2.1 and later, we can no longer just throw an array
     * of data at the statement for binding. We now need to bind values
     * specifically to their respective placeholders.
     * 
     * In addition, we can't bind one value to multiple identical named
     * placeholders; we need to bind that same value multiple times. So if
     * `:foo` is used three times, PDO uses `:foo` the first time, `:foo2` the
     * second time, and `:foo3` the third time.
     * 
     * This query() method examins the statement for all `:name` placeholders
     * and attempts to bind data from the `$data` array.  The regular-expression
     * it uses is a little braindead; it cannot tell if the :name placeholder
     * is literal text or really a place holder.
     * 
     * As such, you should *either* use the `$data` array for named-placeholder
     * value binding at query() time, *or* bind-as-you-go when building the 
     * statement, not both.  If you do, you are on your own to make sure
     * that nothing looking like a `:name` placeholder exists in the literal text.
     * 
     * Question-mark placeholders are not supported for automatic value
     * binding at query() time.
     * 
     * @param string $stmt The text of the SQL statement, optionally with
     * named placeholders.
     * 
     * @param array $data An associative array of data to bind to the named
     * placeholders.
     * 
     * @return PDOStatement
     * 
     */
    public function query($stmt, $data = array())
    {
        $this->connect();
        
        // prepre the statement and bind data to it
        $prep = $this->_prepare($stmt);
        $this->_bind($prep, $data);
        
        // now try to execute
        try {
            $prep->execute();
        } catch (PDOException $e) {
            echo '<pre>';
            throw new Exception('Query Failed:' . var_export(array(
                'pdo_code'  => $e->getCode(),
                'pdo_text'  => $e->getMessage(),
                'stmt'      => $stmt,
                'data'      => $data,
                'pdo_trace' => $e->getTraceAsString(),
            ), true));
            echo '</pre>';
        }
        
        // done!
        return $prep;
    }
    
    /**
     * 
     * Prepares an SQL query as a PDOStatement object.
     * 
     * @param string $stmt The text of the SQL statement, optionally with
     * named placeholders.
     * 
     * @return PDOStatement
     * 
     */
    protected function _prepare($stmt)
    {
        // prepare the statment
        try {
            $prep = $this->_pdo->prepare($stmt);
            $prep->conn = $this->_pdo->conn;
        } catch (PDOException $e) {
            throw new Exception('Prepare Failed', array(
                'pdo_code'  => $e->getCode(),
                'pdo_text'  => $e->getMessage(),
                'stmt'      => $stmt,
                'pdo_trace' => $e->getTraceAsString(),
            ));
        }
        
        return $prep;
    }
    
    /**
     * 
     * Binds an array of scalars as values into a prepared PDOStatment.
     * 
     * Array element values that are themselves arrays will not be bound
     * correctly, because PDO expects scalar values only.
     * 
     * @param PDOStatement $prep The prepared PDOStatement.
     * 
     * @param array $data The scalar values to bind into the PDOStatement.
     * 
     * @return void
     * 
     */
    protected function _bind($prep, $data)
    {
        // was data passed for binding?
        if (! $data) {
            return;
        }
            
        // find all :placeholder matches.  note that this is a little
        // brain-dead; it will find placeholders in literal text, which
        // will cause errors later.  so in general, you should *either*
        // bind at query time *or* bind as you go, not both.
        preg_match_all(
            "/\W:([a-zA-Z_][a-zA-Z0-9_]*)/m",
            $prep->queryString . "\n",
            $matches
        );
        
        // bind values to placeholders, repeating as needed
        $repeat = array();
        foreach ($matches[1] as $key) {
            
            // only attempt to bind if the data key exists.
            // this allows for nulls and empty strings.
            if (! array_key_exists($key, $data)) {
                // skip it
                continue;
            }
        
            // what does PDO expect as the placeholder name?
            if (empty($repeat[$key])) {
                // first time is ":foo"
                $repeat[$key] = 1;
                $name = $key;
            } else {
                // repeated times of ":foo" are treated by PDO as
                // ":foo2", ":foo3", etc.
                $repeat[$key] ++;
                $name = $key . $repeat[$key];
            }
           
            if (is_int($data[$key])) {
                $param = PDO::PARAM_INT;
            } else if (is_bool($data[$key])) {
                $param = PDO::PARAM_BOOL;
            } else if (is_null($data[$key])) {
                $param = PDO::PARAM_NULL;
            } else if (is_string($data[$key])) {
                $param = PDO::PARAM_STR;
            } else {
                $param = false;
            }

            // bind the value to the placeholder name
            $prep->bindValue($name, $data[$key], $param);
        }
    }
    
    // -----------------------------------------------------------------
    // 
    // Quoting
    // 
    // -----------------------------------------------------------------
    
    /**
     * 
     * Safely quotes a value for an SQL statement.
     * 
     * If an array is passed as the value, the array values are quoted
     * and then returned as a comma-separated string; this is useful 
     * for generating IN() lists.
     * 
     * {{code: php
     *     $sql = Solar::factory('Solar_Sql');
     *     
     *     $safe = $sql->quote('foo"bar"');
     *     // $safe == "'foo\"bar\"'"
     *     
     *     $safe = $sql->quote(array('one', 'two', 'three'));
     *     // $safe == "'one', 'two', 'three'"
     * }}
     * 
     * @param mixed $val The value to quote.
     * 
     * @return string An SQL-safe quoted value (or a string of 
     * separated-and-quoted values).
     * 
     */
    public function quote($val)
    {
        if (is_array($val)) {
            // quote array values, not keys, then combine with commas.
            foreach ($val as $k => $v) {
                $val[$k] = $this->quote($v);
            }
            return implode(', ', $val);
        } else {
            // quote all other scalars, including numerics
            $this->connect();
            return $this->_pdo->quote($val);
        }
    }
    
    /**
     * 
     * Quotes a value and places into a piece of text at a placeholder; the
     * placeholder is a question-mark.
     * 
     * {{code: php
     *      $sql = Solar::factory('Solar_Sql');
     *      
     *      // replace one placeholder
     *      $text = "WHERE date >= ?";
     *      $data = "2005-01-01";
     *      $safe = $sql->quoteInto($text, $data);
     *      // => "WHERE date >= '2005-01-02'"
     *      
     *      // replace multiple placeholders
     *      $text = "WHERE date BETWEEN ? AND ?";
     *      $data = array("2005-01-01", "2005-01-31");
     *      $safe = $sql->quoteInto($text, $data);
     *      // => "WHERE date BETWEEN '2005-01-01' AND '2005-01-31'"
     * 
     *      // single placeholder with array value
     *      $text = "WHERE foo IN (?)";
     *      $data = array('a', 'b', 'c');
     *      $safe = $sql->quoteInto($text, $data);
     *      // => "WHERE foo IN ('a', 'b', 'c')"
     *      
     *      // multiple placeholders and array values
     *      $text = "WHERE date >= ? AND foo IN (?)";
     *      $data = array('2005-01-01, array('a', 'b', 'c'));
     *      $safe = $sql->quoteInto($text, $data);
     *      // => "WHERE date >= '2005-01-01' AND foo IN ('a', 'b', 'c')"
     * }}
     * 
     * @param string $text The text with placeholder(s).
     * 
     * @param mixed $data The data value(s) to quote.
     * 
     * @return mixed An SQL-safe quoted value (or string of separated values)
     * placed into the orignal text.
     * 
     * @see quote()
     * 
     */
    public function quoteInto($text, $data)
    {
        // how many question marks are there?
        $count = substr_count($text, '?');
        if (! $count) {
            // no replacements needed
            return $text;
        }
        
        // only one replacement?
        if ($count == 1) {
            $data = $this->quote($data);
            $text = str_replace('?', $data, $text);
            return $text;
        }
        
        // more than one replacement; force values to be an array, then make 
        // sure we have enough values to replace all the placeholders.
        settype($data, 'array');
        if (count($data) < $count) {
            // more placeholders than values
            throw $this->_exception('ERR_NOT_ENOUGH_VALUES', array(
                'text'  => $text,
                'data'  => $data,
            ));
        }
        
        // replace each placeholder with a quoted value
        $offset = 0;
        foreach ($data as $val) {
            // find the next placeholder
            $pos = strpos($text, '?', $offset);
            if ($pos === false) {
                // no more placeholders, exit the data loop
                break;
            }
            
            // replace this question mark with a quoted value
            $val  = $this->quote($val);
            $text = substr_replace($text, $val, $pos, 1);
            
            // update the offset to move us past the quoted value
            $offset = $pos + strlen($val);
        }
        
        return $text;
    }
    
    /**
     * 
     * Quote multiple text-and-value pieces.
     * 
     * The placeholder is a question-mark; all placeholders will be replaced
     * with the quoted value.   For example ...
     * 
     * {{code: php
     *     $sql = Solar::factory('Solar_Sql');
     *     
     *     $list = array(
     *          "WHERE date > ?"   => '2005-01-01',
     *          "  AND date < ?"   => '2005-02-01',
     *          "  AND type IN(?)" => array('a', 'b', 'c'),
     *     );
     *     $safe = $sql->quoteMulti($list);
     *     
     *     // $safe = "WHERE date > '2005-01-02'
     *     //          AND date < 2005-02-01
     *     //          AND type IN('a','b','c')"
     * }}
     * 
     * @param array $list A series of key-value pairs where the key is
     * the placeholder text and the value is the value to be quoted into
     * it.  If the key is an integer, it is assumed that the value is
     * piece of literal text to be used and not quoted.
     * 
     * @param string $sep Return the list pieces separated with this string
     * (for example ' AND '), default null.
     * 
     * @return string An SQL-safe string composed of the list keys and
     * quoted values.
     * 
     */
    public function quoteMulti($list, $sep = null)
    {
        $text = array();
        foreach ((array) $list as $key => $val) {
            if (is_int($key)) {
                // integer $key means a literal phrase and no value to
                // be bound into it
                $text[] = $val;
            } else {
                // string $key means a phrase with a placeholder, and
                // $val should be bound into it.
                $text[] = $this->quoteInto($key, $val); 
            }
        }
        
        // return the condition list
        $result = implode($sep, $text);
        return $result;
    }
    
    /**
     * 
     * Quotes a single identifier name (table, table alias, table column, 
     * index, sequence).  Ignores empty values.
     * 
     * If the name contains ' AS ', this method will separately quote the
     * parts before and after the ' AS '.
     * 
     * If the name contains a space, this method will separately quote the
     * parts before and after the space.
     * 
     * If the name contains a dot, this method will separately quote the
     * parts before and after the dot.
     * 
     * @param string|array $spec The identifier name to quote.  If an array,
     * quotes each element in the array as an identifier name.
     * 
     * @return string|array The quoted identifier name (or array of names).
     * 
     * @see _quoteName()
     * 
     */
    public function quoteName($spec)
    {
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $spec[$key] = $this->quoteName($val);
            }
            return $spec;
        }
        
        // no extraneous spaces
        $spec = trim($spec);
        
        // `original` AS `alias` ... note the 'rr' in strripos
        $pos = strripos($spec, ' AS ');
        if ($pos) {
            // recurse to allow for "table.col"
            $orig  = $this->quoteName(substr($spec, 0, $pos));
            // use as-is
            $alias = $this->_quoteName(substr($spec, $pos + 4));
            return "$orig AS $alias";
        }
        
        // `original` `alias`
        $pos = strrpos($spec, ' ');
        if ($pos) {
            // recurse to allow for "table.col"
            $orig = $this->quoteName(substr($spec, 0, $pos));
            // use as-is
            $alias = $this->_quoteName(substr($spec, $pos + 1));
            return "$orig $alias";
        }
        
        // `table`.`column`
        $pos = strrpos($spec, '.');
        if ($pos) {
            // use both as-is
            $table = $this->_quoteName(substr($spec, 0, $pos));
            $col   = $this->_quoteName(substr($spec, $pos + 1));
            return "$table.$col";
        }
        
        // `name`
        return $this->_quoteName($spec);
    }
    
    /**
     * 
     * Quotes an identifier name (table, index, etc); ignores empty values and
     * values of '*'.
     * 
     * @param string $name The identifier name to quote.
     * 
     * @return string The quoted identifier name.
     * 
     * @see quoteName()
     * 
     */
    protected function _quoteName($name)
    {
        $name = trim($name);
        if ($name == '*') {
            return $name;
        } else {
            return $this->_ident_quote_prefix
                 . $name
                 . $this->_ident_quote_suffix;
        }
    }
    
    /**
     * 
     * Quotes all fully-qualified identifier names ("table.col") in a string,
     * typically an SQL snippet for a SELECT clause.
     * 
     * Does not quote identifier names that are string literals (i.e., inside
     * single or double quotes).
     * 
     * Looks for a trailing ' AS alias' and quotes the alias as well.
     * 
     * @param string|array $spec The string in which to quote fully-qualified
     * identifier names to quote.  If an array, quotes names in each element
     * in the array.
     * 
     * @return string|array The string (or array) with names quoted in it.
     * 
     * @see _quoteNamesIn()
     * 
     */
    public function quoteNamesIn($spec)
    {
        if (is_array($spec)) {
            foreach ($spec as $key => $val) {
                $spec[$key] = $this->quoteNamesIn($val);
            }
            return $spec;
        }
        
        // single and double quotes
        $apos = "'";
        $quot = '"';
        
        // look for ', ", \', or \" in the string.
        // match closing quotes against the same number of opening quotes.
        $list = preg_split(
            "/(($apos+|$quot+|\\$apos+|\\$quot+).*?\\2)/",
            $spec,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );
        
        // concat the pieces back together, quoting names as we go.
        $spec = null;
        $last = count($list) - 1;
        foreach ($list as $key => $val) {
            
            // skip elements 2, 5, 8, 11, etc. as artifacts of the back-
            // referenced split; these are the trailing/ending quote
            // portions, and already included in the previous element.
            // this is the same as every third element from zero.
            if (($key+1) % 3 == 0) {
                continue;
            }
            
            // is there an apos or quot anywhere in the part?
            $is_string = strpos($val, $apos) !== false ||
                         strpos($val, $quot) !== false;
            
            if ($is_string) {
                // string literal
                $spec .= $val;
            } else {
                // sql language.
                // look for an AS alias if this is the last element.
                if ($key == $last) {
                    // note the 'rr' in strripos
                    $pos = strripos($val, ' AS ');
                    if ($pos) {
                        // quote the alias name directly
                        $alias = $this->_quoteName(substr($val, $pos + 4));
                        $val = substr($val, 0, $pos) . " AS $alias";
                    }
                }
                
                // now quote names in the language.
                $spec .= $this->_quoteNamesIn($val);
            }
        }
        
        // done!
        return $spec;
    }
    
    /**
     * 
     * Quotes all fully-qualified identifier names ("table.col") in a string.
     * 
     * @param string|array $text The string in which to quote fully-qualified
     * identifier names to quote.  If an array, quotes names in  each 
     * element in the array.
     * 
     * @return string|array The string (or array) with names quoted in it.
     * 
     * @see quoteNamesIn()
     * 
     */
    protected function _quoteNamesIn($text)
    {
        $word = "[a-z_][a-z0-9_]+";
        
        $find = "/(\\b)($word)\\.($word)(\\b)/i";
        
        $repl = '$1'
              . $this->_ident_quote_prefix
              . '$2'
              . $this->_ident_quote_suffix
              . '.'
              . $this->_ident_quote_prefix
              . '$3'
              . $this->_ident_quote_suffix
              . '$4'
              ;
              
        $text = preg_replace($find, $repl, $text);
        
        return $text;
    }
    
    
    // -----------------------------------------------------------------
    // 
    // Auto-increment and sequence reading.
    // 
    // -----------------------------------------------------------------
    
    /**
     * 
     * Get the last auto-incremented insert ID from the database.
     * 
     * @param string $table The table name on which the auto-increment occurred.
     * 
     * @param string $col The name of the auto-increment column.
     * 
     * @return int The last auto-increment ID value inserted to the database.
     * 
     */
    public function lastInsertId($table = null, $col = null)
    {
        $this->connect();
        return $this->_pdo->lastInsertId();
    }
    
    /**
     * delete
     * Insert description here
     *
     * @param $table
     * @param $where
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public function delete($table, $where)
    {
        if ($where) {
            $where = $this->quoteMulti($where, ' AND ');
            $where = $this->quoteNamesIn($where);
        }
        
        $table = $this->quoteName($table);
        $result = $this->query("DELETE FROM $table WHERE $where");
        return $result->rowCount();
    }
    
    /**
     * insert
     * Insert description here
     *
     * @param $table
     * @param $data
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public function insert($table, $data)
    {
        // the base statement
        $table = $this->quoteName($table);
        $stmt = "INSERT INTO $table ";
        
        // col names come from the array keys
        $keys = array_keys($data);
        
        // quote the col names
        $cols = array();
        foreach ($keys as $key) {
            $cols[] = $this->quoteName($key);
        }
        
        // add quoted col names
        $stmt .= '(' . implode(', ', $cols) . ') ';
        
        // add value placeholders (use unquoted key names)
        $stmt .= 'VALUES (:' . implode(', :', $keys) . ')';
        
        // execute the statement
        $result = $this->query($stmt, $data);
        return $result->rowCount();
    }
    
    /**
     * update
     * Insert description here
     *
     * @param $table
     * @param $data
     * @param $where
     *
     * @return
     *
     * @access
     * @static
     * @see
     * @since
     */
    public function update($table, $data, $where)
    {
        // the base statement
        $table = $this->quoteName($table);
        $stmt = "UPDATE $table SET ";
        
        // add "col = :col" pairs to the statement
        $tmp = array();
        foreach ($data as $col => $val) {
            $tmp[] = $this->quoteName($col) . " = :$col";
        }
        $stmt .= implode(', ', $tmp);
        
        // add the where clause
        if ($where) {
            $where = $this->quoteMulti($where, ' AND ');
            $where = $this->quoteNamesIn($where);
            $stmt .= " WHERE $where";
        }
        
        // execute the statement
        $result = $this->query($stmt, $data);
        return $result->rowCount();
    }
    
    public function fetchAll($stmt, $data = array(), $fetch_mode = PDO::FETCH_ASSOC)
    {
        // perform query
        $sth = $this->query($stmt, $data);
        return $sth->fetchAll($fetch_mode);
    }

    public function fetchOne($stmt, $data = array(), $fetch_mode = PDO::FETCH_ASSOC)
    {
        // perform query
        $sth = $this->query($stmt, $data);
        return $sth->fetch($fetch_mode);
    }
    
    public function fetchAllTranslations()
    {
        $sql = "SELECT * FROM ot_translations";
        return $this->fetchAll($sql);
    }
    
    public function fetchPageTranslation($url, $native_code, $native_text)
    {
        $sql = "SELECT * 
                FROM ot_translations
                WHERE url = :url
                    AND native_locale_code = :ncode
                    AND native_text = :ntext";
        
        $data = array(
            'url' => $url,
            'ncode' => $native_code,
            'ntext' => $native_text,
        );
        return $this->fetchAll($sql, $data);
    }
    
    public function insertEntry($page, $native_code, $native_text, $translated_code, $translated_text, $ip)
    {
        $data = array(
            'url' => $page,
            'native_locale_code' => $native_code,
            'native_text' => $native_text,
            'translated_locale_code' => $translated_code,
            'translated_text' => $translated_text,
            'ip' => ip2long($ip),
        );
        return $this->insert('ot_translations', $data);
    }
    
    public function fetchById($tid) 
    {
        $sql = "SELECT * 
                FROM ot_translations
                WHERE translation_id = :tid";
        
        $data = array(
            'tid' => $tid,
        );
        return $this->fetch($sql, $data);
    }
    
    public function voteUpById($tid, $ip)
    {
        if (!$this->isInt($tid)) {
            return false;
        }
        
        $t = $this->fetchById($tid);
        
        if (!$t || long2ip($t['ip']) == $ip) {
            return false;
        }
        
        $data = array(
            'vote_up' = $t['vote_up'] + 1,
        );
        
        $where = array('translation_id = ?' => array($tid));
        
        $this->update('ot_translations', $data, $where);
    }
    
    public function voteDownById($tid, $ip)
    {
        if (!$this->isInt($tid)) {
            return false;
        }
        
        $t = $this->fetchById($tid);
        
        if (!$t || long2ip($t['ip']) == $ip) {
            return false;
        }
        
        $data = array(
            'vote_down' = $t['vote_down'] + 1,
        );
        
        $where = array('translation_id = ?' => array($tid));
        
        $this->update('ot_translations', $data, $where);
    }
    
    public function isInt($value) 
    {
        if (is_int($value)) {
            return true;
        }
        
        // otherwise, must be numeric, and must be same as when cast to int
        return is_numeric($value) && $value == (int) $value;
    }
}
