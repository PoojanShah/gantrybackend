<?php


namespace App\DTO;

use phpDocumentor\Reflection\DocBlockFactoryInterface;
use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{

    public function __construct(array $parameters, DocBlockFactoryInterface $blockFactory)
    {
        try {
            $class = new ReflectionClass(static::class);

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
                        $this->{$propertyName} = $paramValue;
                        break;
                    case \DateTimeImmutable::class:
                    case \DateTime::class:
                    case \DateTimeInterface::class:

                        $this->{$propertyName} = $paramValue ? new \DateTime($paramValue) : null;
                        break;
                    case 'null':
                        $this->{$propertyName} = $paramValue;
                        break;
                    case 'array':
                        if ($docComment = $reflectionProperty->getDocComment()) {
                            $docBlock = $blockFactory->create($docComment);
                            $type = $docBlock->getTagsByName('var')[0]
                                ? $docBlock->getTagsByName('var')[0]->getType()->__toString()
                                : null;

                            if($type){
                                foreach ($parameters[$propertyName] as $param) {
                                    $this->{$propertyName}[] = new $type($param, $blockFactory);
                                }
                            }
                        } else {
                            $this->{$propertyName} = $parameters[$propertyName];
                        }
                        break;

                    default:
                        $this->{$propertyName} = new $propertyType($parameters[$propertyName], $blockFactory);
                        break;

                }
            }
        } catch (\Exception $e) {
            throw new \Exception("DTO mapping error. Property name - $propertyName. Property type - $propertyType. Incoming value - $paramValue.");
        }
    }

}