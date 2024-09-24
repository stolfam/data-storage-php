<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage;

    final class CipherHelper
    {
        public string $ciphering = "AES-128-CTR";
        public int $initialVector = 1234567891011121;
        public string $cKey = "dev";

        /**
         * @param string $ciphering
         * @param int    $initialVector
         * @param string $cKey
         */
        public function __construct(string $cKey, int $initialVector, string $ciphering = "AES-128-CTR")
        {
            $this->ciphering = $ciphering;
            $this->initialVector = $initialVector;
            $this->cKey = $cKey;
        }

        public function crypt(string $data): string
        {
            return openssl_encrypt($data, $this->ciphering, $this->cKey, 0, (string) $this->initialVector);
        }

        public function decrypt(string $data): string
        {
            return openssl_decrypt($data, $this->ciphering, $this->cKey, 0, (string) $this->initialVector);
        }
    }