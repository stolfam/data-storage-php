# Data Storage PHP

## Install
`composer require stolfam/data-storage-php`

## Use
You can use existing implementations or implement your own logic of save/load by implementing interface `IDataStorage`.

### Example
```
// you can use existing implementation or implement your own logic of save/load
$dataStorage = new CookiesStorage();
$serializableData = ...

// set data with protection to overwriting (example 1)
if($dataStorage->save("key", $serializableData, false)) {
    // successfully saved
} else {
    // "key" exists or error on save
}

// set data with overwritting enabled (example 2)
if($dataStorage->save("key", $serializableData)) {
    // successfully saved
} else {
    // error on save
}

// get data (example 1)
if($dataStorage->exists("key")) {
    // we are 100% sure that data exists
    $data = $dataStorage->load("key");
}

// get data (example 2)
$data = $dataStorage->load("key");
if($data != null) {
    // we are 100% sure that data exists
}
```