<?php
    declare(strict_types=1);

    namespace Stolfam\DataStorage\Impl;

    use Stolfam\DataStorage\IDataStorage;


    class CookiesStorage implements IDataStorage
    {
        public const DEFAULT_EXPIRATION = 5184000;

        public readonly int $expiration;
        public readonly string $prefix;

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


        public function save(string $key, mixed $data, bool $overwrite = true): bool
        {
            $key = $this->prefix . $key;
            if (!$overwrite && isset($_COOKIE[$key])) {
                return false;
            }

            $data = serialize($data);
            $data = base64_encode($data);

            return setcookie($key, $data, (time() + $this->expiration), "/", ($_SERVER['HTTP_HOST'] ?? ""));
        }

        public function load(string $key): mixed
        {
            $key = $this->prefix . $key;
            if (isset($_COOKIE[$key])) {
                $data = null;
                try {
                    $data = base64_decode($_COOKIE[$key]);
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