<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Controller\Conversation;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\Conversation\Conversation;
use BitBag\SyliusMultiVendorMarketplacePlugin\Form\Type\Conversation\MessageType;
use BitBag\SyliusMultiVendorMarketplacePlugin\Repository\Conversation\ConversationRepositoryInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Resolver\ActualUserResolverInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ConversationController
{
    private Environment $templatingEngine;

    private FormFactoryInterface $formFactory;

    private ConversationRepositoryInterface $conversationRepository;

    private ActualUserResolverInterface $actualUserResolver;

    private RequestStack $requestStack;

    public function __construct(
        Environment                     $templatingEngine,
        FormFactoryInterface            $formFactory,
        ConversationRepositoryInterface $conversationRepository,
        ActualUserResolverInterface     $actualUserResolver,
        RequestStack                    $requestStack
    )
    {
        $this->templatingEngine = $templatingEngine;
        $this->formFactory = $formFactory;
        $this->conversationRepository = $conversationRepository;
        $this->actualUserResolver = $actualUserResolver;
        $this->requestStack = $requestStack;
    }

    public function index(): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $template = $request->attributes->get('_sylius')['template'];

        $actualUser = $this->actualUserResolver->resolve();


        if ($request->query->get('closed')) {
            $conversations = $this->conversationRepository->findAllWithStatusAndUser(Conversation::STATUS_CLOSED, $actualUser);
        } else {
            $conversations = $this->conversationRepository->findAllWithStatusAndUser(Conversation::STATUS_OPEN, $actualUser);
        }

        return new Response(
            $this->templatingEngine->render(
                $template, [
                    'conversations' => $conversations,
                ]
            )
        );
    }

    public function show(int $id): Response
    {
        $request = $this->requestStack->getCurrentRequest();
        $template = $request->attributes->get('_sylius')['template'];

        $form = $this->formFactory->create(MessageType::class);

        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);
        return new Response(
            $this->templatingEngine->render(
                $template, [
                    'form' => $form->createView(),
                    'conversation' => $conversation,
                ]
            )
        );
    }
}
