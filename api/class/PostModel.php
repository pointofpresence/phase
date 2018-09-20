<?php

/**
 * Class PostModel
 */
class PostModel extends CSqlite3
{
    const STATUS_HIDDEN = 0;
    const STATUS_PUBLISHED = 1;
    //
    protected $_table = 'posts';
    protected $_primaryKey = 'id';

    /**
     * @inheritdoc
     */
    public function update($primaryValue, $item)
    {
        if (is_object($item)) {
            $item = (array)$item;
        }

        $toTrim = ['body', 'teaser', 'title'];

        foreach ($toTrim as $t) {
            if (!empty($item[$t])) {
                $item[$t] = trim($item[$t]);
            }
        }

        $toUnset = ['created', 'id'];

        foreach ($toUnset as $t) {
            if (isset($item[$t])) {
                unset($item[$t]);
            }
        }

        if ($item['teaser'] == '<p><br></p>') {
            $post['teaser'] = NULL;
        }

        if ($item['body'] == '<p><br></p>') {
            $post['body'] = NULL;
        }

        $params = [];

        foreach ($item as $k => $v) {
            $params[] = '`' . $k . '`=' . $this->quote($v);
        }

        $this->ex(
            'UPDATE ' . $this->_table
            . ' SET ' . implode(',', $params)
            . ' WHERE id=?', $primaryValue
        );
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $q = $this->dq(
            "SELECT * FROM " . $this->_table
            . " ORDER BY " . $this->_primaryKey . " DESC"
        );

        $result = [];

        while ($r = $q->fetch_assoc($q)) {
            $result[] = $r;
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->sq("SELECT COUNT(*) FROM " . $this->_table);
    }

    /**
     * @param array $condition
     *
     * @return null
     */
    public function getBy($condition = [])
    {
        if (!$condition) {
            return NULL;
        }

        $params = [];

        foreach ($condition as $k => $v) {
            $params[] = '`' . $k . '`=' . $this->quote($v);
        }

        return $this->sqa(
            "SELECT * FROM " . $this->_table . " WHERE " . implode(',', $params)
        );
    }

    /**
     * @param $item
     *
     * @return array|FALSE
     */
    public function insert($item)
    {
        if (is_object($item)) {
            $item = (array)$item;
        }

        $item['status'] = self::STATUS_PUBLISHED;

        if ($item['teaser'] == '<p><br></p>') {
            $post['teaser'] = NULL;
        }

        if ($item['body'] == '<p><br></p>') {
            $post['body'] = NULL;
        }

        $this->dq(
            "INSERT INTO " . $this->_table
            . " (" . implode(',', array_keys($item)) . ")"
            . " VALUES (" . implode(',', array_fill(0, count($item), '?')) . ")",
            array_values($item));

        $id = $this->last_insert_id();

        return $this->getBy([$this->_primaryKey => $id]);
    }

    public function getPublished()
    {
        $q = $this->dq(
            "SELECT * FROM " . $this->_table
            . " WHERE status = " . self::STATUS_PUBLISHED
            . " ORDER BY " . $this->_primaryKey . " DESC"
        );

        $result = [];

        while ($r = $q->fetch_assoc($q)) {
            $result[] = $r;
        }

        return $result;
    }
}