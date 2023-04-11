<?php

class Render {

	public static function view(string $filename, array $data = []): void {
		foreach ($data as $key => $value)
			$$key = $value;
		$file = __DIR__ . '/../tpl/' . $filename . '.php';
		if (file_exists($file))
			include $file;
	}
}
