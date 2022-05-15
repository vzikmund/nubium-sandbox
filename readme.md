# Nubium Sandbox

Mini aplikace obsahuje výpis článků. Podporuje přihlášení a registraci uživatele. Při inicializaci databáze jsou
definováni 3 uživatelé.

První dva uživatelé mají účty aktivní, třetí má účet deaktivovaný a nelze se za něj přihlásit. 
Kontrola stavu účtu je realtime v `BasePresenter.php`, tzn. při změně flagu v DB, aplikace přihlášeného uživatele odhlásí.

| login  | pass      |  is_active  |
|--------|-----------|:-----------:|
| user_1 | 123456789 |      1      |
| user_2 | 123456789 |      1      |
| user_3 | 123456789 |      0      |

Celý SQL skript včetně vytvoření tabulek a jejich naplnění je v
`/database/init/init.sql`.

## Prerekvizity

- Composer
- Docker

## Instalace
1. stažení repozitáře z gitu
2. stažení composer balíčků zavoláním `composer install` ve složce `/nette`, kde se nachází aplikace
3. spuštění dockeru `docker compose up -d` z kořenové složky, ve které se nachází `docker-compose.yml`.
4. vložení řádku `127.0.0.1 nubium-sandbox.test` do souboru `/etc/hosts`
5. web je nyní dostupný na `http://nubium-sandbox.test/`
6. phpMyAdmin na `localhost:8081`

## Co by stálo za vylepšení

- Chybové hlášky z `Nette forms` vypsané v alertu by bylo vhodné předělat do jiného stylu zobrazení (např. text pod chybovým inputem)
- Řazení je pro jednoduchost pouze `DESC` a v případě hodnocení jsou nehodnocené články řazeny až pod záporná hodnocení. Možná by bylo vhodné zapracovat na pořadí. Nejdříve vysoká, následně nehodnocené a poté záporné.
- Tabulka článků obsahuje sloupec `link` a `content`. Je tedy připravena na zobrazení článku podle jeho adresy, ale detail článku není naprogramován.
- V tabulce článků je více stavů. Stav `private` by byl zobrazen jen autorovi, dokud by článek nepublikoval (nepřesunul do stavu `public`). Stav `archived` by sloužil pro "odložení" článků do jiného hrnečku, pokud bychom jej již nechtěli v hlavním vlákně, ale chtěli bychom zachovat funkčnost linku kvůli SEO.
- S Dockerem jsem přišel do styku úplně poprvé. Tak tam je určitě také prostor na zlepšení. Min. zprovoznění `HTTPS` pro nginx, nad kterým jsem zlomit hůl, a proto projekt funguje jen na `HTTP`.

## Doba programování

| Popis                       |                                                            Čas |  
|-----------------------------|---------------------------------------------------------------:|
| Inicializace Dockeru        | raději nepočítám, <br/>ale bylo zajímavé zkusit něco nového :) |
| Inicializace DB a projektu  |                                                       40 minut | 
| Registrace                  |                                                       1 hodina | 
| Přihlášení, navigace        |                                                       20 minut |
| Vypiš článků                |                                                       1 hodina |
| Paginace a hodnocení AJAXem |                                                       30 minut |
| Jednoduché řazení           |                                                       20 minut |
| **Celkem**                  |                                                  **~4 hodiny** |