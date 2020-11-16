<?php

declare(strict_types = 1);

namespace MyGarden\Views;

class View
{
	public function display(string $file, array $data = null): void
	{
		include 'src\Views\\' . $file;
	}
}