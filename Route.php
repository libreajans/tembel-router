<?php

	namespace Yakusha\Kitaphaber;
	
	/**
	* @page 	: İşlem sonunda hangi sayfanın include edilmesi gerektiğini depolamakta kullanıyoruz
	* 
	* @pattern	: Kullanacağımız eşleştirme regex patternlerini burada tanımlıyoruz
	* 			  Aynı sayfayı birden fazla dış url'den çözümleyebilmek için burada 
	*			  dizi kullanıyoruz. Her pattern anahtarsız bir dizi olarak tanımlanmalı
	*			  
	* @match	: Eşleyen kaçıncı değerin alınacağını gösterir, bunlar 0,1,2 şeklinde bir sıralamayla gider
	* 			  
	* @type		: Elde etmek istediğimiz sonucun tipini belirlemeye yarar, farklı bir değer gelmişse router yoluna devam eder
	* 			  kabul ettiği değerler (numeric, string, null)
	* 			  null geldiğinde bu iki denetimi de yapmadan eşleşmeyi null olarak döner
	* 
	* @param	: Yeni sayfaya aktarılmasını istediğimiz parametreleri burada dizi olarak 
	* 			  oluşturup gönderiyoruz. daha sonra extract komutu ile değişkene dönüştürebiliyoruz
	* 			  
	*
	* @return	: Sonuç kısmında eşleşmiş değerleri ve match değerini dönüyoruz.
	*/
	

	class Route
	{
	
		public function __construct(){}
	
		private static $routes = Array();

		public static function add($page, $pattern = array(), $match = 2, $type = 'numeric', $param = array())
		{
			array_push(self::$routes, Array(
				'page'		=> $page,
				'pattern'	=> $pattern,
				'param'		=> $param,
				'match'		=> $match,
				'type'		=> $type,
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
				$result['page']		= $route['page'];
				$result['param']	= $route['param'];
				
				foreach($route['pattern'] as $k => $v)
				{
					if(preg_match($v, $url_path, $matches))
					{
						if($route['type'] == 'numeric')
						{
							if(is_numeric($matches[$route['match']]))
							{
								$result['match'] = (int) $matches[$route['match']];
								//bir sonuç bulduysak ana döngüyü de alt döngüyü de kıralım
								break 2;
							}
						}
						
						if($route['type'] == 'string')
						{
							if(is_string($matches[$route['match']]))
							{
								$result['match'] = (string) rawurldecode($matches[$route['match']]);
								//bir sonuç bulduysak ana döngüyü de alt döngüyü de kıralım
								break 2;
							}
						}
						
						//saçma bir şekilde buraya null göndermek de gerekebiliyor
						if($route['type'] == 'null')
						{
							$result['match'] = 'null';
							break 2;
						}
						
					}
				}
			}
			
			if(!empty($result['match']))
			{
				return $result;
			}
			
		}
	}
