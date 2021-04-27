<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\TagType;
use App\Repository\TagRepository;
use App\Service\TagsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(TagType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->response($form, Response::HTTP_BAD_REQUEST);
        }

        return $this->response(
            $this->tagsService->create($form->getData()->getName())
        );
    }

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
