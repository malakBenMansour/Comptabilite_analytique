<?php

namespace App\Form;

use App\Entity\ProduitSearch;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice',IntegerType::class, [
             'required'=>false,
                'label'=>false,
                'attr'=> ['placeholder'=> 'Prix maximale']

            ])


            ->add('minQuantite' ,IntegerType::class,[
                'required'=>false,
                'label'=>false,
                'attr'=> ['placeholder'=> 'Quantite minimale']

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProduitSearch::class,
            'method'=> 'get',
            'crsf_protection'=> false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
