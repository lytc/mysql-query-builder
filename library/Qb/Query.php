<?php

namespace Qb;

/**
 *
 */
class Query extends \PDO
{
    /**
     * @param string|array|Arguments [$from]
     * @param string|array [$column]
     * @param string|array [$where]
     * @param string|array|Arguments [$orderBy]
     * @param int [$limit]
     * @param int [$offset]
     * @return Select
     */
    public static function select($from = null, $column = null, $where = null, $orderBy = null, $limit = null, $offset = null)
    {
        $select = new Select();

        if ($from) {
            $select->from($from);
        }

        if ($where) {
            $select->where($where);
        }

        if ($orderBy) {
            $select->orderBy($orderBy);
        }

        if ($limit) {
            $select->limit($limit);
        }

        if ($offset) {
            $select->offset($offset);
        }

        return $select;
    }

    /**
     * @param string [$into]
     * @param string|array|Select [$value]
     * @return Insert
     */
    public static function insert($into = null, $value = null)
    {
        $insert = new Insert();

        if ($into) {
            $insert->into($into);
        }

        if ($value) {
            $insert->value($value);
        }

        return $insert;
    }

    /**
     * @param string|array|Arguments [$from]
     * @param array [$value]
     * @param string|array [$where]
     * @param string|array|Arguments [$orderBy]
     * @param int [$limit]
     * @return Update
     */
    public static function update($from = null, array $value = null, $where = null, $orderBy = null, $limit = null)
    {
        $update = new Update();

        if ($from) {
            $update->from($from);
        }

        if ($value) {
            $update->value($value);
        }

        if ($where) {
            $update->where($where);
        }

        if ($orderBy) {
            $update->orderBy($orderBy);
        }

        if ($limit) {
            $update->limit($limit);
        }

        return $update;
    }

    /**
     * @param string|array|Arguments [$from]
     * @param string|array [$where]
     * @param string|array|Arguments [$orderBy]
     * @param int [$limit]
     * @return Delete
     */
    public static function delete($from = null, $where = null, $orderBy = null, $limit = null)
    {
        $delete = new Delete();

        if ($from) {
            $delete->from($from);
        }

        if ($where) {
            $delete->where($where);
        }

        if ($orderBy) {
            $delete->orderBy($orderBy);
        }

        if ($limit) {
            $delete->limit($limit);
        }

        return $delete;
    }
}