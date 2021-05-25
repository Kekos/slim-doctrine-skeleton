<?php declare(strict_types=1);

namespace App\User\Domain;

use App\Common\Domain\DomainException\DomainRecordNotFoundException;
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
