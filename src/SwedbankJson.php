<?php
namespace SwedbankJson;

use Exception;
use SwedbankJson\Auth\AbstractAuth;
use SwedbankJson\Exception\UserException;

/**
 * Class SwedbankJson
 */
class SwedbankJson
{
    /** @var AbstractAuth  */
    private $_auth;

    /** @var string */
    private $_profileID;

    /**
     * SwedbankJson constructor.
     *
     * @param AbstractAuth $auth One of the authentication types.
     */
    public function __construct(AbstractAuth $auth)
    {
        $this->_auth = $auth;
    }

    /**
     * Profile information
     *
     * List of available user profile(s) with per session IDs. Each user can only have one private profile but several corporate profiles.
     * Profile ID is a 40 characters long string that looks like a SHA1 hash, found in privateProfile->id or corporateProfiles->id.
     *
     * @return array        JSON decoded response from the API.
     * @throws Exception
     */
    public function profileList()
    {
        $client = $this->_auth->getClient();
        if (empty($client))
            $this->_auth->login();

        $output = $this->_auth->getRequest('profile/');

        if (!isset($output->hasSwedbankProfile))
            throw new Exception('Unknown error with the profile page.', 20);

        if (!isset($output->banks[0]->bankId))
        {
            if (!$output->hasSwedbankProfile AND $output->hasSavingsbankProfile)
                throw new UserException("The user is not a customer in Swedbank. Please choose one of the Sparbanken's bank types (sparbanken, sparbanken_foretag eller sparbanken_ung)", 21);

            elseif ($output->hasSwedbankProfile AND !$output->hasSavingsbankProfile)
                throw new UserException("The user is not a customer in Sparbanken. Please choose one of the Swedbank's bank types (swedbank, swedbank_foretag eller swedbank_ung)", 22);

            else
                throw new Exception('The profile do not contain any bank accounts.', 23);
        }
        return $output->banks[0];
    }

    /**
     * Selecting profile
     *
     * @param string $profileID PorfileID
     *
     * @return null If profile already selected.
     * @throws Exception
     */
    private function selectProfile($profileID = '')
    {
        // If profile ID not defined, choose default profile
        if (empty($profileID))
        {
            if ($this->_profileID)
                return null; // If profile already selected

            $profiles    = $this->profileList();
            $profileData = $profiles->{$this->_auth->getProfileType()}; // Depending on selected bank type, it will wither choose a private or corporate profile

            $profileID = (isset($profileData->id)) ? $profileData->id : $profileData[0]->id; // Default profile
        }

        // Request profile cahnge
        $this->_auth->postRequest('profile/'.$profileID);

        $this->_profileID = $profileID;
    }

    /**
     * Reminders and notifications
     *
     * Lists number of rejected payments, payments signed, unsigned transfers and incoming e-invoices.
     *
     * @return object   Decoded JSON reminder and notification list
     * @throws Exception
     */
    public function reminders()
    {
        $this->selectProfile();

        return $this->_auth->getRequest('message/reminders');
    }

    /**
     * Account list grouped by type and periodicity types.
     *
     * There are mainly two groups; fromAccountGroup and recipientAccountGroup. As the names suggests, these groups lists accounts that money can be withdrawn from and be
     * transferred to. Something to keep in mid is check the attribute "groupId", which accounts you can transfer to without signing the transaction. Example of types:
     *
     * * ACCOUNT_SEK - Regular account
     * * ACCOUNT_SAVINGS - Savings account
     * * ACCOUNT_SIGNED - Signed receivers, accounts that somebody else owns.
     *
     * Currently money transfers to ACCOUNT_SIGNED accounts is not supported. Either is adding new signed accounts.
     *
     * For periodicity transferees, allowed types are also listed under the 'perodicity' attribute.
     *
     * @return object       Decoded JSON list of basic accounts information and periodicity types.
     * @throws Exception
     */
    public function baseInfo()
    {
        $this->selectProfile();

        return $this->_auth->getRequest('transfer/baseinfo');
    }

    /**
     * Lists accounts available to the profile.
     *
     * Type of accounts that are listed:
     *
     * * transactionAccounts - Regular accounts
     * * transactionDisposalAccounts - Disposal accounts
     * * loanAccounts - Loans (including mortgage)
     * * savingAccounts - Savings accounts
     * * cardAccounts - Credit cards
     * * cardCredit - Credit information
     *
     * NOTE: Use quickBalanceAccounts() to list quick balance accounts. Do not rely on the 'selectedForQuickbalance' attribute.
     *
     * @param string $profileID PorfileID
     *
     * @return object Decoded JSON detailed account list
     * @throws Exception
     */
    public function accountList($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->getRequest('engagement/overview');

        if (!isset($output->transactionAccounts))
            throw new Exception("Can not fetch account list.", 30);

        return $output;
    }

    /**
     * Lists investment savings available to the profile.
     *
     * @param string $profileID Profile ID
     *
     * @return object Decoded JSON investment savings list
     * @throws Exception
     */
    public function portfolioList($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->getRequest('portfolio/holdings');

        if (!isset($output->savingsAccounts))
            throw new Exception('Can not fetch investment savings list.', 40);

        return $output;
    }

    /**
     * Account details and bank statements.
     *
     * @param string $accountID           Account ID. If left blank, the default account is chosen.
     * @param int    $transactionsPerPage Bank statements per page. Default 50.
     * @param int    $page                Bank statements paging index.
     *
     * @return object Decoded JSON with account information
     * @throws Exception Not valid AccountID
     */
    public function accountDetails($accountID = '', $transactionsPerPage = 0, $page = 1)
    {
        // If account ID not defined, choose default account
        if (empty($accountID))
            $accountID = $this->accountList()->transactionAccounts[0]->id;

        $query = [];
        if ($transactionsPerPage > 0 AND $page >= 1)
            $query = ['transactionsPerPage' => (int)$transactionsPerPage, 'page' => (int)$page,];

        $output = $this->_auth->getRequest('engagement/transactions/'.$accountID, $query);

        if (!isset($output->transactions))
            throw new Exception('Not a valid AccountID', 50);

        return $output;
    }

    /**
     * Add and prepare a transfer for confirmation
     *
     * @param float  $amount                  Amount to be transferred
     * @param string $fromAccountId           From AccountID
     * @param string $recipientAccountId      Recipient AccountID
     * @param string $fromAccountNote         From message
     * @param string $recipientAccountMessage Recipient message
     * @param string $transferDate            Date when the transfer will take place. Date format is YYYY-MM-DD (today's date onwards). If not specified, it is a direct transfer
     * @param string $periodicity             Periodicity. For possible possible selectable periods, see 'Periodicity' from the result of @see baseInfo()
     *
     * @return object
     */
    public function registerTransfer($amount, $fromAccountId, $recipientAccountId, $fromAccountNote = '', $recipientAccountMessage = '', $transferDate = '', $periodicity = 'NONE')
    {
        $data = [
            'amount'             => number_format((float)$amount, 2, ',', ''),
            'note'               => $fromAccountNote,
            'periodicalCode'     => $periodicity,
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
            throw new UserException('Det finns inga transaktioner att bekräfta', 55);

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