<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\TagType;
use App\Repository\TagRepository;
use App\Service\Exception\NotUniqueEntityException;
use App\Service\TagsService;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TagController extends ApiController
{
    private TagRepository $tagRepository;
    private TagsService $tagsService;

    public function __construct(
        TagRepository $tagRepository,
        TagsService $tagsService
    ) {
        $this->tagRepository = $tagRepository;
        $this->tagsService = $tagsService;
    }

    /**
     * Create tag action
     *
     * @param Request $request
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(TagType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        try {
            return $this->response(
                $this->tagsService->create($form->getData()->getName())
            );
        } catch (NotUniqueEntityException $e) {
            throw new HttpException(Response::HTTP_CONFLICT, $e->getMessage());
        }
    }

    /**
     * Edit tag action
     *
     * @param int $tagId
     * @param Request $request
     * @return Response
     *
     * @throws EntityNotFoundException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function updateAction(int $tagId, Request $request): Response
    {
        if (!$tag = $this->tagRepository->find($tagId)) {
            throw new NotFoundHttpException('Tag not found');
        }

        $form = $this->buildForm(TagType::class, $tag, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        return $this->response(
            $this->tagsService->update($tagId, $form->getData()->getName())
        );
    }
}
