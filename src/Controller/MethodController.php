<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Method;
use App\Annotation\Breadcrumb;
use App\Form\ConfigureMethod;
use App\Form\ConfigureMethodType;
use App\Service\ProcessCreator;
use App\Service\InformationFinder;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route(name="method_", path="/method/")
 */
final class MethodController extends AbstractController
{
    const TITLE_CONFIGURE = 'Настройка нейронной сети';
    const TITLE_LIST = 'Прогнозирование';

    private ProcessCreator $processCreator;

    private InformationFinder $informationFinder;

    public function __construct(
        ProcessCreator $processCreator,
        InformationFinder $informationFinder
    ) {
        $this->processCreator = $processCreator;
        $this->informationFinder = $informationFinder;
    }

    /**
     * @Route(name="configure", path="configure")
     * @Breadcrumb(route="method_list", name=MethodController::TITLE_CONFIGURE)
     */
    public function configureAction(Request $request): Response
    {
        $data = new ConfigureMethod();
        $form = $this->createForm(ConfigureMethodType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Method|null $method */
            $method = $this->informationFinder->getInformationBySlug(
                Method::class,
                (string) $request->query->get('method')
            );

            if (is_null($method)) {
                $form->addError(new FormError('Не указан метод для прогнозирования'));
            } else {
                return $this->redirectToRoute('process_show', [
                    'id' => $this->processCreator->create($data->setMethod($method))
                ]);
            }
        }

        return $this->render('method/configure.html.twig', [
            'title' => self::TITLE_CONFIGURE,
            'form'  => $form->createView()
        ]);
    }

    /**
     * @Route(name="list", path="list")
     * @Breadcrumb(route="home", name=self::TITLE_LIST)
     */
    public function listAction(): Response
    {
        return $this->render('method/list.html.twig', [
            'title'   => self::TITLE_LIST,
            'methods' => $this->informationFinder->getInformation(Method::class)
        ]);
    }
}
