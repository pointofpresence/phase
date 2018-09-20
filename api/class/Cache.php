<?php

class Cache
{
    const CACHE_TIME = 360;

    protected $_cachePath;

    public function __construct($path = NULL)
    {
        $this->_cachePath =
            ($path === NULL
                ? __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'cache'
                : $path
            ) . DIRECTORY_SEPARATOR;

        if (!is_dir($this->_cachePath)) {
            mkdir($this->_cachePath);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     * Not supported by file-based caching
     *
     * @return    mixed
     */
    public function cacheInfo()
    {
        return File::getDirFileInfo($this->_cachePath);
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the Cache
     *
     * @return    boolean        false on failure/true on success
     */
    public function clean()
    {
        return File::deleteFiles($this->_cachePath);
    }

    // ------------------------------------------------------------------------

    /**
     * Delete from Cache
     *
     * @param    mixed $id unique identifier of item in cache
     *
     * @return   boolean    true on success/false on failure
     */
    public function delete($id)
    {
        if (file_exists($this->_cachePath . $id) && is_writable($this->_cachePath . $id)) {
            return unlink($this->_cachePath . $id);
        }

        return FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Fetch from cache
     *
     * @param    mixed $id unique key id
     *
     * @return   mixed  data on success/false on failure
     */
    public function get($id)
    {
        if (!file_exists($this->_cachePath . $id)) {
            return FALSE;
        }

        $data = File::readFile($this->_cachePath . $id);
        $data = unserialize($data);

        if (time() > $data['time'] + $data['ttl']) {
            unlink($this->_cachePath . $id);

            return FALSE;
        }

        return $data['data'];
    }

    // ------------------------------------------------------------------------

    /**
     * Get Cache Metadata
     *
     * @param    mixed $id key to get cache metadata on
     *
     * @return   mixed        FALSE on failure, array on success.
     */
    public function getMetadata($id)
    {
        if (!file_exists($this->_cachePath . $id)) {
            return FALSE;
        }

        $data = File::readFile($this->_cachePath . $id);
        $data = unserialize($data);

        if (is_array($data)) {
            $mtime = filemtime($this->_cachePath . $id);

            if (!isset($data['ttl'])) {
                return FALSE;
            }

            return [
                'expire' => $mtime + $data['ttl'],
                'mtime'  => $mtime
            ];
        }

        return FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Is supported
     *
     * Check to see that the cache directory is indeed writable
     *
     * @return boolean
     */
    public function isSupported()
    {
        return File::isReallyWritable($this->_cachePath);
    }

    /**
     * Save into cache
     *
     * @param    string $id     unique key
     * @param    mixed  $data   data to store
     * @param    int    $ttl    length of time (in seconds) the cache is valid
     *                          - Default is [self::CACHE_TIME] seconds
     *
     * @return   boolean    true on success/false on failure
     */
    public function save($id, $data, $ttl = self::CACHE_TIME)
    {
        $contents = [
            'time' => time(),
            'ttl'  => $ttl,
            'data' => $data
        ];

        if (File::writeFile($this->_cachePath . $id, serialize($contents))) {
            @chmod($this->_cachePath . $id, 0777);

            return TRUE;
        }

        return FALSE;
    }
}