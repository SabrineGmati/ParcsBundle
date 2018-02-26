<?php

namespace ParcsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ParcType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
            ->add('adresse', ChoiceType::class, array(
                    'label' => 'Type',
                    'choices' => array(
                        'Grand Tunis' => 'tunis',
                        'Sousse' => 'sousse',
                        'Hammamet' => 'hammamet',
                        'Sfax' => 'sfax',
                        'Djerba' => 'djerba'
                    ),
                    'required' => true,
                    'multiple' => false,)
            )
            ->add('description')
            ->add('tel')
            ->add('image', FileType::class, array('label' => 'Sélectionner une image de votre Parc',
                'attr' => array('class' => 'filestyle', 'data-buttonName' => "btn-primary"), 'data_class' => null));


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Parc'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_parc';
    }


}
