<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class AuthorController extends AbstractController {
  
  protected AuthorService $service;
  
  public function __construct(AuthorService $service){
    $this->service = $service;
  }
  
  #[Route(
    path: "authors",
    name: "get_authors",
    methods: "GET"
  )]
  public function getAuthors(Request $request): JsonResponse {
    $response = $this->service->getAll($request->query->all());
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
  #[Route(
    path: "authors/{id}",
    name: "get_author_by_id",
    requirements: ["id" => "[0-7][0-9A-HJKMNP-TV-Z]{25}"],
    methods: "GET"
  )]
  public function getAuthorById(Ulid $id): JsonResponse {
    $response = $this->service->getById($id);
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
  #[Route(
    path: "authors",
    name: "create_author",
    
  )]
  public function createAuthor(Request $request): JsonResponse {
    $response = $this->service->createAuthor($request->getContent(true));
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
}