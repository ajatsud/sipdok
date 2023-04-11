<?php

class Render {

	public static function view(string $file, array $data = []): void {
		foreach ($data as $key => $value)
			$$key = $value;
		$file = __DIR__ . '/../tpl/' . $file . '.php';
		$index = __DIR__ . '/../tpl/index.php';
		if (file_exists($index))
			include $index;
	}
}
