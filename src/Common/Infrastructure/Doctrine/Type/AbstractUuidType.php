<?php declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

use function array_pop;
use function is_a;
use function is_scalar;
use function pack;
use function preg_replace;
use function str_replace;
use function unpack;

abstract class AbstractUuidType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_scalar($value)) {
            $value = $this->createValueObjectFromValue($value);
        }

        $fqcn = $this->getValueObjectClassName();

        if (!is_a($value, $fqcn)) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $fqcn);
        }

        return pack('h*', str_replace('-', '', (string) $value));
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $values = unpack('h*', $value);
        $uuid = preg_replace(
            '/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/',
            '$1-$2-$3-$4-$5',
            array_pop($values),
        );

        return $this->createValueObjectFromValue($uuid);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = 16;
        $column['fixed'] = true;

        return $platform->getBinaryTypeDeclarationSQL($column);
    }

    abstract protected function createValueObjectFromValue($value): object;

    abstract protected function getValueObjectClassName(): string;
}
