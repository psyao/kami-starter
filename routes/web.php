<?php

declare(strict_types=1);

use Psyao\Kami\Facades\Kami;

// Last: one fallback route answers every Entry's Path, so a route of the site's own, above it, always wins.
Kami::routes();
