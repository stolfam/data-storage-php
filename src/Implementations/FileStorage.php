<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage\Impl;

    use Stolfam\DataStorage\IDataStorage;


    class FileStorage implements IDataStorage
    {
        public readonly string $dir;
        public readonly string $prefix;
        public readonly string $namespace;

        /**
         * @param string $dir
         * @param string $prefix
         */
        public function __construct(string $dir, string $namespace = null, string $prefix = "")
        {
            $this->dir = $dir;
            $this->prefix = $prefix;
            $this->namespace = $namespace;

            if (!file_exists($dir)) {
                mkdir($dir);
            }

            if ($namespace != null) {
                $dir .= "/" . $namespace;
                if (!file_exists($dir)) {
                    mkdir($dir);
                }
            }
        }

        public function save(string $key, mixed $data, bool $overwrite = true): bool
        {
            $key = $this->prefix . $key;

            if (!$overwrite && file_exists($this->dir . "/" . $key)) {
                return false;
            }

            $data = serialize($data);

            return (bool) file_put_contents($this->dir . "/" . $key, $data);
        }

        public function load(string $key): mixed
        {
            $key = $this->prefix . $key;

            if (file_exists($this->dir . "/" . $key)) {
                $data = file_get_contents($this->dir . "/" . $key);

                return unserialize($data);
            }

            return null;
        }

        public function exists(string $key): bool
        {
            $key = $this->prefix . $key;

            return file_exists($this->dir . "/" . $key);
        }

        public function remove(string $key): bool
        {
            $key = $this->prefix . $key;
            if (file_exists($this->dir . "/" . $key)) {
                return @unlink($this->dir . "/" . $key);
            }

            return false;
        }
    }