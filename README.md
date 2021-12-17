# tembel-router
İlk defa router yazmak zorunda kaldım


## Nasıl kullanılır:
```php
<?php

	// Sınıfı nereye koyduysak oradan çağırıyoruz
	include 'Route.php';

	// İsim uzayını belirdiyoruz
	use \Yakusha\Route;

	// Yollarımızı ekliyoruz
	//sabit sayfa tanımlarımız
	//gizli olarak sayfaların id değerleri varsa onları da cast edebiliyoruz

	Route::add('page', array('/hakkimizda$/'), array('id_cast' => 151));

	//bizim için en önemli olan şey, yıllardır kullandığımız url yapısını korumak
	//içerik yollarını tanımlıyoruz
	Route::add('content_detail', array('/(.*)-k([0-9]*).html$/'));

	//feed ve sitemaplar
	//fark ettiyseniz birden çok url patterni tanımlayabiliyoruz
	Route::add('feed', array(
		'/feed.xml$/', '/feed.php$/', '/feed$/', 
		'/rss.xm$/', '/rss$/, 
		'/atom$/'));

	//Router çalıştırıyoruz
	$result = Route::run();
	
	//kullanacağımız değişkenlere varsayılan değer giriyoruz
	//özellikle $_sayfaadi dosyası için önemli, çünkü include için kullanıyoruz

	$_sayfaadi  = 'index';
	$_m1		= 0;
	$_m2		= 0;
	
	if(isset($result['page'])) 
	{
		$_sayfaadi	= @$result['page'];
		$_m1		= @rawurldecode($result['match1']);
		$_m2		= @rawurldecode($result['match2']);
		
		//sayfada kullanılsın istediğiz değişkenleri extract ediyoruz
		extract($result['param']);		
	}
	
	//en son dosyamızı include ediyoruz
	//burada elbette bir dosya varlığı kontrolü yapılabilir
	//lakin adı üstünde tembel router!
	include $_sayfaadi.'.php';

```

Büyük oranda şu projeden ilham alınmıştır: https://github.com/steampixel/simplePHPRouter
