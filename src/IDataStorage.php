<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage;

    interface IDataStorage
    {
        public function save(string $key, mixed $data, bool $overwrite = true): bool;

        public function load(string $key): mixed;

        public function exists(string $key): bool;

        public function remove(string $key): bool;
    }