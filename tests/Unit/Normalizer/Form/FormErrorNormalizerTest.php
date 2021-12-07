<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\Normalizer\Form;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Weblabel\ApiBundle\Normalizer\Form\FormErrorNormalizer;

class FormErrorNormalizerTest extends TestCase
{
    public function testErrorNormalizing()
    {
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new ValidatorExtension(Validation::createValidator()))
            ->getFormFactory();

        $emailConstraint = new Email();
        $emailConstraint->message = 'Invalid email';
        $emailConstraint->payload['errorCode'] = 1;

        $notBlankConstraint = new NotBlank();
        $notBlankConstraint->payload['errorCode'] = 2;
        $notBlankConstraint->message = 'Invalid first name';

        $builder = $formFactory->createBuilder(FormType::class);
        $builder->add(
            'email',
            TextType::class,
            [
                'constraints' => [
                    $emailConstraint,
                ],
            ]
        );
        $details = $builder
            ->create('details', FormType::class)
            ->add(
                'firstName',
                TextType::class,
                [
                    'constraints' => [
                        $notBlankConstraint,
                    ],
                ]
            );
        $builder->add($details);

        $form = $builder->getForm();
        $form->submit(
            [
                'email' => 'test',
            ]
        );

        $normalizer = new FormErrorNormalizer();

        self::assertSame(
            [
                'email' => [
                    [
                        'message' => 'Invalid email',
                        'code' => 1,
                        'parameters' => [],
                    ],
                ],
                'details' => [
                    'firstName' => [
                        [
                            'message' => 'Invalid first name',
                            'code' => 2,
                            'parameters' => [],
                        ],
                    ],
                ],
            ],
            $normalizer->normalize($form)
        );
    }
}
