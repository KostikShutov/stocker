<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Metal;
use App\Entity\Period;
use App\Service\Api\ApiProvider;
use App\Service\Api\ProviderResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ConfigureMethodType extends AbstractType
{
    private ProviderResolver $providerResolver;

    public function __construct(ProviderResolver $providerResolver)
    {
        $this->providerResolver = $providerResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('metal', EntityType::class, [
                'label'        => 'Металл',
                'class'        => Metal::class,
                'choice_label' => 'title'
            ])
            ->add('period', EntityType::class, [
                'label'        => 'Тип прогноза',
                'class'        => Period::class,
                'choice_label' => 'title'
            ])
            ->add('start', DateType::class, [
                'label'    => 'Начальный диапазон известных котировок',
                'widget'   => 'single_text',
                'required' => false
            ])
            ->add('end', DateType::class, [
                'label'    => 'Конечный диапазон известных котировок',
                'widget'   => 'single_text',
                'required' => false
            ])
            ->add('provider', ChoiceType::class, [
                'label'   => 'Провайдер данных',
                'data'    => ApiProvider::PROVIDER_YAHOO,
                'choices' => self::getProviderChoices()
            ])
            ->add('run', SubmitType::class, [
                'label' => 'Запустить'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ConfigureMethod::class,
        ]);
    }

    /**
     * @return string[]
     */
    private function getProviderChoices(): array
    {
        $choices = [];

        foreach ($this->providerResolver->getProviders() as $period) {
            $choices[$period->getId()] = $period->getId();
        }

        return $choices;
    }
}
