{{-- Any 5xx without a page of its own, here or in Laravel (whose 500 and 503 extend minimal too). Never the
     exception's message: it may hold internals. --}}
@extends('errors::minimal')

@section('title', __(Symfony\Component\HttpFoundation\Response::$statusTexts[$exception->getStatusCode()] ?? 'Error'))
@section('code', $exception->getStatusCode())
@section('message', __(Symfony\Component\HttpFoundation\Response::$statusTexts[$exception->getStatusCode()] ?? 'Error'))
