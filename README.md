Job Task
---

# PHP programátor - praktický test

Aplikace kontakty

## Obecné informace

V BlueGhostu se snažíme mít standardizovaná zadání pro velké projekty i menší funkcionality,
níže najdete ukázku takové “specifikace”, kterou můžete obdržet od projektového manažera a
kterou budete mít za úkol odbavit. S projekťákem máte samozřejmě možnost vždy konzultovat,
nicméně toto zadání si myslím, že konzultaci nutně nepotřebuje. Pokud byste si s něčím nevěděli
rady nebo nebylo zadání jednoznačné, připravte aplikaci dle vašeho nejlepšího vědomí a
svědomí a při odevzdávání to napište do readme nebo průvodního mailu.

## Specifikace úkolu

### Aplikační logika

Aplikace bude fungovat jako jednoduchý adresář. V aplikaci bude možné vytvářet, mazat a
editovat jednotlivé kontakty, které budou mít tyto položky:

- Jméno (povinná položka)
- Příjmení (povinná položka)
- Telefonní číslo
- Email (povinná položka)
- Dlouhá poznámka

Formulář pro správu kontaktů bude obsahovat backendovou validaci polí dle popisu výše. Do
validátoru přidejte také kontrolu formátu zadaného emailu

### Prezentační vrstva

Pro aplikaci není připraven grafický návrh – budou použity základní HTML prvky bez CSS, pro
zobrazování dat budou použity tabulky.

Aplikace bude SEO optimalizována. Na URL (/) bude seznam všech kontaktů, editace jednotlivých
kontaktů bude na URL (/identifikator-kontaktu). Identifikátor bude obsahovat jméno kontaktu.
Mazání kontaktu může probíhat na editaci kontaktu nebo může být dostupné přes jiný odkaz –
ten nemusí být SEO optimalizován (stačí ID).

Seznam všech kontaktů bude stránkovaný s číselnou navigací stránek a tlačítky pro přechod na
další a předchozí stránku. Stránkování může být jednoduché s reloadem stránky. Volitelně
můžete stránkování implementovat pomocí AJAXu. I v tomto případě musí být ale zachována
funkce historie prohlížeče. Tzn. při přechodu na další stránku AJAXem dojde ke změně v
adresním řádku v prohlížeči.

Poznámka může kontaktu může být dlouhý text, který se nehodí přímo do přehledu kontaktů.
Nicméně i na této stránce je nutné mít k poznámce přístup. V seznamu kontaktů bude tlačítko na
zobrazení poznámky. Při kliknutí na toto tlačítko se zobrazí jednoduchý popup nebo modal okno
s poznámkou kontaktu. Toto okno půjde zavřít klávesou ESC, klikem kamkoliv mimo popup a
tlačítkem “Zavřít” uvnitř popupu.

### Interní poznámky - rady a tipy jak na to :)

Při vytváření aplikace dbejte na základní pravidla programování a myslete zejména na
bezpečnost, znovupoužitelnost a rozšiřitelnost. Aplikaci vytvářejte jako byste byli v týmu a bylo
třeba aby na vašem kódu později pokračovali další kolegové.

- Aplikaci postavte na frameworku Symfony 5+ a PHP 7.4+.
- Používat lze veškeré běžně dostupné knihovny pro PHP i frontend.
- Je nutné používat GIT pro verzování kódu.
- Do README.md uvěďte postup pro lokální instalaci aplikace tak, aby ji zadavatel mohl
otestovat.

Pro zadavatele je důležité, jak je aplikace napsána, nikoliv kolik funkcí má navíc. Prosím tedy o
minimalizaci času nad ní stráveného a sdělení kolik času bylo třeba, aby byla aplikace uvedena
do stavu, který budete posílat.

## Spustění
### Docker kontejnery
vytvoření všech docker containerů
```
docker-compose up --build -d
```
### struktura DB 
pro vytvoření základní struktury databáze je nejprve nutné se přihlísit do kontejneru s DB
```
docker-compose exec blueghost-db bash
```
a pak je potřeba spustit skript na vytvoření DB
```
mysql -uroot -pblueghost < /var/install/install.sql && exit
```

## Vzpracování zadání
Celkově mě zpracování zadání trvalo kolem 3-4 hodin. Z toho asi 50%-70% času bylo dohledávání informací z dokumentací a článků. Zbylý čas bylo na vlastní psaní kódu nebo využívání předdefinovaných funkcí.

K tomu další 3 hodiny byly na vytvoreni docker prostredi pro Symfony a zprovozneni vnitřních vazeb Docker sítě.

### Jednotlivé části
#### Vytvoření databáze
Vytvoření databáze jsem provedl pomocí `make:entity`, `make:migration` a `doctrine:migrations:migrate`

Doba: 20 min

#### Vytvoření databáze
Dále jsem vytvořil CRUD znovu za využití symfony vestavěnych funkcí `make:crud`

Doba: 15 min
#### Vytvoření slugu pro URL
Pro SEO optimalizovana URL jsem se rozhodl u entity `Contact` vytvořit `slug` pro snadné a automatizované vytváření "user friendly" URL podle návodu, který jsem našel na internetu.

Doba: 40 min

#### Paginace
Poté co jsem si připomněl, že existuje Knp Bundle, který toto pokrývá jsem ho nasadil a pak se chvíli trápil než jsem si uvědomil, že špatně pracuji s EntityManagerem, ale vše hned fungovalo.

Doba: 45 min
#### Modal
Modalové okno mi trvalo nejdéle, protože jsem musel napojit přes Encore správné balíky a poté lépe porozumnět jak Java Scriptu, tak i Stimulusu

Doba: 90 - 120 min

### Testy

Jako poslední jsem jenom přidal resp. upravil vygenerované testy, aby odpovídaly změnám v controlleru a entitě 

- v controlleru jsem změnil routy z `id` na `slug`
- v entitě jsem přidal podmínku, že `email` musí mít formát emailu

### Příprava Dockeru a prostředí
Příprava dockeru částečně z mého starého repozitáře [GitLab Jobs](https://gitlab.com/lamaweb-job/ulozto) a aktualizace PHP, NGINX a zmena na Symfony 5 trvala asi 3 hodiny.