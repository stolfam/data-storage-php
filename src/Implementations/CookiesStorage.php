<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage\Impl;

    use Stolfam\DataStorage\CipherHelper;
    use Stolfam\DataStorage\IDataStorage;


    class CookiesStorage implements IDataStorage
    {
        public const DEFAULT_EXPIRATION = 5184000;

        public int $expiration;
        public string $prefix;

        public ?CipherHelper $cipher = null;

        /** @var string[] */
        public array $errors = [];

        /**
         * @param int    $expiration
         * @param string $prefix
         */
        public function __construct(int $expiration = self::DEFAULT_EXPIRATION, string $prefix = "")
        {
            $this->expiration = $expiration;
            $this->prefix = $prefix;
        }

        public function setCipher(CipherHelper $cipher): void
        {
            $this->cipher = $cipher;
        }

        public function save(string $key, mixed $data, bool $overwrite = true): bool
        {
            $key = $this->prefix . $key;
            if (!$overwrite && isset($_COOKIE[$key])) {
                return false;
            }

            $data = serialize($data);
            $data = base64_encode($data);

            if ($this->cipher != null) {
                $data = $this->cipher->crypt($data);
            }

            return setcookie($key, $data, (time() + $this->expiration), "/", ($_SERVER['HTTP_HOST'] ?? ""));
        }

        public function load(string $key): mixed
        {
            $key = $this->prefix . $key;
            if (isset($_COOKIE[$key])) {
                $data = null;
                try {
                    $rawData = $_COOKIE[$key];
                    if ($this->cipher != null) {
                        $rawData = $this->cipher->decrypt($rawData);
                    }

                    $data = base64_decode($rawData);
                } catch (\Throwable $t) {
                    $this->errors[] = $t->getMessage();

                    return null;
                }
                $data = @unserialize($data);
                if (empty($data)) {
                    return null;
                }

                return $data;
            }

            return null;
        }

        public function exists(string $key): bool
        {
            $key = $this->prefix . $key;

            return isset($_COOKIE[$key]);
        }

        public function remove(string $key): bool
        {
            return setcookie($key, "", 0, "");
        }
    }