<?php declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Throwable;

class UserNotFoundException extends DomainRecordNotFoundException
{
    public function __construct(
        $message = 'The user you requested does not exist.',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
