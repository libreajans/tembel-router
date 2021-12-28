# tembel-router
Bu benim ilk router denemem. Artık .htacess dosyasına değer girerek yönlendirme yapmaktan bıkmıştım.

Bir kaç araştırma yaptıktan sonra şu projeden (https://github.com/steampixel/simplePHPRouter) özelleştirerek kendime bir Router yazmayı başardım.

Neredeyse aynen sitede kullandığım şekliyle sizinle paylaşıyorum.

Sorunuz olursa sormaktan çekinmeyin.

İlk sürüm bundan daha basitti, lakin parse edilen değerler için bir eşleştirme yapmıyordu. Yeni sürüm biraz daha tutarlı bir router oldu.

## Nasıl kullanılır:
```php
<?php

 	//vendorları ve kendi classlarımızı çağırıyoruz
	require_once 'Route.php';

	use Yakusha\Kitaphaber\Route;
	
	//bu küçük denetim sayesinde direk ana sayfa talep edildiyse rooter içine hiç almıyoruz
	if(!isset($_SERVER['REQUEST_URI']) OR $_SERVER['REQUEST_URI'] == '/')
	{
		$_sayfaadi = 'index';
	}
	else
	{
		//Önce en çok kullanılan sayfaları tanımlıyoruz
		//Bizim sitemiz için bunlar içerik sayfaları oluyor
		Route::add('content_detail', array('/(.*)-k([0-9]*).html$/', '/(.*)-k([0-9]*).htm(.*)/'), 2, 'numeric');
		Route::add('book_detail', array('/(.*)-bd([0-9]*).html$/', '/(.*)-bd([0-9]*).htm(.*)/'), 2, 'numeric');
		Route::add('book_list', array('/(.*)-bl([0-9]*)$/', '/(.*)-bl([0-9]*)(.*)/'), 2, 'numeric');
		Route::add('yazar_detay', array('/(.*)-a([0-9]*)$/', '/(.*)-a([0-9]*)(.*)/'), 2, 'numeric');

		//değer gönderilmiş haliyle kimi sayfalar
		Route::add('etiket_liste', array('/etiket\/(.*)$/'), 1, 'string');
		Route::add('etiketler', array('/etiketler\/(.*)$/', '/etiketler$/'), 1, 'string');
		Route::add('arsiv', array('/arsiv\/(.*)$/'), 1, 'string');
		Route::add('arama', array('/arama\/(.*)$/'), 1, 'string');
		
		//değer gönderilmemiş haliyle çıplak sayfalar
		Route::add('etiketler', array('/etiketler$/'), 0, 'string');
		Route::add('arsiv', array('/arsiv$/'), 0, 'string');
		Route::add('arama', array('/arama$/'), 0, 'string'); //TODO template de kırılma var
		
		Route::add('yazarlar', array('/yazarlar$/'), 0, 'string');
		Route::add('books', array('/books$/'), 0, 'string');
		Route::add('mansetler', array('/mansetler$/'), 0, 'string');
		Route::add('ajax', array('/ajax.php$/'), 0, 'string');
		
		//Bu sayfalar alışkanlık olarak var, kimi sayfalar değer de alıyor, oluşturuyoruz
		//değer alsalar bile değerlerini parse etmiyoruz
		Route::add('index', array('/index.php$/', '/index.php\?(.*)/'), 0, 'string');
		Route::add('giris', array('/giris.php$/', '/giris.php\?(.*)/'), 0, 'string');
		Route::add('crop', array('/crop.php$/', '/crop.php\?(.*)$/'), 0, 'string');
		Route::add('404', array('/404.php$/', '/404.php\?(.*)/'), 0, 'string');
		Route::add('acp', array('/acp.php$/', '/acp.php\?(.*)/'), 0, 'string');
		
		//feed için bir kaç default yol belirtiyoruz
		//bu sayfa da hiçbir değer almıyor
		Route::add('feed', array('/feed.xml$/', '/feed.php$/', '/rss.xml$/', '/feed$/', '/atom$/', '/rss$/'), 0, 'string');
		
		//id cast ettiklerimiz bir arada dursun
		Route::add('sitemap', array('/sitemap.xml$/'), 0, 'string', array('image' => 1, 'change' => 0));
		Route::add('sitemap', array('/sitemap_change.xml$/'), 0, 'string', array('image' => 1, 'change' => 1));
		Route::add('sitemap', array('/sitemap_yandex.xml$/'), 0, 'string', array('image' => 0, 'change' => 1));
		Route::add('sitemap', array('/sitemap_yandex_change.xml$/'), 0, 'string', array('image' => 0, 'change' => 1));
		
		//sabit sayfa tanımlarımız
		Route::add('page', array('/hakkimizda$/'), 0, 'string', array('id_cast' => 151));
		Route::add('page', array('/kunye$/'), 0, 'string', array('id_cast' => 152));
		Route::add('page', array('/iletisim$/'), 0, 'string', array('id_cast' => 153));
		Route::add('page', array('/yazi-gondermek-icin$/'), 0, 'string', array('id_cast' => 154));

		//sabit kategori yollarını tanımlıyoruz
		Route::add('kategori', array('/dusunce$/'), 0, 'string', array('id_cast' => 100));
		Route::add('kategori', array('/edebiyat$/'), 0, 'string', array('id_cast' => 101));
		Route::add('kategori', array('/bilim$/'), 0, 'string', array('id_cast' => 102));
		Route::add('kategori', array('/ilahiyat$/'), 0, 'string', array('id_cast' => 103));
		Route::add('kategori', array('/soylesi$/'), 0, 'string', array('id_cast' => 104));
		Route::add('kategori', array('/yeni-cikanlar$/'), 0, 'string', array('id_cast' => 105));
		Route::add('kategori', array('/dergi$/'), 0, 'string', array('id_cast' => 106));
		Route::add('kategori', array('/sinema$/'), 0, 'string', array('id_cast' => 107));
		Route::add('kategori', array('/kara-tahta$/'), 0, 'string', array('id_cast' => 108));
		Route::add('kategori', array('/haber$/'), 0, 'string', array('id_cast' => 109));
		Route::add('kategori', array('/tarih$/'), 0, 'string', array('id_cast' => 110));
		Route::add('kategori', array('/cocuk-kitapligi$/'), 0, 'string', array('id_cast' => 111));
		
		$result = Route::run();
		
		//default değerler atıyoruz
		//$_sayfaadi için 404 atayarak tüm bulunamayan 
		//url değerlerini oraya yönlendiriyoruz
		$_sayfaadi  = '404';
		$_match		= 0;
		
		//elimizdeki sonuçta bir sayfa varsa değerleri parse etmeye başlıyoruz
		if(isset($result['page'])) 
		{
			$_sayfaadi	= $result['page'];
			$_match		= $result['match'];
			
			//bu kısımda param ile gelen parametreleri değişkene dönüştürüyoruz
			//kategori ve sayfalar gibi statik url değerlerini dinamik url yaparken kullanıyoruz
			extract($result['param']);		
		}
	}
	
	//siteyi artık çağırabiliriz
	include $_sayfaadi.'.php';

```
