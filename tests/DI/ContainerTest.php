<?php

declare(strict_types=1);

namespace Hisui\Tests\DI;

use Hisui\DI\Container;
use Hisui\Test\TestCase;

class HelloGreeter
{
    public function greet(): string
    {
        return 'hello';
    }
}

class MessageGreeter
{
    public function __construct
    (
        private string $message = 'default',
    ) {
    }

    public function greet(): string
    {
        return $this->message;
    }
}

class BracketedGreeter
{
    public function __construct
    (
        private HelloGreeter $greeter,
    ) {
    }

    public function greet(): string
    {
        return '[' . $this->greeter->greet() . ']';
    }
}

class CombinedGreeter
{
    public function __construct
    (
        private BracketedGreeter $bracketedGreeter,
        private MessageGreeter $messageGreeter,
    ) {
    }

    public function greet(): string
    {
        return $this->bracketedGreeter->greet() . '|' . $this->messageGreeter->greet();
    }
}

interface UnboundGreeter {}

class CircularDependencyA
{
    public function __construct
    (
        private CircularDependencyB $b,
    ) {
    }
}

class CircularDependencyB
{
    public function __construct
    (
        private CircularDependencyC $c,
    ) {
    }
}

class CircularDependencyC
{
    public function __construct
    (
        private CircularDependencyA $a,
    ) {
    }
}

class UntypedRequiredArgument
{
    public function __construct
    (
        private $message,
    ) {
    }
}

class RequiredBuiltinArgument
{
    public function __construct
    (
        private string $message,
    ) {
    }
}

class ClassWithDefaultBuiltinArgument
{
    public function __construct
    (
        public string $message = 'hello',
    ) {
    }
}

class RequiredUnionTypedArgument
{
    public function __construct
    (
        public HelloGreeter|MessageGreeter $greeter,
    ) {
    }
}

class ClassWithDefaultUnionTypedArgument
{
    public function __construct
    (
        public HelloGreeter|MessageGreeter $greeter = new HelloGreeter(),
    ) {
    }
}

final class ContainerTest extends TestCase
{
    public function testAutowiresConcreteClass(): void
    {
        $container = new Container();
        $result = $container->get(HelloGreeter::class);

        $this->assertSame(true, $result instanceof HelloGreeter);
        $this->assertSame('hello', $result->greet());
    }

    public function testResolvesRegisteredSingleton(): void
    {
        $container = new Container();
        $container->singleton(MessageGreeter::class, fn () => new MessageGreeter('hi'));
        $result = $container->get(MessageGreeter::class);

        $this->assertSame(true, $result instanceof MessageGreeter);
        $this->assertSame('hi', $result->greet());
        $this->assertSame(
            $container->get(MessageGreeter::class),
            $container->get(MessageGreeter::class),
        );
    }

    public function testAutowiresNestedDependencies(): void
    {
        $container = new Container();
        $result = $container->get(BracketedGreeter::class);

        $this->assertSame(true, $result instanceof BracketedGreeter);
        $this->assertSame('[hello]', $result->greet());
    }

    public function testUsesDefaultConstructorArguments(): void
    {
        $container = new Container();
        $result = $container->get(CombinedGreeter::class);

        $this->assertSame(true, $result instanceof CombinedGreeter);
        $this->assertSame('[hello]|default', $result->greet());
    }

    public function testUsesDefaultValuesForUnsupportedParameterTypes(): void
    {
        $container = new Container();
        $result = $container->get(ClassWithDefaultBuiltinArgument::class);

        $this->assertSame(true, $result instanceof ClassWithDefaultBuiltinArgument);
        $this->assertSame('hello', $result->message);

        $result = $container->get(ClassWithDefaultUnionTypedArgument::class);
        $this->assertSame(true, $result->greeter instanceof HelloGreeter);
    }

    public function testThrowsWhenResolvingUnregisteredNonClassId(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            $container = new Container();
            $container->get('any_string');
        });
    }

    public function testThrowsWhenAutowiringUnboundInterface(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            $container = new Container();
            $container->get(UnboundGreeter::class);
        });
    }

    public function testThrowsWhenRegisteringDuplicateId(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            $container = new Container();
            $container->singleton('factory', fn () => null);
            $container->singleton('factory', fn () => null);
        });
    }

    public function testThrowsWhenAutowiringUnsupportedRequiredParameter(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            $container = new Container();
            $container->get(UntypedRequiredArgument::class);
        });

        $this->assertThrows(\InvalidArgumentException::class, function () {
            $container = new Container();
            $container->get(RequiredBuiltinArgument::class);
        });

        $this->assertThrows(\InvalidArgumentException::class, function () {
            $container = new Container();
            $container->get(RequiredUnionTypedArgument::class);
        });
    }

    public function testThrowsOnCircularDependency(): void
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            $container = new Container();
            $container->get(CircularDependencyC::class);
        });
    }
}
