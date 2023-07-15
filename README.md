## Codememory DTO
#### This library is mainly for Symfony, but you can also use it in native PHP. How is this library different from others? The library can automatically collect objects according to the given rules from the data that you specify, basically this is the data from the Request

### Install
```shell 
$ composer require codememory/dto
```

### What will be covered in this documentation?
* How to use DTO?
* How to validate DTO with symfony/validator?
* How to use decorators?
* What is Context in decorators?
* How to create your own decorators?
* What is a collector and how to create your own collector ?

> [ ! ] Please note that in the DataTransfer, all properties that we process must have the access modifier _"public"_

### Usage examples

```php
<?php

use Codememory\Dto\Collectors\BaseCollector;
use Codememory\Dto\Decorators as DD;
use Codememory\Dto\Configuration;
use Codememory\Reflection\ReflectorManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Codememory\Dto\AbstractDataTransferObject;
use Codememory\Dto\Factory\ExecutionContextFactory;

enum StatusEnum
{
    case ACTIVATED;
    case NOT_ACTIVATED;
}

#[DD\ToType]
final class UserDto extends AbstractDataTransferObject
{
    public ?string $name = null;
    public ?string $surname = null;
    public ?int $age = null;
    
    #[DD\ToEnum]
    public ?StatusEnum $status = null;
}

$userDto = new UserDto(
    new BaseCollector(), 
    new Configuration(),
    new ExecutionContextFactory(),
    new ReflectorManager(new FilesystemAdapter('dto', '/var/cache/codememory'))
);

// We start the assembly of DTO based on the transferred data
$userDto->collect([
    'name' => 'My Name',
    'surname' => 'My Surname',
    'age' => 80,
    'status' => 'ACTIVATED'
]);

// Result dump UserDto
/** 
  name    -> My Name
  surname -> My Surname
  age     -> 80
  status  -> StatusEnum::ACTIVATED (object)
*/
```

### Validate DTO with symfony/validator
```php
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Codememory\Reflection\ReflectorManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Codememory\Dto\AbstractDataTransferObject;
use Codememory\Dto\Configuration;
use Codememory\Dto\Factory\ExecutionContextFactory;

final ProductDto extends AbstractDataTransferObject
{
    #[DD\Validation([
        new Assert\NotBlank(message: 'Name is required'),
        new Assert\Length(max: 5, maxMessage: 'Name must not exceed 5 characters')
    ])]
    public ?string $name = null;
}


$productDto = new ProductDto(
    new BaseCollector(),
    new Configuration()
    new ExecutionContextFactory(),
    new ReflectorManager(new FilesystemAdapter('dto', '/var/cache/codememory'))
);

$productDto->collect(['name' => 'Super name']);

// Validate
$validator = Validation::createValidatorBuilder()
    ->enableAnnotationMapping()
    ->getValidator();

$errors = $validator->validate();

foreach ($errors as $error) {
    echo $error->getMessage(); // Name must not exceed 5 characters
}
```

### Use decorators

> There are 2 types of decorators, one points to the DTO class, the other to the DTO properties

> The difference between the decorator which the class has is that the given decorator will be executed for all DTO properties and the given decorator will be executed first

```php
use Codememory\Dto\Decorators as DD

// Decorator for class
#[DD\ToType] // This decorator will cast all DTO properties to the type specified by the property
final class OneDto extends AbstractDataTransferObject 
{
    public ?int $number = null;
    public array $list = [];
}

// Decorators for properties
final class TestDto extends AbstractDataTransferObject 
{
    #[DD\NestedDTO(OneDto::class)]
    public ?OneDto $one = null;
    
   
    // Multiple decorators
    // Priority works here, first ToEnum will be executed, and then IgnoreSetterCallForHarvestableObject
    #[DD\ToEnum]
    #[DD\IgnoreSetterCallForHarvestableObject]
    public ?StatusEnum $status = null;
}
```

### List of decorators
* __IgnoreSetterCallForHarvestableObject__ - Ignore the setter call on the harvestable object


* __PrefixSetterMethodForHarvestableObject__ - Changes the prefix of the called method to set the value in the harvestable object
  * __$prefix__ - Prefix name, for example "set"


* __SetterMethodForHarvestableObject__ - Change the full name of the method through which it will be possible to set the value in the harvestable object
    * __$name__ - Method name, for example "setName"


* __NestedDTO__ - Nested DataTransfer, nest in DataTransfer property
    * __$dto__ - DataTransfer namespace
    * __$object (default: null)__ - The namespace of the object to be collected. If the value is not passed, the property on which this decorator is attached will ignore the setter call on the collected object
    * __$thenCallback (default: null)__ - The name of the callback method, which should return a bool value indicating whether it is worth checking out or not


* __ToEnum__ - Translates a value from collect data to an enum object
  * __$byValue (default: false)__ - Search for case in Enum by its value, by default it searches by its name


* __ToEnumList__ - Similar to the ToEnum decorator, except that this decorator expects an array and will try to convert each element of the value into an Enum
    * __$unique (default: true)__ - This is a new argument that will filter the input array for uniqueness


* __ToType__ - Converts a value from collect data to a specific type
  * __$type (default: auto)__ - The name of the PHP type or Interface DateTime. By default works on the type from the property
  * __$onyData (default: false)__ - Force cast to type, only value at collect data level


* __Validation__ - Add symfony assert constraint to validation queue
  * __$assert__ - Array of validation rules if this property will be processed


* __XSS__ - Protecting input strings or strings in an array from XSS attack


* __ExpectArray__ - Expects a normal array
  * __$expectKeys__ - Array of pending keys, the rest will be removed  


* __ExpectMultiArray__ - Expects a normal array
    * __$expectKeys__ - Array of pending keys, the rest will be removed
    * __$itemKeyAsNumber (default: true)__ - Converts all item keys to numeric order


* __ExpectOneDimensionalArray__ - Expects a one-dimensional array
  * __$types (default: any)__ - Array of skipped value types

### Parsing Context
> This is an API class that comes inside a decorator to manage the state or values of the dto, the object being collected, and the value from collect data

#### Methods:
  * __getDataTransferObject__ - Returns the current data transfer object that contains the property being processed.
  * __getProperty__ - Returns the currently processed property.
  * __getData__ - Returns the input data that will be used to collect the dto and the object.
  * __getDataValue__ - Returns a value from data (which was passed during data transfer build).
  * __getDataTransferObjectValue__ - Returns the value that was set to the data transfer property.
  * __getValueForHarvestableObject__ - Returns the value that was set to the object being collected.
  * __getDataKey__ - Returns a key that can be used to get a value from data.
  * __getNameSetterMethodForHarvestableObject__ - Returns the name of the setter method for the object being collected.
  * __isIgnoredSetterCallForHarvestableObject__ - Whether the setter method call on the harvestable object is ignored.
  * __isSkippedThisProperty__ - Whether to skip processing the current property.

> Many of these methods have setters.

#### DataTransferObject Methods:
  * __getCollector__ - Returns the collector with which the dto will be collected
  * __getConfiguration__ - Returns the dto configuration
  * __getExecutionContextFactory__ - Returns the factory with which the decorator context was created
  * __getReflectorManager__ - Returns the reflection manager, more details in the [codememory/reflection](https://github.com/codememory1/reflection) library
  * __getClassReflector__ - Returns a reflection of the current dto
  * __getHarvestableObject__ - Returns the collectable object
  * __setHarvestableObject__ - Set build object
  * __addDataTransferObjectPropertyConstraintsCollection__ - Add DTO Property Validation Collection
  * __getListDataTransferObjectPropertyConstrainsCollection__ - Get a list of all dto property validation collections
  * __getDataTransferObjectPropertyConstrainsCollection__ - Get collection validation property of specific dto
  * __collect__ - Starts the entire build process
  * __recollectHarvestableObject__ - Rebuilds the harvestable object into the new passed object

### Creating your own decorator

```php
use Attribute;
use Codememory\Dto\Interfaces\DecoratorInterface;
use Codememory\Dto\Interfaces\DecoratorHandlerInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Reflection\ReflectorManager;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Codememory\Dto\Configuration;
use Codememory\Dto\AbstractDataTransferObject;
use Codememory\Dto\Factory\ExecutionContextFactory;

// Let's create a decorator that will combine the value of all properties and separate it with a certain character
#[Attribute(Attribute::TARGET_PROPERTY)] // Will only apply to properties
final class PropertyConcatenation implements DecoratorInterface 
{
    public function __construct(
        public readonly string $propertyNames,
        public readonly string $separator
    ) {}
    
    public function getHandler() : string
    {
        return PropertyConcatenationHandler::class;
    }
}

// Create decorator Handler
final class PropertyConcatenationHandler implements DecoratorHandlerInterface 
{
    /**
     * @param PropertyConcatenation $decorator
     */
    public function handle(ConstraintInterface $decorator, ExecutionContextInterface $context) : void
    {
        // Get the values of all passed properties
        $assignedValues = array_map(static function (string $property) use ($context) {
            return $context->getDataTransferObject()->$property;
        }, $decorator->propertyNames);
        
        // Update the current value by concatenating multiple values separating them with $separator
        $context->setDataTransferObjectValue(implode($decorator->separator, $assignedValues));
        $context->setValueForHarvestableObject($context->getDataTransferObject());
    }
}

// Let's test our decorator
final class TestDto extends AbstractDataTransferObject
{
    public ?string $name = null;
    public ?string $surname = null;
    
    #[PropertyConcatenation(['name', 'surname'], '+')]
    public ?string $fullName = null;
}

// To register this decorator when you create a new instance by passing the configuration as the second argument to it, you must first register the decorator through this configuration

$configuration = new Configuration();

$configuration->registerDecoratorHandler(new PropertyConcatenationHandler());

$testDto = new TestDto(
    new BaseCollector(),
    $configuration,
    new ExecutionContextFactory(),
    new ReflectorManager(new FilesystemAdapter('dto', '/var/cache/codememory'))
);

$testDto->collect([
    'name' => 'Code',
    'surname' => 'Memory',
    'full_name' => 'test_full_name' // Our decorator will override this value
]);

echo $testDto->fullName // Code+Memory
```

### Creating Your Own Collector

> Collector - is a DTO collector, it plays a major role in working with each DTO property

```php
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\Interfaces\ExecutionContextInterface;
use Codememory\Dto\Interfaces\DecoratorInterface;

final class MyCollector implements CollectorInterface
{
    public function collect(ExecutionContextInterface $context) : void
    {
        // Here all processing begins on each property, this method is called every iteration of the properties
        
        // Example get property attributes
        foreach ($context->getProperty()->getAttributes() as $attribute) {
            $attributeInstance = $attribute->newInstance();
        
            if ($attributeInstance instanceof DecoratorInterface) {
                $decoratorHandler = $context->getDataTransferObject()->getConfiguration()->getDecoratorHandler($attributeInstance->getHandler());
                
                // ....
            }
        }
        
        // ....
    }
}
```