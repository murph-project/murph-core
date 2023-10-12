<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Core\Entity\Site\Page\Page;
use App\Core\Entity\Site\Page as BlockEntity;
use App\Core\Form\Site\Page as Block;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormBuilderInterface;

#[ORM\Entity]
class <?php echo $class_name; ?> extends Page
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
<?php if (count($blocks)) { ?>        $builder
<?php foreach ($blocks as $block) { ?>
            ->add('<?php echo $block['name']; ?>', <?php echo $block['type']; ?>)
<?php } ?>
        ;
<?php } ?>
    }

<?php foreach ($blocks as $block) { ?>
    public function set<?php echo $block['camelCase']; ?>(BlockEntity\Block $block)
    {
        return $this->setBlock($block);
    }

    public function get<?php echo $block['camelCase']; ?>()
    {
        return $this->getBlock('<?php echo $block['name']; ?>'<?php if ($block['class']) { ?>, <?php echo $block['class']; ?><?php } ?>);
    }

<?php } ?>
}
