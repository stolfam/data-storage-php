<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage\Impl;

    use Stolfam\DataStorage\IDataStorage;


    final class DevStorage implements IDataStorage
    {
        private array $data = [];

        public function save(string $key, mixed $data, bool $overwrite = true): bool
        {
            if (!$overwrite && isset($this->data[$key])) {
                return false;
            }

            $this->data[$key] = serialize($data);

            return true;
        }

        public function load(string $key): mixed
        {
            if (isset($this->data[$key])) {
                return unserialize($this->data[$key]);
            }

            return null;
        }

        public function exists(string $key): bool
        {
            return isset($this->data[$key]);
        }

        public function remove(string $key): bool
        {
            if (isset($this->data[$key])) {
                unset($this->data[$key]);

                return true;
            }

            return false;
        }
    }