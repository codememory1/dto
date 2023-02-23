<?php

use Codememory\Dto\Constraints;
use Codememory\Dto\Registers\ConstraintHandlerRegister;

ConstraintHandlerRegister::register(new Constraints\IgnoreSetterCallConstraintHandler());
ConstraintHandlerRegister::register(new Constraints\NestedDataTransferConstraintHandler());
ConstraintHandlerRegister::register(new Constraints\ToEnumConstraintHandler());
ConstraintHandlerRegister::register(new Constraints\ToTypeConstraintHandler());
ConstraintHandlerRegister::register(new Constraints\ValidationConstraintHandler());
ConstraintHandlerRegister::register(new Constraints\CallbackConstraintHandler());