<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage\Impl;

    class HashFileStorage extends FileStorage
    {
        public function hashKey(string $key): string
        {
            return md5($key);
        }

        public function save(string $key, mixed $data, bool $overwrite = true): bool
        {
            return parent::save($this->hashKey($key), $data, $overwrite);
        }

        public function load(string $key): mixed
        {
            return parent::load($this->hashKey($key));
        }

        public function exists(string $key): bool
        {
            return parent::exists($this->hashKey($key));
        }

        public function remove(string $key): bool
        {
            return parent::remove($this->hashKey($key));
        }
    }