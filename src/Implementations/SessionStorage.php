<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage\Impl;

    use Stolfam\DataStorage\IDataStorage;


    class SessionStorage implements IDataStorage
    {
        public string $prefix;
        public string $namespace;

        /**
         * @param string $prefix
         * @param string $namespace
         */
        public function __construct(string $namespace, string $prefix = "")
        {
            $this->prefix = $prefix;
            $this->namespace = $namespace;
        }

        public function save(string $key, mixed $data, bool $overwrite = true): bool
        {
            $key = $this->prefix . $key;
            if (!$overwrite && isset($_SESSION[$this->namespace][$key])) {
                return false;
            }
            $_SESSION[$this->namespace][$key] = serialize($data);

            return true;
        }

        public function load(string $key): mixed
        {
            $key = $this->prefix . $key;
            if (isset($_SESSION[$this->namespace][$key])) {
                return unserialize($_SESSION[$this->namespace][$key]);
            }

            return null;
        }

        public function exists(string $key): bool
        {
            $key = $this->prefix . $key;

            return isset($_SESSION[$this->namespace][$key]);
        }

        public function remove(string $key): bool
        {
            $key = $this->prefix . $key;

            if (isset($_SESSION[$this->namespace][$key])) {
                unset($_SESSION[$this->namespace][$key]);

                return true;
            }

            return false;
        }

    }