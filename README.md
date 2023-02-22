## Codememory DTO
#### This library is mainly for Symfony, but you can also use it in native PHP. How is this library different from others? The library can automatically collect objects according to the given rules from the data that you specify, basically this is the data from the Request

### Install
```shell 
$ composer require codememory/dto
```

### What will be covered in this documentation?
* How to use DTO?
* How to validate DTO with symfony/validator?
* How to use constraints?
* What is DataTransferControl in constraints?
* How to create your own constraints?
* What is a collector and how to create your own collector ?

> [ ! ] Please note that in the DataTransfer, all properties that we process must have the access modifier _"public"_

### Usage examples

```php
<?php

use Codememory\Dto\DataTransfer;
use Codememory\Dto\Collectors\BaseCollector;
use Codememory\Dto\Constraints as DtoConstraints;

enum StatusEnum
{
    case ACTIVATED;
    case NOT_ACTIVATED;
}

#[DtoConstraints\ToTypeConstraint]
final class UserDto extends DataTransfer
{
    public ?string $name = null;
    public ?string $surname = null;
    public ?int $age = null;
    
    #[DtoConstraints\ToEnumConstraint]
    public ?StatusEnum $status = null;
}

$userDto = new UserDto(new BaseCollector());

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

final ProductDto extends DataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Name is required'),
        new Assert\Length(max: 5, maxMessage: 'Name must not exceed 5 characters')
    ])]
    public ?string $name = null;
}


$productDto = new ProductDto(new BaseCollector());

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

### Use constraints

> There are 2 types of constraints, one points to the DTO class, the other to the DTO properties

> The difference between the constraint which the class has is that the given constraint will be executed for all DTO properties and the given constraint will be executed first

```php
use Codememory\Dto\Constraints as DtoConstraints

// Constraint for class
#[DtoConstraints\ToTypeConstraint] // This constraint will cast all DTO properties to the type specified by the property
final class OneDto extends DataTransfer 
{
    public ?int $number = null;
    public array $list = [];
}

// Constrains for properties
final class TestDto extends DataTransfer 
{
    #[DtoConstraints\NestedDataTransferConstraint(OneDto::class)]
    public ?OneDto $one = null;
    
   
    // Multiple constraints
    // Priority works here, first ToEnumConstraint will be executed, and then IgnoreSetterCallConstraint
    #[DtoConstraints\ToEnumConstraint]
    #[DtoConstraints\IgnoreSetterCallConstraint]
    public ?StatusEnum $status = null;
}
```

### List of constraints
* __AsPatchConstraint__ - Calls a setter on the object being collected and invokes the following constraints on the given property, only if the request method is PATCH and a property key is passed to collect
  * __$assert (default: [])__ - Array of validation rules if this property will be processed


* __IgnoreSetterCallConstraint__ - Ignore the setter call on the collected object


* __NestedDataTransferConstraint__ - Nested DataTransfer, nest in DataTransfer property
    * __$dataTransfer__ - DataTransfer namespace
    * __$object (default: null)__ - The namespace of the object to be collected. If the value is not passed, the property on which this constraint is attached will ignore the setter call on the collected object


* __ToEntityConstraint__ - Translate value from collect data to doctrine entity (Requires registration)
  * __$byKey__ - The key by which to search for a record in the database
  * __$isList (default: false)__ - Searching for multiple entities in an array, it is recommended that before calling this constraint, call constraint ToTypeConstraint with array type and with parameter onlyData = true
  * __$uniqueInList (default: true)__ - The value from collect data will be passed through the array_unique function
  * __$checkNotFoundEntity (default: true)__ - Whether to perform a check if the entity is not found, if the entity is not found, an exception will be thrown
  * __$customHandlerNotFoundEntity (default: null)__ - Own handler, if the entity is not found, you need to specify the method name from DataTransfer
    * __$value__ - Value from collect data
    * __$dataTransferControl__ - API for managing logic
  * __$itemValueConverter (default: null)__ - Method for converting each element of the array, which will be passed as a value for searching in the database


* __ToEnumConstraint__ - Translates a value from collect data to an enum object
  * __$byValue (default: false)__ - Search for case in Enum by its value, by default it searches by its name


* __ToTypeConstraint__ - Converts a value from collect data to a specific type
  * __$type (default: auto)__ - The name of the PHP type or Interface DateTime. By default works on the type from the property
  * __$onyData (default: false)__ - Force cast to type, only value at collect data level


* __ValidationConstraint__ - Add symfony assert constraint to validation queue
  * __$assert__ - Array of validation rules if this property will be processed

### Parsing DataTransferControl
> This is an API class that comes inside a constraint to manage the state or values of the dto, the object being collected, and the value from collect data

#### Properties:
  * __$property__ - readonly ReflectionProperty - Current property being processed
  * __$dataTransfer__ - readonly DataTransferInterface - Current DTO being processed
  * __$data__ - readonly array - A copy of the data that was passed to collect data


#### Methods:
  * __setDataTransferValue__ - Set value for DTO property
  * __setObjectValue__ - Set a value for the setter of the collected object
  * __setDataValue__ - Set the value that was passed to collect data
  * __setSetterMethodNameToObject__ - Set the name of the setter method that will be called on the collected object
  * __setIsIgnoreSetterCall__ - Set the setter call ignore status of the collected object
  * __setIsSkipProperty__ - Set skip status of currently processed property in DataTransfer
  * __setDataKey__ - Set a new value selection key from collect data - does not play a major role, intended for processing subsequent constraints

#### DataTransfer Methods:
  * __getReflectionAdapter__ - Returns the Reflection Adapter
  * __setObject__ - Set the object to be collected, if the object is not set, all processing associated with the object will not run and the DTO will work without the object
  * __getObject__ - Get the collected object, must be called after the collect method
  * __addDataTransferCollection__ - Add collection or array of collections with symfony validator constraints (assert)
    * __$key__ - The key by which you can then get the collection itself with the rules for each property
    * __$dataTransferCollection__ - Expects a Codememory\Dto\DataTransferCollection or an array of Codememory\Dto\DataTransferCollection. This collection is used to validate DTO properties
  * __getListDataTransferCollection__ - Returns a list of collections
  * __collect__ - Collects DTO and object (if one was passed)

### DataTransferCollection Methods
  * __construct__
    * __$dataTransfer__ - DTO
    * __$propertyValidation__ - Validate properties of the DTO that was passed as the first argument. Scheme: [propertyName => [Symfony assert objects]]
  * __getDataTransfer__ - Return $dataTransfer
  * __getDataTransfer__ - Return $propertyValidation
  * __addPropertyValidation__ - Add validation on a specific property
    * __$propertyName__ - The name of the property to be validated
    * __$constraints__ - An array of symfony assert objects or a specific symfony assert object

### Creating your own constraint

```php
use Attribute;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Codememory\Dto\Interfaces\ConstraintHandlerInterface;
use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Registers\ConstraintHandlerRegister;

// Let's create a constraint that will combine the value of all properties and separate it with a certain character
#[Attribute(Attribute::TARGET_PROPERTY)] // Will only apply to properties
final class PropertyConcatenationConstraint implements ConstraintInterface 
{
    public function __construct(
        public readonly string $propertyNames,
        public readonly string $separator
    ) {}
    
    public function getHandler() : string
    {
        return PropertyConcatenationConstraintHandler::class;
    }
}

// Create constraint Handler
final class PropertyConcatenationConstraintHandler implements ConstraintHandlerInterface 
{
    /**
     * @param PropertyConcatenationConstraint $constraint
     */
    public function handle(ConstraintInterface $constraint, DataTransferControl $dataTransferControl) : void
    {
        // Get the values of all passed properties
        $assignedValues = array_map(static function (string $property) use ($dataTransferControl) {
            return $dataTransferControl->dataTransfer->$property;
        }, $constraint->propertyNames);
        
        // Update the current value by concatenating multiple values separating them with $separator
        $dataTransferControl->setValue(implode($constraint->separator, $assignedValues));
    }
}

// Now let's register our handler so that DataTransfer can receive it
ConstraintHandlerRegister::register(new PropertyConcatenationConstraintHandler());

// Let's test our constraint
final class TestDto extends DataTransfer
{
    public ?string $name = null;
    public ?string $surname = null;
    
    #[PropertyConcatenationConstraint(['name', 'surname'], '+')]
    public ?string $fullName = null;
}

$testDto = new TestDto(new BaseCollector());

$testDto->collect([
    'name' => 'Code',
    'surname' => 'Memory',
    'full_name' => 'test_full_name' // Our constraint will override this value
]);

echo $testDto->fullName // Code+Memory
```

### Creating Your Own Collector

> Collector - is a DTO collector, it plays a major role in working with each DTO property

```php
use Codememory\Dto\Interfaces\CollectorInterface;
use Codememory\Dto\DataTransferControl;
use Codememory\Dto\Interfaces\ConstraintInterface;
use Codememory\Dto\Registers\ConstraintHandlerRegister;

final class MyCollector implements CollectorInterface
{
    public function collect(DataTransferControl $dataTransferControl) : void
    {
        // Here all processing begins on each property, this method is called every iteration of the properties
        
        // Example get property attributes
        foreach ($dataTransferControl->property->getAttributes() as $attribute) {
            $attributeInstance = $attribute->newInstance();
        
            if ($attributeInstance instanceof ConstraintInterface) {
                $constraintHandler = ConstraintHandlerRegister::getHandler($attributeInstance->getHandler());
                
                // ....
            }
        }
        
        // ....
    }
}
```