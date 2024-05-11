<?php

namespace App\Core\Entity\Site\Page;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BuilderBlock extends JsonBlock
{
    public function getValue()
    {
        $value = parent::getValue();

        if (is_string($value)) {
            return json_decode($value);
        }

        return [];
    }
}
