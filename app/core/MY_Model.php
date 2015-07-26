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

    protected $returnType = 'object';

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

    public function getBy($key, $value = null, $protect = null)
    {
        if ($this->isIterable($value)) {
            $this->db->where_in($key, $value, $protect);
        } else {
            $this->db->where($key, $value, $protect);
        }
        
        return $this->db->get($this->table)->row(0, $this->returnType);
    }

    private function isIterable($var)
    {
        return (is_array($var) || $var instanceof \ArrayAccess);
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
}
