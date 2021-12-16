<?php

	/***
	* Berbat belgelendirme için beni bağışlayın lütfen.
	* 
	* @page 	: İşlem sonunda hangi sayfanın include edilmesi gerektiğini depolamakta kullanıyoruz
	* @pattern	: Kullanacağımız eşleştirme regex patternlerini burada tanımlıyoruz
	* 			 Aynı sayfayı birden fazla dış url'den çözümleyebilmek için burada 
	*			 dizi kullanıyoruz. Her pattern anahtarsız bir dizi olarak tanımlanmalı
	* @param	: Yeni sayfaya aktarılmasını istediğimiz parametreleri burada dizi olarak 
	* 			 oluşrup gönderiyoruz. daha sonra extract komutu ile değişkene dönüştürebiliyoruz
	*
	* Sonuç kısmında sonucu bir dizi olarak dönüyouz, dizi içinde iki eşleşmiş sonuç olabiliyor
	* İşimize yarayan sonucu duruma göre kullanıyoruz.			 
	*/

	namespace Yakusha;

	class Route
	{
		private static $routes = Array();

		public static function add($page, $pattern = array(), $param = array())
		{
			array_push(self::$routes, Array(
				'page'		=> $page,
				'pattern'	=> $pattern,
				'param'		=> $param,
			));
		}

		public static function getAll()
		{
			return self::$routes;
		}

		public static function run()
		{
			if(isset($_SERVER['REQUEST_URI']))
			{
				$url_path = $_SERVER['REQUEST_URI'];
				if(substr($url_path,-1) == '/')
				{
					$url_path = substr($url_path, 0, -1);
				}
			}
			
			foreach (self::$routes as $route)
			{
				foreach($route['pattern'] as $k => $v)
				{
					if(preg_match($v, $url_path, $matches))
					{
						$result['page']		= $route['page'];
						$result['match1']	= @$matches[1];
						$result['match2']	= @$matches[2];
						$result['param']	= $route['param'];
						return $result;
					}
				}
			}
		}
	}
