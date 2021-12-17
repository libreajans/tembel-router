<?php
	// Her neredeyse oradan Sınıfımızı çağıralım
	require_once 'Route.php';

	// Bu isim uzayını kullanalım
	use \Yakusha\Route;
	
	if(isset($_GET['page']) && $_GET['page'] == 'index')
	{
		$_sayfaadi = 'index';
	}
	else
	{
		//sabit sayfa tanımlarımız
		Route::add('page', array('/hakkimizda$/'), array('id_cast' => 151));
		Route::add('page', array('/kunye$/'), array('id_cast' => 152));
		Route::add('page', array('/iletisim$/'), array('id_cast' => 153));
		Route::add('page', array('/yazi-gondermek-icin$/'), array('id_cast' => 154));

		//kategori yollarını tanımlıyoruz
		Route::add('kategori', array('/dusunce$/'), array('id_cast' => 100));
		Route::add('kategori', array('/edebiyat$/'), array('id_cast' => 101));
		Route::add('kategori', array('/bilim$/'), array('id_cast' => 102));
		Route::add('kategori', array('/ilahiyat$/'), array('id_cast' => 103));
		Route::add('kategori', array('/soylesi$/'), array('id_cast' => 104));
		Route::add('kategori', array('/yeni-cikanlar$/'), array('id_cast' => 105));
		Route::add('kategori', array('/dergi$/'), array('id_cast' => 106));
		Route::add('kategori', array('/sinema$/'), array('id_cast' => 107));
		Route::add('kategori', array('/kara-tahta$/'), array('id_cast' => 108));
		Route::add('kategori', array('/haber$/'), array('id_cast' => 109));
		Route::add('kategori', array('/tarih$/'), array('id_cast' => 110));
		Route::add('kategori', array('/cocuk-kitapligi$/'), array('id_cast' => 111));

		//içerik yollarını tanımlıyoruz
		Route::add('content_detail', array('/(.*)-k([0-9]*).html$/'));
		Route::add('book_detail', array('/(.*)-bd([0-9]*).html$/'));
		Route::add('book_list', array('/(.*)-bl([0-9]*)$/'));
		Route::add('books', array('/books$/'));

		Route::add('mansetler', array('/mansetler$/'));
		Route::add('etiket_liste', array('/etiket\/(.*)$/'));
		Route::add('etiketler', array('/etiketler\/(.*)$/', '/etiketler$/'));
		Route::add('yazarlar', array('/yazarlar$/'));
		Route::add('yazar_detay', array('/(.+)-a([0-9]*)$/'));
		Route::add('arsiv', array('/arsiv\/(.*)$/', '/arsiv$/'));
		Route::add('arama', array('/arama\/(.*)$/', '/arama$/'));
		Route::add('404', array('/404$/'));

		//feed ve sitemaplar
		Route::add('feed', array('/feed.xml$/', '/feed.php$/', '/rss.xm$/', '/feed$/', '/atom$/', '/rss$/'));
		Route::add('sitemap', array('/sitemap.xml$/'), array('image' => 1, 'change' => 0));
		Route::add('sitemap', array('/sitemap_change.xml$/'), array('image' => 1, 'change' => 1));
		Route::add('sitemap', array('/sitemap_yandex.xml$/'), array('image' => 0, 'change' => 1));
		Route::add('sitemap', array('/sitemap_yandex_change.xml$/'), array('image' => 0, 'change' => 1));

		//Route::getAll();

		$result = Route::run();

		$_sayfaadi  = '404';
		$_m1		= 0;
		$_m2		= 0;

		if(isset($result['page'])) 
		{
			$_sayfaadi	= @$result['page'];
			$_m1		= @rawurldecode($result['match1']);
			$_m2		= @rawurldecode($result['match2']);

			extract($result['param']);		
		}

	}
	
	include $_sayfaadi.'.php';
