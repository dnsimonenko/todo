<?php declare(strict_types=1);

namespace TodoApp\Infrastructure\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class ValueObjectConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        if ($request->attributes->has($configuration->getName())) {
            $className = $configuration->getClass();
            if (!class_exists($className)) {
                throw new \InvalidArgumentException(sprintf('Class %s not found to transform a value to value object', $className));
            }

            $method = $configuration->getOptions()['method'] ?? 'fromString';

            if (!in_array('fromString', get_class_methods($className))) {
                throw new \InvalidArgumentException(sprintf('Class %s must have %s method to represent a value object', $className, $method));
            }

            $valueObject = $className::$method($request->attributes->get($configuration->getName()));

            $request->attributes->set($configuration->getName(), $valueObject);
        }
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() && $configuration->getConverter() === 'value_object';
    }
}
