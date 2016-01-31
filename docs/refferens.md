# Refferens
För mer detaljerad information, se phpdoc i [SwedbankJson.php](../src/SwedbankJson.php)

## Profilelist()
Listar tillgänliga profiler för användaren. Används för att bland kunna växla mellan privat konto och ett eller flera företagskonton.

## Reminders()
Antal avvisade betalningar, osignerade betalningar, osignerade överförningar och inkommna e-fakturor

## BaseInfo()
Lista på konton grupperade på typ

## AccountList([ string $profileID = '' ])
Listar alla bankkonton som finns tillgängliga för profilen. Om ingen profil anges väljs första profilen i listan.

## PortfolioList([ string $profileID = '' ])
Listar investeringssparande som finns tillgängliga för profilen. Om ingen profil anges väljs första profilen i listan.

## AccountDetails([ string $accoutID = '' [, int $transactionsPerPage = 0 [, int $page = 1]]])
Visar kontodetaljer och transaktioner för konto. Om inget kontoID anges väljs första kontot i listan.

## QuickBalanceAccounts([ string $profileID = '' ])
Listar möjliga snabbsaldo konton. Om ingen profil anges väljs första profilen i listan.

## QuickBalanceSubscription( string $accountQuickBalanceSubID )
Aktiverar och kopplar snabbsaldo till konto.

För att kunna visa (quickBalance()) och avaktivera (quickBalanceUnsubscription()) snabbsaldo måste man ange "subscriptionId" som finns med i resultatet.
Man bör spara undan subscriptionId i en databas eller motsvarande.

## QuickBalance( string $quickBalanceSubscriptionId )
Hämta snabbsaldo

## QuickBalanceUnsubscription( string $quickBalanceSubscriptionId [, string $profileID = '' ])
Avaktiverar snabbsaldo för konto

## Terminate()
Skickar utloggingsförfrågan till Swedbank samt rensar lokal cookie och sessions data.