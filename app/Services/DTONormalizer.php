<?php

namespace App\Services;

use App\DTO\DataTransferObject;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use ReflectionClass;
use ReflectionProperty;
use DateTimeImmutable;
use DateTime;
use DateTimeInterface;
use Exception;

class DTONormalizer
{
    protected DocBlockFactoryInterface $blockFactory;

    public function __construct(DocBlockFactoryInterface $blockFactory)
    {
        $this->blockFactory = $blockFactory;
    }

    public function denormalize(array $parameters, string $className): DataTransferObject
    {
        $dto = new $className();

        try {
            $class = new ReflectionClass($dto);

            foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {

                $propertyType = $reflectionProperty->getType()->getName();
                $propertyName = $reflectionProperty->getName();

                if (!isset($parameters[$propertyName])) {
                    continue;
                }

                $paramValue = $parameters[$propertyName];

                switch ($propertyType) {
                    case 'int':
                    case 'string':
                    case 'bool':
                    case 'float':
                        settype($paramValue, $propertyType);
                        $dto->{$propertyName} = $paramValue;
                        break;
                    case DateTimeImmutable::class:
                    case DateTime::class:
                    case DateTimeInterface::class:

                        $dto->{$propertyName} = $paramValue ? new DateTime($paramValue) : null;
                        break;
                    case 'null':
                        $dto->{$propertyName} = $paramValue;
                        break;
                    case 'array':
                        if ($docComment = $reflectionProperty->getDocComment()) {
                            $docBlock = $this->blockFactory->create($docComment);
                            $type = $docBlock->getTagsByName('var')[0]
                                ? $docBlock->getTagsByName('var')[0]->getType()->__toString()
                                : null;

                            if ($type) {
                                foreach ($parameters[$propertyName] as $param) {
                                    $dto->{$propertyName}[] = $this->denormalize($param, $type);
                                }
                            }
                        } else {
                            $dto->{$propertyName} = $parameters[$propertyName];
                        }
                        break;

                    default:
                        $dto->{$propertyName} = $this->denormalize($parameters[$propertyName], $propertyType);
                        break;

                }
            }
        } catch (Exception $e) {
            throw new Exception("DTO mapping error. Property name - $propertyName. Property type - $propertyType. Incoming value - $paramValue.");
        }

        return $dto;
    }
}