<?php declare(strict_types=1);

namespace App\Common\Infrastructure\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\QuoteStrategy;
use RuntimeException;

use function array_map;
use function array_merge;
use function is_numeric;
use function is_string;
use function preg_replace;
use function substr;

class MySqlQuoteStrategy implements QuoteStrategy
{
    /**
     * @inheritDoc
     */
    public function getColumnName($fieldName, ClassMetadata $class, AbstractPlatform $platform): string
    {
        if (isset($class->fieldMappings[$fieldName]['quoted'])) {
            return $platform->quoteIdentifier($class->fieldMappings[$fieldName]['columnName']);
        }

        $reserved_keywords_list = $platform->getReservedKeywordsList();

        if ($reserved_keywords_list->isKeyword($fieldName)) {
            return $platform->quoteIdentifier($class->fieldMappings[$fieldName]['columnName']);
        }

        return $class->fieldMappings[$fieldName]['columnName'];
    }

    /**
     * @inheritDoc
     */
    public function getTableName(ClassMetadata $class, AbstractPlatform $platform): string
    {
        if (isset($class->table['quoted'])) {
            return $platform->quoteIdentifier($class->table['name']);
        }

        $reserved_keywords_list = $platform->getReservedKeywordsList();

        if ($reserved_keywords_list->isKeyword($class->table['name'])) {
            return $platform->quoteIdentifier($class->table['name']);
        }

        return $class->table['name'];
    }

    /**
     * @inheritDoc
     */
    public function getSequenceName(array $definition, ClassMetadata $class, AbstractPlatform $platform): string
    {
        if (isset($definition['quoted'])) {
            return $platform->quoteIdentifier($class->table['name']);
        }

        $reserved_keywords_list = $platform->getReservedKeywordsList();

        if ($reserved_keywords_list->isKeyword($definition['sequenceName'])) {
            return $platform->quoteIdentifier($definition['sequenceName']);
        }

        return $definition['sequenceName'];
    }

    /**
     * @inheritDoc
     */
    public function getJoinTableName(array $association, ClassMetadata $class, AbstractPlatform $platform): string
    {
        if (isset($association['joinTable']['quoted'])) {
            return $platform->quoteIdentifier($association['joinTable']['name']);
        }

        $reserved_keywords_list = $platform->getReservedKeywordsList();

        if ($reserved_keywords_list->isKeyword($association['joinTable']['name'])) {
            return $platform->quoteIdentifier($association['joinTable']['name']);
        }

        return $association['joinTable']['name'];
    }

    /**
     * @inheritDoc
     */
    public function getJoinColumnName(array $joinColumn, ClassMetadata $class, AbstractPlatform $platform): string
    {
        if (isset($joinColumn['quoted'])) {
            return $platform->quoteIdentifier($joinColumn['name']);
        }

        $reserved_keywords_list = $platform->getReservedKeywordsList();

        if ($reserved_keywords_list->isKeyword($joinColumn['name'])) {
            return $platform->quoteIdentifier($joinColumn['name']);
        }

        return $joinColumn['name'];
    }

    /**
     * @inheritDoc
     */
    public function getReferencedJoinColumnName(array $joinColumn, ClassMetadata $class, AbstractPlatform $platform): string
    {
        if (isset($joinColumn['quoted'])) {
            return $platform->quoteIdentifier($joinColumn['referencedColumnName']);
        }

        $reserved_keywords_list = $platform->getReservedKeywordsList();

        if ($reserved_keywords_list->isKeyword($joinColumn['referencedColumnName'])) {
            return $platform->quoteIdentifier($joinColumn['referencedColumnName']);
        }

        return $joinColumn['referencedColumnName'];
    }

    /**
     * @inheritDoc
     */
    public function getIdentifierColumnNames(ClassMetadata $class, AbstractPlatform $platform): array
    {
        $quoted_column_names = [];

        foreach ($class->identifier as $fieldName) {
            if (isset($class->fieldMappings[$fieldName])) {
                $quoted_column_names[] = [$this->getColumnName($fieldName, $class, $platform)];

                continue;
            }

            // Association defined as Id field
            $join_columns = $class->associationMappings[$fieldName]['joinColumns'];
            $assoc_quoted_column_names = array_map(
                function ($join_column) use ($platform) {
                    if (isset($join_column['quoted'])) {
                        return $platform->quoteIdentifier($join_column['name']);
                    }

                    $reserved_keywords_list = $platform->getReservedKeywordsList();

                    if ($reserved_keywords_list->isKeyword($join_column['name'])) {
                        return $platform->quoteIdentifier($join_column['name']);
                    }

                    return $join_column['name'];
                },
                $join_columns
            );

            $quoted_column_names[] = $assoc_quoted_column_names;
        }

        return array_merge(...$quoted_column_names);
    }

    /**
     * @inheritDoc
     */
    public function getColumnAlias($columnName, $counter, AbstractPlatform $platform, ClassMetadata $class = null): string
    {
        // 1 ) Concatenate column name and counter
        // 2 ) Trim the column alias to the maximum identifier length of the platform.
        //     If the alias is to long, characters are cut off from the beginning.
        // 3 ) Strip non alphanumeric characters
        // 4 ) Prefix with "_" if the result its numeric
        $columnName .= '_' . $counter;
        $columnName = substr($columnName, -$platform->getMaxIdentifierLength());
        $columnName = preg_replace('/[^A-Za-z0-9_]/', '', $columnName);

        if (!is_string($columnName)) {
            throw new RuntimeException('preg_replace failed when creating column alias');
        }

        return is_numeric($columnName) ? '_' . $columnName : $columnName;
    }
}
