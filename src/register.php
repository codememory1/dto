<?php

use Codememory\Dto\Constraints;
use Codememory\Dto\Registers\ConstraintHandlerRegister;

ConstraintHandlerRegister::register(new Constraints\IgnoreSetterCallHandler());
ConstraintHandlerRegister::register(new Constraints\NestedDTOHandler());
ConstraintHandlerRegister::register(new Constraints\ToEnumHandler());
ConstraintHandlerRegister::register(new Constraints\ToTypeHandler());
ConstraintHandlerRegister::register(new Constraints\ValidationHandler());
ConstraintHandlerRegister::register(new Constraints\CallbackHandler());
ConstraintHandlerRegister::register(new Constraints\ExpectArrayHandler());
ConstraintHandlerRegister::register(new Constraints\ExpectMultiArrayHandler());
ConstraintHandlerRegister::register(new Constraints\XSSHandler());