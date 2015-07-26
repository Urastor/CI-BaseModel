<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Base-Model for CodeIgniter
 *
 * This model extends CodeIgniters Model and makes it easier to
 * interact with CodeIgniters DB-Driver.
 * It has basic methods to easy interact with your database, such as
 * get, insert, update or delete.
 * Also it has build in validation, supports database relation and other funky
 * things. (not ready yet)
 *
 * @author Kevin Gerz
 * @copyright 2015 Kevin Gerz
 * @see http://github.com/Urastor/CI-BaseModel Github-Project Site
 * @license MIT License (MIT)
 *         
 */
class MY_Model extends CI_Model
{

    protected $table = '';

    protected $primKey = 'id';

    private $id;

    private $returnType = 'object';

    public function __construct($guessName = false)
    {
        parent::__construct();
        $this->guessTableName($guessName);
    }

    /* Gets by id */
    public function get()
    {
        if (! $this->hasID())
            return null;
        
        return $this->getBy($this->primKey, $this->id);
    }

    public function getBy($key, $value = null)
    {
        $this->setWhere($key, $value);
        return $this->getRow();
    }

    /**
     * Assumes an array, with key => value relation.
     * Important: The value needs to be an array, with at least one
     * array in it, to anticipate the right where method.
     * An example is up on github soon (TODO)
     *
     * @param array|ArrayAccess $list            
     */
    public function getByList($list)
    {
        $this->setWhere($list, null);
        return $this->getRow();
    }

    public function getRow($index = 0)
    {
        return $this->db->get($this->table)->row($index, $this->returnType);
    }

    public function getResult()
    {
        return $this->db->get($this->table)->result($this->returnType);
    }

    private function isIterable($var)
    {
        return (is_array($var) || $var instanceof \ArrayAccess);
    }

    private function setWhere($par1, $par2)
    {
        if (is_null($par2) && $this->isIterable($par1)) {
            // by list
            foreach ($par1 as $field => $val) {
                // Check for not or or in second array
                $not = false;
                $or = false;
                
                if ($this->isIterable($val[1])) {
                    $not = (isset($val[1]['useNot']) && $val[1]['useNot']);
                    $or = (isset($val[1]['useOr']) && $val[1]['useOr']);
                }
                
                // Evaluate and call where methods
                if ($this->isIterable($val[0])) {
                    if ($not && $or)
                        $method = 'or_where_not_in';
                    elseif ($not && ! $or)
                        $method = 'or_where_in';
                    elseif (! $not && $or)
                        $method = 'where_not_in';
                    else
                        $method = 'where_in';
                } else {
                    $method = ($or) ? 'or_where' : 'where';
                }
                
                $this->db->{$method}($field, $val);
            }
        } elseif (is_null($par2)) {
            // where string
            $this->db->where($par1);
        } else {
            // just a where or where_in
            if ($this->isIterable($par2))
                $this->db->where_in($par1, $par2);
            else
                $this->db->where($par1, $par2);
        }
    }

    private function guessTableName($guess)
    {
        if ($guess) {
            if (empty($this->table) || ! is_string($this->table)) {
                $modelName = get_class($this);
                $this->table = strtolower(str_replace('Model', '', $modelName));
            }
        }
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = (int) $id;
    }

    public function hasID()
    {
        return ($this->id !== null);
    }

    public function getReturnType()
    {
        return $this->returnType;
    }

    public function setReturnType($type)
    {
        $type = strtolower(trim($type));
        if (in_array($type, [
            'object',
            'array'
        ]) || class_exists($type))
            $this->returnType = $type;
    }
}
