<?php

declare(strict_types = 1);

namespace MyGarden\Views;

use MyGarden\Response\Response;

interface IView
{
	public function display(Response $response): void;
}