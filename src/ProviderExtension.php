<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan;

use Faker\Generator;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\MissingMethodFromReflectionException;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\ShouldNotHappenException;
use ReflectionException;

class ProviderExtension implements MethodsClassReflectionExtension
{
    /** @var array<string, MethodReflection> */
    private array $methods = [];

    public function __construct(private ReflectionProvider $reflectionProvider)
    {
    }

    /**
     * @throws ReflectionException
     * @throws ShouldNotHappenException
     * @throws MissingMethodFromReflectionException
     */
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if (! $classReflection->is(Generator::class)) {
            return false;
        }

        if (isset($this->methods[$this->getKey($classReflection, $methodName)])) {
            return true;
        }

        $native = $classReflection->getNativeReflection();
        $providers = $native->getProperty('providers')->getValue();

        foreach ($providers as $provider) {
            if (! method_exists($provider, $methodName)) {
                continue;
            }

            if (! $this->reflectionProvider->hasClass($provider::class)) {
                continue;
            }

            $methodReflection = $this->reflectionProvider->getClass($provider)->getNativeMethod($methodName);
        }

        return false;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        return $this->methods[$this->getKey($classReflection, $methodName)];
    }

    private function getKey(ClassReflection $classReflection, string $methodName): string
    {
        return $classReflection->getName() . '::' . $methodName;
    }
}
