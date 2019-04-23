[![Build Status](https://travis-ci.org/bliskapaczkapl/magento.svg?branch=master)](https://travis-ci.org/bliskapaczkapl/magento)

# Moduł Bliskapaczka dla Magento >= 2.1

## Wersja alfa

Moduł jest ciągle przez nas rozwijany i na razie znajduje się w fazie alpha. Nie zalecany używania moduły w systemach produkcyjnych.

## Instalacja modułu

### Wymagania
W celu poprawnej instalacji modułu wymagane są:
- php >= 7.1
- composer

### Instalacja modułu
W katalogu głownym Magento należy wykonać polecenie poniżej, pod zmienną `$version` należy wstawić wersję modułu, która ma zostać zainstalowana lub usunąć w przypadku instalacji najnowszej wersji. W większości przypadków instalacja najnowszej wersji jest polecanym rozwiązaniem.
```
composer require sendit/bliskapaczka-magento-2 $version
```

### Tryb testowy
Tryb testowy, czli komunikacja z testową wersją znajdującą się pod adresem [sandbox-bliskapaczka.pl](https://sandbox-bliskapaczka.pl/) można uruchomić przełączają w ustwieniach modułu opcję `Test mode enabled` na `Yes`.

## Możliwości modułu
- przesyłki do punktów - moduł daje możliwośc użycia jednej z metod dostawy jaką jest możliwość wybrania puktu doręczenia zamówienia (np. InPost, Paczka w Ruch, Poczta Polska,...)
- przesylki kurierskie - moduł daje możliwośc użycia jednej z metod dostawy jaką jest przesyłka kurierska przez wybrenego przewoźnika
- darmowa dostawa - wsparcie dla regół koszykowych definiujących darmową dostawę. Więcej w dokumentacji [Magento](http://docs.magento.com/m1/ce/user_guide/marketing/price-rule-shopping-cart-free-shipping.html)
- zarządzanie przesyłkami - z poziomu modułu istnieje możliwość zarządzania przesyłkami po stronie bliskapaczka.pl
  - pobranie listu przewozowego
  - aktualizacja statusu przesyłki
  - anulowanie zlecenia

## Zarządzanie przesyłkami
Zarządanie przesyłkami odbywa się przez menu Sprzedaż -> Bliskapaczka. Tam dostępna jest lista wszystkich przesyłek.

## Dodatkowe informacje
### Punkty z płatnością przy dobiorze

Widget bliskapaczka.pl przewiduje możliwość wyświetlenia tylko punktów z obsługą płatności przy pobraniu (więcej informacji w [dokumentacji](https://widget.bliskapaczka.pl)). W magento można wyświetlić widget tylk oz punktami obsługującymi płatność przy odbiorze przez wywołanie metody `Bliskapaczka.showMap` z ustawionym parametrem `codOnly` na `true`. Przykład wywołania:

```
Bliskapaczka.showMap(
    [{"operator":"POCZTA","price":9.69},{"operator":"INPOST","price":9.25},{"operator":"RUCH","price":8},{"operator":"DPD","price":9.99}],
    "AIzaSyCUyydNCGhxGi5GIt5z5I-X6hofzptsRjE",
    true,
    "sendit_bliskapaczka_sendit_bliskapaczka_COD"
    true
)
```

### Informacja o punkcie dostawy
Takie informacje przechowywane są w tabelach sales_flat_quote_address i sales_flat_order_address w polach pos_operator i pos_code.

## Docker demo

`docker pull bliskapaczkapl/magento-2 && docker run -d -p 8080:80 bliskapaczkapl/magento-2`

Front Magento jest dostępny po wpisaniu w przeglądarcę adresu `http://127.0.0.1:8080`.

Panel admina jest dostępny pod adresem  `http://127.0.0.1:8080/admin`, dane dostępowe to `admin/password123`. Moduł należy skonfigurować według instrukcji powyżej.

## Rozwój modułu

### Docker

W celu developmentu można uruchomić docker-compose prze komendę:

```
docker-compose -f docker-compose.yaml -f dev/docker/docker-compose.dev.yaml up
```

### Jak uruchomić testy jednostkowe 
```
docker-compose exec magento php vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/Sendit/Bliskapaczka/Test/Unit/
```

### Jak uruchomić statyczną analizę kodu
```
```
