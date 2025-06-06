<?php

namespace Tests\FlexibleFramework\Validator;

use FlexibleFramework\Validator\ValidatorError;
use PHPUnit\Framework\TestCase;

class ValidatorErrorTest extends TestCase
{
    public function testString()
    {
        $error = new ValidatorError('demo', 'fake', ['a1', 'a2']);
        $property = (new \ReflectionClass($error))->getProperty('messages');
        $property->setValue($error, ['fake' => 'problem %2$s %3$s']);
        $this->assertEquals('problem a1 a2', (string) $error);
    }

    public function testUnknownError()
    {
        $rule = 'fake';
        $field = 'demo';
        $error = new ValidatorError($field, $rule, ['a1', 'a2']);
        $this->assertStringContainsString($field, (string) $error);
        $this->assertStringContainsString($rule, (string) $error);
    }
}
