<?php
    require __DIR__ . "/../bootstrap.php";

    use Tester\Assert;


    $storage = new \Stolfam\DataStorage\Impl\DevStorage();

    $key = "key1";
    $falseKey = "key2";
    $data = 12345;

    $storage->setCipher(new \Stolfam\DataStorage\CipherHelper("devtest",1111111111111111));

    Assert::same(true, $storage->save($key, $data));
    Assert::same(false, $storage->save($key, $data, false));
    Assert::same($data, $storage->load($key));
    Assert::same(true, $storage->exists($key));
    Assert::same(false, $storage->exists($falseKey));
    Assert::same(true, $storage->remove($key));
    Assert::same(false, $storage->remove($falseKey));