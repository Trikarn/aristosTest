<?php

require 'vendor/autoload.php';

use Classes\Models\Product;
use Classes\Models\Order;

// echo "IT WORK";

// $product = new Product('testTitle','12312415125','testParams');

// $product->saveData();

// print_r($product->getData());

$order = new Order('12312415125',['testParams' => 'test']);

// $order->saveDataDB();

$testArray = [
    'test' => 3,
    'array' => [
        '54' => 54
    ]
];

$order->setParam('array.data',['one' => 2, 'two' => 3]);
$order->setParam('array.data.three',5);

// print_r($order->unsetParam('array.data.one,array.data.two'));
// print_r($order->unsetParam(['array.data.one', 'array.data.three']));
print_r($order->unsetParam('array.data.three'));

// print_r($order->getParam('array.data'));



// print_r($testArray);

print_r($order->getData());



?>