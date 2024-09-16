<?php
// api/src/Filter/RegexpFilter.php
namespace App\Filter;
use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;

final class NotInFilter extends AbstractFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if (
            !$this->isPropertyEnabled($property, $resourceClass) ||
            !$this->isPropertyMapped($property, $resourceClass) ||
            $value === '' || 
            $value === null
        ) {
            return;
        }
        // $parameterName = $queryNameGenerator->generateParameterName($property);
        $queryBuilder
            ->andWhere($queryBuilder->expr()->notIn("o.".$property, $value));

    }

    public function getDescription(string $resourceClass): array
    {
        if (!$this->properties) {
            return [];
        }
        $description = [];
        foreach ($this->properties as $property => $strategy) {
            $description[$property] = [
                'property' => $property,
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'description' => 'Not in filter for '.$property,
                'openapi' => [
                    'example' => '1,2,3',
                    'allowReserved' => false,// if true, query parameters will be not percent-encoded
                    'allowEmptyValue' => true,
                    'explode' => false, // to be true, the type must be Type::BUILTIN_TYPE_ARRAY, ?product=blue,green will be ?product=blue&product=green
                ],
            ];
        }
        return $description;
    }
}