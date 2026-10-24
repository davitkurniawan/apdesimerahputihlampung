<?php
// XOSID Neural Database Helper Classes
// Loaded exactly once to prevent redeclaration errors

class XOSIDConfigRepository implements \Illuminate\Contracts\Config\Repository, \ArrayAccess {
    public $is_xosid_decorated = true;
    protected $config;
    public function __construct($config) {
        $this->config = $config;
    }
    public function has($key) {
        return $this->config->has($key);
    }
    public function get($key, $default = null) {
        $val = $this->config->get($key, $default);
        if ($key === 'database.default' && (empty($val) || $val === null)) {
            return 'default';
        }
        return $val;
    }
    public function set($key, $value = null) {
        if ($key === 'database.default' && (empty($value) || $value === null)) {
            $value = 'default';
        }
        $this->config->set($key, $value);
    }
    public function prepend($key, $value) {
        $this->config->prepend($key, $value);
    }
    public function push($key, $value) {
        $this->config->push($key, $value);
    }
    public function all() {
        return $this->config->all();
    }
    public function offsetExists($offset): bool {
        return $this->config->offsetExists($offset);
    }
    public function offsetGet($offset): mixed {
        return $this->get($offset);
    }
    public function offsetSet($offset, $value): void {
        $this->set($offset, $value);
    }
    public function offsetUnset($offset): void {
        $this->config->offsetUnset($offset);
    }
}

class XOSIDConnectionResolver implements \Illuminate\Database\ConnectionResolverInterface {
    protected $resolver;
    public function __construct($resolver) {
        $this->resolver = $resolver;
    }
    public function connection($name = null) {
        if ($name === '' || $name === null) {
            $name = 'default';
        }
        try {
            return $this->resolver->connection($name);
        } catch (\Throwable $e) {
            try {
                return $this->resolver->connection('default');
            } catch (\Throwable $e2) {
                return $this->resolver->connection();
            }
        }
    }
    public function getDefaultConnection() {
        return $this->resolver->getDefaultConnection() ?: 'default';
    }
    public function setDefaultConnection($name) {
        $this->resolver->setDefaultConnection($name);
    }
}
?>
