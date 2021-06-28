<?php

use Classes\Models\Order;
use Classes\Models\Product;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

use Codeception\Test\Unit;

use DMS\PHPUnitExtensions\ArraySubset\Assert;
use PHPUnit\Framework\TestCase;

//AssertArraySubset устарел и был удален в новых версиях PHPUnit так что пришлось
//использовать стороннюю библиотеку

class ParamsTest extends \Codeception\Test\Unit
{
    /**
     * @throws Exception
     */
    public function testOrderParams()
    {
        $order = new Order();
        $this->processParamsTest($order);
    }

    /**
     * @throws Exception
     */
    public function testProductParams()
    {
        $product = new Product();
        $this->processParamsTest($product);
    }

    /**
     * @param SimpleParamsModel $model
     * @throws Exception
     */
    protected function processParamsTest($model)
    {
        $model->removeParams();
        $model->setParam('simple', 1);
        $model->setParam('array.data', [
            'one' => 1,
            'two' => 2
        ]);
        $model->setParam('array.data.three', 3);
        $model->setParam('array.data.five', 5);
        $model->unsetParam('array.data.three');
        $model->unsetParam('array.data.four.five'); //Unset will false
        $model->unsetParams('a,b,array.data.five');
        $model->unsetParams(['c', 'd']);

        $this->assertEquals($model->getParam('simple'), 1, 'Simple Key');
        $this->assertEquals($model->getParam('array.data.two'), 2, 'Simple Array Key');
        $this->assertArrayHasKey('one', $model->getParam('array.data'), 'Simple Array Type');
        Assert::assertArraySubset([
            'one' => 1,
            'two' => 2
        ], $model->getParam('array.data'), false, 'Array Contains Data');
        $this->assertNull($model->getParam('array.data.three'));
        $this->assertNull($model->getParam('array.data.four'));
        $this->assertNull($model->getParam('array.data.five'));
        $this->assertNull($model->getParam('a'));
        $this->assertNull($model->getParam('b'));
        $this->assertNull($model->getParam('c'));
        $this->assertNull($model->getParam('d'));
    }
}