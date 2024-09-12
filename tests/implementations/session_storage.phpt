<?php
    require __DIR__ . "/../bootstrap.php";

    use Tester\Assert;


    $dir = __DIR__ . "/../temp";
    if (!file_exists($dir)) {
        mkdir($dir);
    }

    $storage = new \Stolfam\DataStorage\Impl\SessionStorage("test", "dev_");

    $key = "key1";
    $falseKey = "key2";
    $data = 12345;

    Assert::same(true, $storage->save($key, $data));
    Assert::same(false, $storage->save($key, $data, false));
    Assert::same($data, $storage->load($key));
    Assert::same(true, $storage->exists($key));
    Assert::same(false, $storage->exists($falseKey));
    Assert::same(true, $storage->remove($key));
    Assert::same(false, $storage->remove($falseKey));