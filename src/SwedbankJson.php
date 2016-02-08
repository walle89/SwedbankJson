<?php
/**
 * Wrapper för Swedbanks stänga API för mobilappar
 *
 * @package SwedbankJSON
 * @author  Eric Wallmander
 *          Date: 2012-01-01
 *          Time: 21:36
 */
namespace SwedbankJson;

use Exception;
use SwedbankJson\Auth\AbstractAuth;
use SwedbankJson\Exception\UserException;

/**
 * Class SwedbankJson
 */
class SwedbankJson
{
    /**
     * @var object Inlogging
     */
    private $_auth;

    /**
     * @var bool Håller koll på om profil är satt
     */
    private $_selectedProfileID;

    /**
     * SwedbankJson constructor.
     *
     * @param AbstractAuth $auth Instans en av inloggingsmetoderna
     */
    public function __construct(AbstractAuth $auth)
    {
        $this->_auth = $auth;
    }

    /**
     * Profilinfomation
     *
     * Få tillgång till lista av profiler och respektive tillfälliga ID-nummer. Varje privatperson och företag har egna profiler.
     *
     * @return array        JSON-avkodad data om profilen
     * @throws Exception    Fel med anrop mot Swedbank API:et
     */
    public function profileList()
    {
        $client = $this->_auth->getClient();
        if (empty($client))
            $this->_auth->login();

        $output = $this->_auth->getRequest('profile/');

        if (!isset($output->hasSwedbankProfile))
            throw new Exception('Något med profilsidan är fel.', 20);

        if (!isset($output->banks[0]->bankId))
        {
            if (!$output->hasSwedbankProfile AND $output->hasSavingsbankProfile)
                throw new UserException('Kontot är inte kopplad till Swedbank. Välj ett annat BankApp', 21);

            elseif ($output->hasSwedbankProfile AND !$output->hasSavingsbankProfile)
                throw new UserException('Kontot är inte kund i Sparbanken. Välj ett annat BankApp', 22);

            else
                throw new Exception('Profilsidan innerhåller inga bankkonton.', 23);
        }
        return $output->banks[0];
    }

    /**
     * Väljer profil
     *
     * @param string $profileID
     *
     * @return null Då när profil är redan är vald
     * @throws Exception
     * @throws UserException
     */
    private function selectProfile($profileID = '')
    {
        // Om profil inte är definerad, hämta standardprofil
        if (empty($profileID))
        {
            // Är profileID satt? Hoppa över selectProfile
            if ($this->_selectedProfileID)
                return null;

            $profiles    = $this->profileList();
            $profileData = $profiles->{$this->_auth->getProfileType()}; // Väljer privat- eller företagskonto beroende på angiven appdata user-agent

            $profileID = (isset($profileData->id)) ? $profileData->id : $profileData[0]->id;
        }

        // Väljer profil
        $this->_auth->postRequest('profile/'.$profileID);

        $this->_selectedProfileID = $profileID;
    }

    /**
     * Antal avvisade betalningar, osignerade betalningar, osignerade överförningar och inkommna e-fakturor
     *
     * @return object       Lista med antal
     * @throws Exception
     */
    public function reminders()
    {
        $this->selectProfile();

        return $this->_auth->getRequest('message/reminders');
    }

    /**
     * Lista på konton grupperade på typ
     *
     * @return object       Lista med grundläggande information om konton
     * @throws Exception
     */
    public function baseInfo()
    {
        $this->selectProfile();

        return $this->_auth->getRequest('transfer/baseinfo');
    }

    /**
     * Listar alla bankkonton som finns tillgängliga för profilen. Om ingen profil anges väljs första profilen i listan.
     *
     * @param string $profileID ProfilID
     *
     * @return object               Lista på alla konton
     * @throws \Exception           Något med API-anropet gör att kontorna inte listas
     */
    public function accountList($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->getRequest('engagement/overview');

        if (!isset($output->transactionAccounts))
            throw new Exception('Bankkonton kunde inte listas.', 30);

        return $output;
    }

    /**
     * Listar investeringssparande som finns tillgängliga för profilen. Om ingen profil anges väljs första profilen i listan.
     *
     * @param string $profileID ProfilID
     *
     * @return object           Lista på alla Investeringssparkonton
     * @throws \Exception       Något med API-anropet gör att kontorna inte listas
     */
    public function portfolioList($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->getRequest('portfolio/holdings');

        if (!isset($output->savingsAccounts))
            throw new Exception('Investeringssparkonton kunde inte listas.', 40);

        return $output;
    }

    /**
     * Visar kontodetaljer och transaktioner för konto. Om inget kontoID anges väljs första kontot i listan.
     *
     * @param $accoutID             string  Unik och slumpvis konto-id från Swedbank API
     * @param $transactionsPerPage  int     Antal transaktioner som listas "per sida". Måste vara ett heltal större eller lika med 1.
     * @param $page                 int     Aktuell sida. Måste vara ett heltal större eller lika med 1. $transactionsPerPage måste anges.
     *
     * @return object           Avkodad JSON med kontinformationn
     * @throws Exception        AccoutID inte stämmer
     */
    public function accountDetails($accoutID = '', $transactionsPerPage = 0, $page = 1)
    {
        if (empty($accoutID))
            $accoutID = $this->accountList()->transactionAccounts[0]->id;

        $query = [];
        if ($transactionsPerPage > 0 AND $page >= 1)
            $query = ['transactionsPerPage' => (int)$transactionsPerPage, 'page' => (int)$page,];

        $output = $this->_auth->getRequest('engagement/transactions/'.$accoutID, $query);

        if (!isset($output->transactions))
            throw new Exception('AccountID stämmer inte', 50);

        return $output;
    }

    /**
     * Lägg till och förbered överförning
     *
     * @param float  $amount                  Belopp att överföra
     * @param string $fromAccountId           ID för avsändarkonto
     * @param string $recipientAccountId      ID för kontomotagare
     * @param string $fromAccountNote         Notering av transaktion
     * @param string $recipientAccountMessage Meddelande för mottagare
     * @param string $transferDate            Datum när överförningen ska ske i formatet YYYY-MM-DD (dagens datum och framåt). Om inget anges, görs den direkt
     * @param string $perodicity              Periodicitet. För möjliga möjliga valbara perioder, se 'perodicity' från resultatet av @see baseInfo()
     *
     * @return object
     */
    public function registerTransfer($amount, $fromAccountId, $recipientAccountId, $fromAccountNote = '', $recipientAccountMessage = '', $transferDate = '', $perodicity = 'NONE')
    {
        $data = [
            'amount'             => number_format((float)$amount, 2, ',', ''),
            'note'               => $fromAccountNote,
            'periodicalCode'     => $perodicity,
            'message'            => $recipientAccountMessage,
            'recipientAccountId' => $recipientAccountId,
            'fromAccountId'      => $fromAccountId,
        ];

        if (!empty($transferDate))
            $data['transferDate'] = $transferDate;

        $this->_auth->postRequest('transfer/registered', $data);

        return $this->listRegisteredTransfers();
    }

    /**
     * Översikt av ej bekräftade överförningar
     *
     * @return object
     */
    public function listRegisteredTransfers()
    {
        return $this->_auth->getRequest('transfer/registered');
    }

    /**
     * Lista aktuella/framtida bekräftade överförningar
     *
     * Innehåller bland annat schemalagda och periodiska överförningar
     *
     * @return object
     */
    public function listConfirmedTransfers()
    {
        return $this->_auth->getRequest('transfer/confirmed');
    }

    /**
     * Ta bort överförning
     *
     * @param $transfareId
     */
    public function deleteTransfer($transfareId)
    {
        $this->_auth->getRequest('transfer/'.$transfareId);
        $this->_auth->deleteRequest('transfer/'.$transfareId);
    }

    /**
     * Genomför transaktioner
     *
     * @return object
     */
    public function confirmTransfer()
    {
        $transactions = $this->listRegisteredTransfers();

        if (empty($transactions->links->next->uri))
            throw new UserException('Det finns inga transaktioner att bekräfta');

        // confirmTransferId
        preg_match('#transfer/confirmed/(.+)#iu', $transactions->links->next->uri, $m);
        $confirmTransferId = $m[1];

        $output = $this->_auth->putRequest('transfer/confirmed/'.$confirmTransferId);

        return $output;
    }

    /**
     * Lista möjligar snabbsaldo konton.  Om ingen profil anges väljs första profilen i listan.
     *
     * @param string $profileID ProfilID
     *
     * @return object           Lista på snabbsaldokonton med respektive quickbalanceSubscription ID
     * @throws Exception
     */
    public function quickBalanceAccounts($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->getRequest('quickbalance/accounts');

        if (!isset($output->accounts))
            throw new Exception('Snabbsaldokonton kan inte listas.', 60);

        return $output;
    }

    /**
     * Aktiverar och kopplar snabbsaldo till konto.
     *
     * För att kunna visa (@see quickBalance()) och avaktivera (@see quickBalanceUnsubscription()) snabbsaldo måste man
     * ange "subscriptionId" som finns med i resultatet. Man bör spara undan subscriptionId i en databas eller
     * motsvarande.
     *
     * @param string $accountQuickBalanceSubID ID hämtad från @see quickBalanceAccounts(). Leta efter ID under quickbalanceSubscription
     *
     * @return object                           Bekräfltese med innehållande subscriptionId
     * @throws Exception
     */
    public function quickBalanceSubscription($accountQuickBalanceSubID)
    {
        $output = $this->_auth->postRequest('quickbalance/subscription/'.$accountQuickBalanceSubID);

        if (!isset($output->subscriptionId))
            throw new Exception('Kan ej sätta prenumeration, förmodligen fel ID av "quickbalanceSubscription"', 61);

        return $output;
    }

    /**
     * Hämta snabbsaldo
     *
     * @param string $quickBalanceSubscriptionId SubscriptionId
     *
     * @return object                       Saldoinformation
     * @throws Exception
     */
    public function quickBalance($quickBalanceSubscriptionId)
    {
        $output = $this->_auth->getRequest('quickbalance/'.$quickBalanceSubscriptionId);

        if (!isset($output->balance))
            throw new Exception('Kan ej hämta snabbsaldo. Kontrollera ID', 62);

        return $output;
    }

    /**
     * Avaktiverar snabbsaldo för konto
     *
     * @param string $quickBalanceSubscriptionId SubscriptionId
     * @param string $profileID                  ProfileID
     *
     * @return object
     * @throws Exception
     */
    public function quickBalanceUnsubscription($quickBalanceSubscriptionId, $profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->deleteRequest('quickbalance/subscription/'.$quickBalanceSubscriptionId);

        if (!isset($output->subscriptionId))
            throw new Exception('Kan ej sätta prenumeration, förmodligen fel ID av "quickbalanceSubscription"', 63);

        return $output;
    }

    /**
     * Loggar ut från API:et
     */
    public function terminate()
    {
        return $this->_auth->terminate();
    }
}