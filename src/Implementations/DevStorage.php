<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage\Impl;

    use Stolfam\DataStorage\CipherHelper;
    use Stolfam\DataStorage\IDataStorage;


    final class DevStorage implements IDataStorage
    {
        private array $data = [];
        private ?CipherHelper $cipher = null;

        public function setCipher(CipherHelper $cipher): void {
            $this->cipher = $cipher;
        }

        public function save(string $key, mixed $data, bool $overwrite = true): bool
        {
            if (!$overwrite && isset($this->data[$key])) {
                return false;
            }

            $this->data[$key] = serialize($data);

            if ($this->cipher != null) {
                $this->data[$key] = $this->cipher->crypt($this->data[$key]);
            }

            return true;
        }

        public function load(string $key): mixed
        {
            if (isset($this->data[$key])) {
                $data = $this->data[$key];
                if ($this->cipher != null) {
                    $data = $this->cipher->decrypt($this->data[$key]);
                }

                return unserialize($data);
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