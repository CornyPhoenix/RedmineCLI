<?php
/*
 * (C) 2016 ${USER_NAME}
 */

namespace CornyPhoenix\Component\Redmine\Normalizer;

use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer as BaseNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GetSetMethodNormalizer extends BaseNormalizer
{

    /**
     * {@inheritdoc}
     *
     * @throws LogicException
     * @throws CircularReferenceException
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if ($this->isCircularReference($object, $context)) {
            return $this->handleCircularReference($object);
        }

        $reflectionObject = new \ReflectionObject($object);
        $reflectionMethods = $reflectionObject->getMethods(\ReflectionMethod::IS_PUBLIC);
        $allowedAttributes = $this->getAllowedAttributes($object, $context, true);

        $attributes = array();
        foreach ($reflectionMethods as $method) {
            if ($this->isGetMethod($method)) {
                if (0 === strpos($method->name, 'is')) {
                    $attributeName = $method->name;
                } else {
                    $attributeName = lcfirst(substr($method->name, 3));
                }
                if (in_array($attributeName, $this->ignoredAttributes)) {
                    continue;
                }

                if (false !== $allowedAttributes && !in_array($attributeName, $allowedAttributes)) {
                    continue;
                }

                $attributeValue = $method->invoke($object);
                if (isset($this->callbacks[$attributeName])) {
                    $attributeValue = call_user_func($this->callbacks[$attributeName], $attributeValue);
                }
                if (null !== $attributeValue && !is_scalar($attributeValue)) {
                    if (!$this->serializer instanceof NormalizerInterface) {
                        throw new LogicException(sprintf('Cannot normalize attribute "%s" because injected serializer is not a normalizer', $attributeName));
                    }

                    $attributeValue = $this->serializer->normalize($attributeValue, $format, $context);
                }

                if ($this->nameConverter) {
                    $attributeName = $this->nameConverter->normalize($attributeName);
                }

                $attributes[$attributeName] = $attributeValue;
            }
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $allowedAttributes = $this->getAllowedAttributes($class, $context, true);
        $normalizedData = $this->prepareForDenormalization($data);

        $reflectionClass = new \ReflectionClass($class);
        $object = $this->instantiateObject($normalizedData, $class, $context, $reflectionClass, $allowedAttributes);

        $classMethods = get_class_methods($object);
        foreach ($normalizedData as $attribute => $value) {
            if ($this->nameConverter) {
                $attribute = $this->nameConverter->denormalize($attribute);
            }

            $allowed = $allowedAttributes === false || in_array($attribute, $allowedAttributes);
            $ignored = in_array($attribute, $this->ignoredAttributes);

            if ($allowed && !$ignored) {
                $setter = 'set'.ucfirst($attribute);

                if (in_array($setter, $classMethods)) {
                    $method = $reflectionClass->getMethod($setter);
                    if (!$method->isStatic() && $method->getNumberOfParameters() === 1) {
                        $parameter = $method->getParameters()[0];

                        if (null === $value && !$parameter->allowsNull()) {
                            throw new RuntimeException('Attribute ' . $attribute . ' may not be null');
                        }

                        if ($class = $parameter->getClass()) {
                            if (!$this->serializer instanceof DenormalizerInterface) {
                                throw new LogicException(sprintf('Cannot denormalize attribute "%s" because injected serializer is not a denormalizer', $attribute));
                            }
                            $value = $this->serializer->denormalize($value, $class->getName(), $format, $context);
                        }

                        $object->$setter($value);
                    }
                }
            }
        }

        return $object;
    }

    /**
     * Checks if a method's name is get.* or is.*, and can be called without parameters.
     *
     * @param \ReflectionMethod $method the method to check
     *
     * @return bool whether the method is a getter or boolean getter.
     */
    private function isGetMethod(\ReflectionMethod $method)
    {
        $methodLength = strlen($method->name);

        return
            !$method->isStatic() &&
            (
                ((0 === strpos($method->name, 'get') && 3 < $methodLength) ||
                    (0 === strpos($method->name, 'is') && 2 < $methodLength)) &&
                0 === $method->getNumberOfRequiredParameters()
            )
            ;
    }
}