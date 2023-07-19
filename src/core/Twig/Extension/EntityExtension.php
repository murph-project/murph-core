<?php

namespace App\Core\Twig\Extension;

use App\Core\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EntityExtension extends AbstractExtension
{
    protected PropertyAccessor $propertyAccessor;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->disableExceptionOnInvalidPropertyPath()
            ->getPropertyAccessor()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('entity_to_array', [$this, 'toArray']),
        ];
    }

    public function toArray(EntityInterface $entity, bool $keepIterable = true, array $except = []): array
    {
        $metaData = $this->entityManager->getClassMetadata(get_class($entity));
        $array = [];

        foreach ($metaData->fieldNames as $field) {
            try {
                $value = $this->propertyAccessor->getValue($entity, $field);

                if (in_array($field, $except)) {
                    continue;
                }

                if (is_iterable($value) && !$keepIterable) {
                    continue;
                }

                if (is_object($value)) {
                    $value = (string) $value;
                }

                $array[$field] = [
                    'id' => $field,
                    'name' => Str::asHumanWords($field),
                    'value' => $value,
                ];
            } catch (\Error $e) {
            }
        }

        return $array;
    }
}
