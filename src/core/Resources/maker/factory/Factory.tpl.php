<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Core\Factory\FactoryInterface;
use <?php echo $entity; ?> as Entity;

class <?php echo $class_name; ?> implements FactoryInterface
{
    public function create(): Entity
    {
        return new Entity();
    }
}
