<?php

namespace App\Form;

use App\Entity\Transport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class TransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class)
            ->add('type', ChoiceType::class,[
                'choices' =>[
                    '' =>[
                        'Bus' =>'Bus',
                        'Voiture' =>'Voiture',
                        'train' =>'train',
                        'avion' =>'avion',
                    ],
                ],
            ])        
            ->add('description',TextType::class)
            ->add('disponibilite',TextType::class)
            ->add('price',TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transport::class,
        ]);
    }
}
