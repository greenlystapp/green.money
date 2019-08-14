<?php

use Greenlyst\GreenMoney\Models\Customer;

class CustomerModelTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testModelCreationReturnsCorrectArray()
    {
        $customer = $this->make(Customer::class, [
            'nickName' => 'jd', 'firstName' => 'John', 'lastName' => 'Doe',
            'phoneWork' => '123456', 'phoneWorkExtension' => '456',
            'emailAddress' => 'a@b.com'
        ]);

        $expected = [
            'NickName' => 'jd',
            'NameFirst' => 'John',
            'NameLast' => 'Doe',
            'PhoneWork' => '123456',
            'PhoneWorkExtension' => '456',
            'EmailAddress' => 'a@b.com',
        ];

        $actual = $customer->toArray();

        $this->assertEquals($expected, $actual);
    }
}