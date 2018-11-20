<?php

namespace App\Tests\Helpers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Interfaces\CharacterInterface;

class PHPUnitHelper
{
    /**
     * @param object $instance
     * @param string $methodName
     *
     * @return \ReflectionMethod|string
     */
    public static function callPrivateMethod(object $instance, string $methodName)
    {
        try {
            return self::setAccessibleForMethod($instance, $methodName);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param object $instance
     * @param string $methodName
     * @param array  $args
     *
     * @return mixed|string
     */
    public static function callPrivateMethodWithArgs(object $instance, string $methodName, array $args)
    {
        try {
            $method = self::setAccessibleForMethod($instance, $methodName);

            return $method->invokeArgs($instance, $args);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param object $instance
     * @param string $property
     *
     * @return mixed
     */
    public static function getPrivatePropertyValue(object $instance, string $property)
    {
        try {
            $reflector = new \ReflectionClass($instance);
            $reflectorProperty = $reflector->getProperty($property);
            $reflectorProperty->setAccessible(true);

            return $reflectorProperty->getValue($instance);
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * @param array       $methods
     * @param WebTestCase $testCase
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    public static function builderCharacterMock(array $methods, WebTestCase $testCase)
    {
        $characterMock = $testCase->getMockBuilder(CharacterInterface::class)->getMock();
        foreach ($methods as $methodName => $methodArgs) {
            $characterMock->method($methodName)->willReturn($methodArgs);
        }

        return $characterMock;
    }

    /**
     * @param object $instance
     * @param string $name
     *
     * @throws \ReflectionException
     *
     * @return \ReflectionMethod
     */
    private static function setAccessibleForMethod(object $instance, string $name)
    {
        $class = new \ReflectionClass($instance);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
