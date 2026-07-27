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
     * Get detailed information about a transaction.
     *
     * @param string $detailsTransactionID Detailed transaction ID
     *
     * @return object       Decoded JSON with detailed transaction information
     * @throws Exception    Not a valid DetailsTransactionID
     */
    public function transactionDetails($detailsTransactionID)
    {
        $output = $this->_auth->getRequest('engagement/transactions/details/'.$detailsTransactionID);

        if (!isset($output->transactionDate))
            throw new Exception('Not a valid DetailsTransactionID', 60);

        return $output;
    }

    /**
     * Information to create a payment
     *
     * Lists accounts that are can be send or receive a payment. In order to crate a payment transaction, both sender and receiver account must be selected from this list.
     *
     * The list contains following account types:
     *
     * * Payment - External accounts. Mostly Bankgiro and Plusgiro accounts typically used by organizations and companies. Used for eg. paying bills.
     * * Transfer - External bank accounts.
     * * TransactionAccountGroups - Personal/private bank accounts.
     * * InternationalRecipients - External international accounts.
     *
     * NOTE: Currently this project dose not actions that needs to be signed. In this case, adding new receptions or do payments to a external accounts require to be signed.
     *       Generally accounts listed in "TransactionAccountGroups" do not require to be signed to confirm a payment. However make sure that the sender account have "TRANSFER_FROM"
     *       scope type, and the receive account have the "TRANSFER_TO" scope type.
     *
     * For periodicity transferees, allowed types are listed under "transfare->periodicities".
     *
     * @return object Decode JSON with base payment and transfer information
     * @throws Exception
     */
    public function transferBaseInfo() {
        $this->selectProfile();

        return $this->_auth->getRequest('payment/baseinfo');
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
     * @param string $periodicity             Periodicity. For possible selectable periods, see 'Periodicity' from the result of @see transferBaseInfo()
     *
     * @return object
     */
    public function transferRegisterPayment($amount, $fromAccountId, $recipientAccountId, $fromAccountNote = '', $recipientAccountMessage = '', $transferDate = '', $periodicity = '')
    {
        $data = [
            'amount'          => number_format((float)$amount, 2, ',', ''),
            'noteToSender'    => $fromAccountNote,
            'noteToRecipient' => $recipientAccountMessage,
            'recipientId'     => $recipientAccountId,
            'fromAccountId'   => $fromAccountId,
            'date'            => $transferDate,
        ];

        if(!empty($periodicity)) {
            $data['periodicity'] = $periodicity;
        }

        $this->_auth->postRequest('payment/registered/transfer', $data);

        return $this->transferListRegistered();
    }

    /**
     * List registered transfers
     *
     * Unconfirmed transfers waits to be approved/confirmed.
     *
     * @return object
     */
    public function transferListRegistered()
    {
        return $this->_auth->getRequest('payment/registered');
    }

    /**
     * List confirmed scheduled transfers
     *
     * Both one time feature transfers and active periodicity scheduled transfers are listed.
     * Historical transfers, direct transfers and inactivated periodicity scheduled transfers are not listed.
     *
     * @return object
     */
    public function transferListConfirmed()
    {
        return $this->_auth->getRequest('payment/confirmed');
    }

    /**
     * Delete a transfer
     *
     * Deletes registered and confirmed transfers.
     *
     * @param string $transferId Transaction ID. Get ID from @see listConfirmedTransfers() or @see listRegisteredTransfers()
     */
    public function transferDeletePayment($transferId)
    {
        $this->_auth->getRequest('payment/'.$transferId);
        $this->_auth->deleteRequest('payment/'.$transferId);
    }

    /**
     * Confirms transfers
     *
     * When a transfer is confirmed, the money will be sent at the scheduled time and will be listed in @see transferListRegistered().
     * With transferDeletePayment(), you can cancel a transfer before the money have been sent. However confirmed direct transfers cannot be cancelled, since the money is sent
     * immediately.
     *
     * NOTE: Transfers that need to be signed to confirm are currently not supported.
     * It is possible to open the mobile app to sign and confirm the transaction.
     *
     * @return object
     */
    public function transferConfirmPayments()
    {
        $transactions = $this->transferListRegistered();

        if (empty($transactions->links->next->uri))
            throw new UserException('There are no registered transactions to confirm.', 55);

        // confirmTransferId
        preg_match('#payment/confirmed/(.+)#iu', $transactions->links->next->uri, $m);
        $confirmTransferId = $m[1];

        return $this->_auth->putRequest('payment/confirmed/'.$confirmTransferId);
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
     * Lists credit cards available to the profile.
     *
     * Returns all credit cards associated with the authenticated user from the mobile API.
     * Each card includes information such as card number (masked), credit limit, used credit,
     * available amount, and current balance.
     *
     * @param string $profileID Profile ID
     *
     * @return object Decoded JSON credit card list with cardAccounts array
     * @throws Exception
     */
    public function creditCardList($profileID = '')
    {
        $this->selectProfile($profileID);

        $output = $this->_auth->getRequest('card/creditcard');

        if (!isset($output->cardAccounts))
            throw new Exception('Cannot fetch credit card list.', 70);

        return $output;
    }

    /**
     * Credit card details and transactions.
     *
     * Returns transaction history for a specific credit card, including both booked transactions
     * and reserved (pending) transactions. Supports pagination for large transaction histories.
     *
     * @param string $cardID              Credit card ID. If left blank, the first credit card is chosen.
     * @param int    $transactionsPerPage Transactions per page. Default 100.
     * @param int    $page                Transaction paging index (1-based).
     *
     * @return object Decoded JSON with credit card transaction information including transactions, reservedTransactions, and creditCardDetails
     * @throws Exception Not valid CardID
     */
    public function creditCardDetails($cardID = '', $transactionsPerPage = 0, $page = 1)
    {
        // If card ID not defined, choose first credit card
        if (empty($cardID))
        {
            $cards = $this->creditCardList();
            if (empty($cards->cardAccounts) || count($cards->cardAccounts) === 0)
                throw new Exception('No credit cards available.', 71);

            $cardID = $cards->cardAccounts[0]->id;
        }

        $query = [];
        if ($transactionsPerPage > 0 AND $page >= 1)
            $query = ['transactionsPerPage' => (int)$transactionsPerPage, 'page' => (int)$page,];

        $output = $this->_auth->getRequest('card/creditcard/'.$cardID.'/transactions', $query);

        if (!isset($output->transactions))
            throw new Exception('Not a valid CardID', 72);

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
