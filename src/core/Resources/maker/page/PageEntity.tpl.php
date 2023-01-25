<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use App\Core\Entity\Site\Page\Page;
use App\Core\Entity\Site\Page as BlockEntity;
use App\Core\Form\Site\Page as Block;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormBuilderInterface;

#[ORM\Entity]
class <?= $class_name; ?> extends Page
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
<?php if (count($blocks)): ?>        $builder
<?php foreach ($blocks as $block): ?>
            ->add('<?= $block['name'] ?>', <?= $block['type'] ?>)
<?php endforeach; ?>
        ;
<?php endif; ?>
    }

<?php foreach ($blocks as $block): ?>
    public function set<?= $block['camelCase'] ?>(BlockEntity\Block $block)
    {
        return $this->setBlock($block);
    }

    public function get<?= $block['camelCase'] ?>()
    {
        return $this->getBlock('<?= $block['name'] ?>'<?php if ($block['class']): ?>, <?= $block['class'] ?><?php endif; ?>);
    }

<?php endforeach; ?>
}
