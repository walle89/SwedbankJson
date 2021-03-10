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
    /** @var AbstractAuth */
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
     * @return object JSON decoded response from the API.
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
            {
                throw new UserException(
                    "The user is not a customer in Swedbank. Please choose one of the Sparbanken's bank types (sparbanken or sparbanken_foretag)",
                    21
                );
            }
            elseif ($output->hasSwedbankProfile AND !$output->hasSavingsbankProfile)
            {
                throw new UserException(
                    "The user is not a customer in Sparbanken. Please choose one of the Swedbank's bank types (swedbank or swedbank_foretag)",
                    22
                );
            }
            else
                throw new Exception('The profile do not contain any bank accounts.', 23);
        }
        return $output->banks[0];
    }

    /**
     * Selecting profile
     *
     * @param string $profileID ProfileID
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
     * @param string $profileID Profile ID
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
     * The money will not be sent until the transfer have been confirmed, including direct transfers.
     *
     * NOTE: Transfers that need to be signed to confirm (eg. accounts with groupID ACCOUNT_SIGNED) are currently not supported.
     * It is possible to register the transaction and than open the mobile app, to sign and confirm the transaction.
     *
     * @param float  $amount                  Amount to be transferred
     * @param string $fromAccountId           From AccountID
     * @param string $recipientAccountId      Recipient AccountID
     * @param string $fromAccountNote         From message
     * @param string $recipientAccountMessage Recipient message
     * @param string $transferDate            Date when the transfer will take place. Date format is YYYY-MM-DD (today's date onwards). If not specified, it is a direct transfer
     * @param string $periodicity             Periodicity. For possible selectable periods, see 'Periodicity' from the result of @see baseInfo()
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
     * List registered transfers
     *
     * Unconfirmed transfers waits to be approved/confirmed.
     *
     * @return object
     */
    public function listRegisteredTransfers()
    {
        return $this->_auth->getRequest('transfer/registered');
    }

    /**
     * List confirmed scheduled transfers
     *
     * Both one time feature transfers and active periodicity scheduled transfers are listed.
     * Past transfers, direct transfers and inactivated periodicity scheduled transfers are not listed.
     *
     * @return object
     */
    public function listConfirmedTransfers()
    {
        return $this->_auth->getRequest('transfer/confirmed');
    }

    /**
     * Delete a transfer
     *
     * Deletes registered and confirmed transfers.
     *
     * @param string $transferId Transaction ID. Get ID from @see listConfirmedTransfers() or @see listRegisteredTransfers()
     */
    public function deleteTransfer($transferId)
    {
        $this->_auth->getRequest('transfer/'.$transferId);
        $this->_auth->deleteRequest('transfer/'.$transferId);
    }

    /**
     * Confirms transfers
     *
     * When a transfer is confirmed, the money will be sent at the scheduled time and will be listed in @see listRegisteredTransfers().
     * With deleteTransfer(), you can cancel a transfer before the money have been sent. However confirmed direct transfers cannot be cancelled, since the money is sent
     * immediately.
     *
     * NOTE: Transfers that need to be signed to confirm (eg. accounts with groupID ACCOUNT_SIGNED) are currently not supported.
     * It is possible to open the mobile app to sign and confirm the transaction.
     *
     * @return object
     */
    public function confirmTransfers()
    {
        $transactions = $this->listRegisteredTransfers();

        if (empty($transactions->links->next->uri))
            throw new UserException('There are no registered transactions to confirm.', 55);

        // confirmTransferId
        preg_match('#transfer/confirmed/(.+)#iu', $transactions->links->next->uri, $m);
        $confirmTransferId = $m[1];

        $output = $this->_auth->putRequest('transfer/confirmed/'.$confirmTransferId);

        return $output;
    }

    /**
     * Alias for confirm transferee @see confirmTransfers()
     *
     * @deprecated Refactored to confirmTransfers().
     * @return object
     */
    public function confirmTransfer()
    {
        return $this->confirmTransfers();
    }

    /**
     * List of possible accounts for fetch quick balance
     *
     * @param string $profileID Profile ID
     *
     * @return object   List of possible quick balance accounts. Each account have a quick balance subscription ID needed for subscription.
     * @throws Exception
     */
    public function quickBalanceAccounts($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->getRequest('quickbalance/accounts');

        if (!isset($output->accounts))
            throw new Exception('Quick balance accounts cannot be listed.', 60);

        return $output;
    }

    /**
     * Subscribes a account to quick balance
     *
     * After successful subscription, it's important to save the SubscriptionID ("subscriptionId" attribute) on a permanent storage (eg. database).
     * SubscriptionID is used to fetch quick balance and when it's time to unsubscribe.
     *
     * @param string $accountQuickBalanceSubID Account quick balance subscription ID.
     *
     * @return object A successful subscription will return a subscription ID.
     * @throws Exception
     */
    public function quickBalanceSubscription($accountQuickBalanceSubID)
    {
        $output = $this->_auth->postRequest('quickbalance/subscription/'.$accountQuickBalanceSubID);

        if (!isset($output->subscriptionId))
            throw new Exception('Cannot subscribe to account. Please check the ID is from "quickbalanceSubscription".', 61);

        return $output;
    }

    /**
     * Fetch quick balance
     *
     * Will get basic balance information (eg. account total amount)
     *
     * @param string $subscriptionId Subscription ID. @see quickBalanceSubscription()
     *
     * @return object Balance information
     * @throws Exception
     */
    public function quickBalance($subscriptionId)
    {
        $output = $this->_auth->getRequest('quickbalance/'.$subscriptionId);

        if (!isset($output->balance))
            throw new Exception('Cannot fetch quick balance. Please check the Subscription ID.', 62);

        return $output;
    }

    /**
     * Unsubscribes quick balance account
     *
     * @param string $subscriptionId Subscription ID. @see quickBalanceSubscription()
     * @param string $profileID      Profile ID.
     *
     * @return object
     * @throws Exception
     */
    public function quickBalanceUnsubscription($subscriptionId, $profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->deleteRequest('quickbalance/subscription/'.$subscriptionId);

        if (!isset($output->subscriptionId))
            throw new Exception('Cannot unsubscribe account. Please check the Subscription ID.', 63);

        return $output;
    }

    /**
     * Sign out
     *
     * Alias for @see AbstractAuth::terminate()
     *
     * @return object @see AbstractAuth::terminate();
     */
    public function terminate()
    {
        return $this->_auth->terminate();
    }
}
