<?php

require 'vendor/autoload.php';

use Classes\Models\Product;
use Classes\Models\Order;



$order = new Order();
$order->setParam('array.data',['one' => 2, 'two' => 3]);
$order->setParam('array.data.three',5);
print_r($order->saveDataDB());
$result = $order->getDataDB();

print_r($result[5]);



// print_r($order->unsetParam('array.data.one,array.data.two'));
// print_r($order->unsetParam(['array.data.one', 'array.data.three']));
// print_r($order->unsetParam('array.data.three'));




// print_r($testArray);

// print_r($order->getData());



?>