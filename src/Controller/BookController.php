<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Ulid;

class BookController extends AbstractController {
  
  protected BookService $service;
  
  public function __construct(BookService $service){
    $this->service = $service;
  }
  
  #[Route(
    path: "books",
    name: "get_books",
    methods: "GET"
  )]
  public function getBooks(Request $request): JsonResponse {
    $response = $this->service->getAll($request->query->all());
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
  #[Route(
    path: "books/{id}",
    name: "get_book_by_id",
    requirements: ["id" => "[0-7][0-9A-HJKMNP-TV-Z]{25}"],
    methods: "GET"
  )]
  public function getBookById(Ulid $id): JsonResponse {
    $response = $this->service->getById($id);
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
  #[Route(
    path: "authors/{id}/books",
    name: "get_author_books",
    requirements: ["id" => "[0-7][0-9A-HJKMNP-TV-Z]{25}"],
    methods: "GET"
  )]
  public function getBookByAuthor(Ulid $id, Request $request): JsonResponse {
    $response = $this->service->getAll(array_merge($request->query->all(), ["authorId" => $id]));
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
  #[Route(
    path: "books",
    name: "create_book",
    methods: "POST"
  )]
  public function createBook(Request $request): JsonResponse {
    $response = $this->service->save(json_decode($request->getContent(), true));
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
  #[Route(
    path: "books/{id}",
    name: "update_book",
    requirements: ["id" => "[0-7][0-9A-HJKMNP-TV-Z]{25}"],
    methods: "PUT"
  )]
  public function updateBook(Ulid $id, Request $request): JsonResponse {
    $response = $this->service->update($id, json_decode($request->getContent(), true));
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
  #[Route(
    path: "books/{id}",
    name: "delete_book",
    requirements: ["id" => "[0-7][0-9A-HJKMNP-TV-Z]{25}"],
    methods: "DELETE"
  )]
  public function deleteBook(Ulid $id): JsonResponse {
    $response = $this->service->delete($id);
    
    return new JsonResponse($response->getData(), $response->getStatusCode());
  }
  
}