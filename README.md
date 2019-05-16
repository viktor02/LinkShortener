# Link Shortener

База данных - MySQL, обращение через PDO.

Для генерации QR кода используется [PHP QR Code](http://phpqrcode.sourceforge.net/)

## Install

Создать базу данных через php my admin и указать настройки в connection.php
Таблица pair создается автоматически

<details>
	<summary>Структура таблицы pair</summary>

	| Столбец       | Тип           |
	| ------------- |:-------------:|
	| id            | int(11)       |
	| serviceUrl    | text          |
	| realUrl       | text          |

</details>

## Возможности

* Создание 5-значных коротких ссылок
* Создание QRCode для ссылки

## ToDo

* Проверка на дублирование ссылки
* Админ панель
* CodeReview