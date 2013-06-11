<?php

namespace Acme\SyncBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CheckAvailability extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('model', 'text');
        $builder->add('color_code', 'text');
        $builder->add('size', 'text');
    }

    public function getName()
    {
        return 'contact';
    }
}
