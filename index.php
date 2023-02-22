<?php

use Codememory\Dto\Collectors\BaseCollector;
use Codememory\Dto\Constraints as DtoConstraints;
use Codememory\Dto\DataTransfer;
use Codememory\Dto\Registers\ConstraintHandlerRegister;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

require_once 'vendor/autoload.php';

enum StatusEnum: string
{
    case ACTIVATED = 'activated';
    case BLOCKED = 'blocked';
}

class StatusEntity
{
    private ?string $key = null;
    private ?string $label = null;

    public function setKey(?string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }
}

class AccountEntity
{
    private ?int $account = null;
    private ?int $age = null;
    private ?StatusEntity $status = null;

    public function setAccount(int $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function setStatus(?StatusEntity $status): self
    {
        $this->status = $status;

        return $this;
    }
}

class UserEntity
{
    private ?string $name = null;
    private ?string $surname = null;
    private ?AccountEntity $account = null;

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function setAccount(?AccountEntity $account): self
    {
        $this->account = $account;

        return $this;
    }
}

#[DtoConstraints\ToTypeConstraint]
class StatusDto extends DataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Ключ статуса обязательный к заполнению')
    ])]
    public ?string $key = null;

    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Название статуса обязательно к заполнению')
    ])]
    public ?string $label = null;
}

#[DtoConstraints\ToTypeConstraint]
class AccountDto extends DataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\Length(min: 5, minMessage: 'Счет должен состоять минимум из 5 символов')
    ])]
    public ?int $account = null;

    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Возраст обязательный к заполнению')
    ])]
    public ?int $age = null;

    #[DtoConstraints\NestedDataTransferConstraint(StatusDto::class, StatusEntity::class)]
    public ?StatusDto $status = null;
}

class UserDto extends DataTransfer
{
    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Имя обязательно к заполнению')
    ])]
    public ?string $name = null;
    public ?bool $is = null;

    #[DtoConstraints\ValidationConstraint([
        new Assert\NotBlank(message: 'Фамилия обязательна к заполнению')
    ])]
    public ?string $surname = null;

    #[DtoConstraints\NestedDataTransferConstraint(AccountDto::class, AccountEntity::class)]
    public ?AccountDto $account = null;

    #[DtoConstraints\ToEnumConstraint(true)]
    public ?StatusEnum $enum = null;
}

ConstraintHandlerRegister::register(new DtoConstraints\ValidationConstraintHandler());
ConstraintHandlerRegister::register(new DtoConstraints\NestedDataTransferConstraintHandler());
ConstraintHandlerRegister::register(new DtoConstraints\ToTypeConstraintHandler());
ConstraintHandlerRegister::register(new DtoConstraints\ToEnumConstraintHandler());

$validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

$userDto = new UserDto(new BaseCollector());

// $userDto->setObject(new UserEntity());
$userDto->collect([
    'name' => 'My Name',
    'surname' => 'My surname',
    'account' => [
        'account' => 12345,
        'age' => 23,
        'status' => [
            'key' => 'ACTIVATED',
            'label' => 'Активирован'
        ]
    ],
    'enum' => 'activated'
]);

dd($userDto->enum);

$errors = $validator->validate($userDto);

foreach ($errors as $error) {
    echo $error->getMessage() . '<br>';
}