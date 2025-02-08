## Codememory DTO
#### This library is mainly for Symfony, but you can also use it in native PHP. How is this library different from others? The library can automatically collect objects according to the given rules from the data that you specify, basically this is the data from the Request

### Install
```shell 
$ composer require codememory/dto
```

### Injection

```php
<?php

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Codememory\Reflection\ReflectorManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Codememory\Dto\PropertyGrouper;
use Codememory\Dto\Factory\PropertyExecutionContextFactory;
use Codememory\Dto\NameConverter\SnakeCaseNameConverter;
use Codememory\Dto\Processors\ClassDecoratorProcessor;
use Codememory\Dto\Registrars\ClassDecoratorRegistrar;
use Codememory\Dto\Processors\PropertyDecoratorProcessor;
use Codememory\Dto\Registrars\DecoratorTypeRegistrar;
use Codememory\Dto\Registrars\PropertyDecoratorRegistrar;
use Codememory\Dto\Factory\ClassExecutionContextFactory;

$cache = new FilesystemAdapter('codememory', directory: 'cache');
$reflectorManager = new ReflectorManager($cache);
$eventDispatcher = new EventDispatcher();
$propertyDecoratorRegistrar = new PropertyDecoratorRegistrar();

$manager = new DataTransferObjectManager(
    $reflectorManager,
    new PropertyGrouper(
        new PropertyExecutionContextFactory(
            new SnakeCaseNameConverter()
        )
    ),
    new ClassDecoratorProcessor(
        new ClassDecoratorRegistrar(),
        $eventDispatcher
    ),
    new PropertyDecoratorProcessor(
        new DecoratorTypeRegistrar(),
        $propertyDecoratorRegistrar,
        $eventDispatcher
    ),
    new ClassExecutionContextFactory(
        new PropertyWrapperFactory()
    )
);
```

### Use
```php
use Codememory\Dto\Decorators\Property;

enum MyEnum {
    case FOO_BAR;
}

enum MyEnum2: string {
    case TEST = 'value';
}

class MyObject {
    public function __construct(
        public string $fooBar,
        
        #[Property\ToEnum]
        public MyEnum $case,
        
        #[Property\ToEnum(value: true)]
        public MyEnum2 $case2
    ) {}
}

$result = $manager->hydrate(MyObject::class, [
    'foo_bar' => 'Foo Bar',
    'case' => 'FOO_BAR',
    'case2' => 'value'
]);
```

### Events
`Codememory\Dto\Events\AfterAllProcessedTypeDecoratorsEvent` - Fires after all types of property decorators have been processed

`Codememory\Dto\Events\AfterProcessedClassDecoratorsEvent` - Fires after all class decorators have been processed

`Codememory\Dto\Events\AfterProcessedTypeDecoratorsEvent` - Fires after each type of decorator has been processed

`Codememory\Dto\Events\BeforeAllProcessedTypeDecoratorsEvent` - Fires before all types of property decorators are processed

`Codememory\Dto\Events\BeforeProcessedClassDecoratorsEvent` - Fires before class decorators are processed

`Codememory\Dto\Events\BeforeProcessedTypeDecoratorsEvent` - Fires before the next type of property decorator is processed

### Warnings

> DTO does not support optional parameters, all parameters must be required and every key in data must exist at the time of passing to the manager


### How to send Request Body?

> No one knows what parameters will be passed from the client to the server. To avoid getting exceptions or fatal errors, you should do validation before mapping data. This library works well with different types of decorators, which will allow you to manage the priorities of processing decorators and after processing a particular type of decorator you can use events to validate the data before it is set to the object properties.

### Example

> We will use Symfony Validator for validation. The necessary decorator already exists inside the library, which will be able to collect all the constreints in one place

> SymfonyValidation decorator has type Codememory\Dto\Interfaces\SymfonyValidationDecoratorTypeInterface this type of decorators is registered with priority 0, which will allow all decorators with this type to work before all others.

```php
use Codememory\Dto\Decorators\Property;
use Symfony\Component\Validator\Constraints as Assert;

// DTO
class MyObject {
    public function __construct(
        #[Property\SymfonyValidation([
            new Assert\NotBlank(message: 'Foo Bar is required.'),
            new Assert\Type('string', message: 'Invalid type for For Bar.')
        ])]
        public string $fooBar
    ) {}
}

$result = $manager->hydrate(MyObject::class, []);
```

`Listener for processing all contraints`

```php
use Codememory\Dto\Events\AfterProcessedTypeDecoratorsEvent;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;
use Codememory\Dto\Decorators\Property\SymfonyValidationHandler;

class SymfonyValidationEventListener {
    public function onProcessed(AfterProcessedTypeDecoratorsEvent $event): void 
    {
        if (SymfonyValidationDecoratorTypeInterface::class === $event->type) {
            // This contains an array of all the constants of the object into which we store the data
            $metadata = $event->classExecutionContext->getMetadata()[SymfonyValidationHandler::METADATA_KEY] ?? false;

            if (false !== $metadata) {
                $validator = Validation::createValidator();

                $violations = $validator->validate($event->data, new Collection($metadata, missingFieldsMessage: 'Not all fields have been transferred.'));

                foreach ($violations as $error) {
                    throw new RuntimeException($error->getMessage());
                }
            }
        }
    }
}

// Before creating a manager, you must refer to the EventDispatcher that you pass to the manager
$listener = new SymfonyValidationEventListener();

$eventDispatcher->addListener(AfterProcessedTypeDecoratorsEvent::class, $listener->onProcessed(...));
```

> You don't have to use the SymfonyValidation decorator, you can write your own. As an example, we show you the Symfony Validation decorator for Symfony Validation

### Decorator
```php
// Decorator

<?php

namespace Codememory\Dto\Decorators\Property;

use Attribute;
use Codememory\Dto\Interfaces\PropertyDecoratorInterface;
use Codememory\Dto\Interfaces\SymfonyValidationDecoratorTypeInterface;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class SymfonyValidation implements PropertyDecoratorInterface
{
    /**
     * @param array<int, Constraint> $constraints
     */
    public function __construct(
        public array $constraints
    ) {
    }

    public function getType(): string
    {
        return SymfonyValidationDecoratorTypeInterface::class;
    }

    public function getHandler(): string
    {
        return SymfonyValidationHandler::class;
    }
}
```

### Decorator Handler

```php
<?php

namespace Codememory\Dto\Decorators\Property;

use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\PropertyDecoratorHandlerInterface;
use Codememory\Dto\Interfaces\PropertyExecutionContextInterface;

class SymfonyValidationHandler implements PropertyDecoratorHandlerInterface
{
    public const string METADATA_KEY = '__symfony_constraints';

    /**
     * @param SymfonyValidation $decorator
     */
    public function process(DecoratorInterface $decorator, PropertyExecutionContextInterface $executionContext): void
    {
        $metadata = $executionContext->getClassExecutionContext()->getMetadata();
        $inputName = $executionContext->getInputName();

        if (!array_key_exists(self::METADATA_KEY, $metadata)) {
            $metadata[self::METADATA_KEY] = [];
        }

        if (!array_key_exists($inputName, $metadata[self::METADATA_KEY])) {
            $metadata[self::METADATA_KEY][$inputName] = [];
        }

        $metadata[self::METADATA_KEY][$inputName] += $decorator->constraints;

        $executionContext->getClassExecutionContext()->setMetadata($metadata);
    }
}
```

> getClassExecutionContext returns the class context that is in effect at the time of one hydration, if there is a nesting in the object, the context for the nested object will be different. 