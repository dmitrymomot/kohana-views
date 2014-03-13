<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_Extension_View extends Kohana_View {

	/**
	 * @var string
	 */
	protected static $_theme_path = NULL;

	/**
	 * Adds path for loading views
	 *
	 * @param string $path
	 * @return void
	 */
	public static function theme_path($path)
	{
		if (is_dir($path))
		{
			static::$_theme_path = rtrim($path, '/').DIRECTORY_SEPARATOR;
		}
	}

	/**
	 * Get the rendered contents of a partial from a loop.
	 *
	 * @param string $view
	 * @param array $data
	 * @param string $key
	 * @param string $empty
	 *
	 * @return string
	 */
	public static function render_each($view, array $data = NULL, $key = NULL, $empty = NULL)
	{
		$views 	= NULL;
		$key 	= ($key) ? $key : 'item';
		$count	= count($data);

		if ($data != NULL AND $count > 0)
		{
			$data = array_values($data);

			foreach ($data as $k => $val)
			{
				$views .= static::factory($view)
					->set($key, $val)
					->set('index', $k+1)
					->set('first_item', ($k == 0))
					->set('last_item', (($k+1) == $count))
					->set('count_all', $count)
					->render();
			}
		}

		return ($views == NULL) ? $empty : $views;
	}

	/**
	 * Sets the view filename.
	 *
	 *     $view->set_filename($file);
	 *
	 * @param   string  $file   view filename
	 * @return  View
	 * @throws  View_Exception
	 */
	public function set_filename($file)
	{
		if (static::$_theme_path AND file_exists(static::$_theme_path.$file.EXT))
		{
			// Store the file path locally
			$this->_file = static::$_theme_path.$file.EXT;

			return $this;
		}

		return parent::set_filename($file);
	}
}
