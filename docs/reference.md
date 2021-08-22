# Reference
For more detailed information, see the phpdoc in [SwedbankJson.php](../src/SwedbankJson.php)

## Profilelist()
Lists available user profiles. Used to include switching between private account and one or more business accounts.

## Reminders()
Get the number of rejected payments, payments signed, unsigned transfers and incoming e-invoices.

## AccountList([ string $profileID = '' ])
List all bank accounts available to the profile. If no profile set is selected first profile in the list.

## PortfolioList([ string $profileID = '' ])
List all bank accounts available to the profile. If ProfileID is not provided, it will fallback to default profile. 

## AccountDetails( string $detailsTransactionID = '' )
Shows account details and transactions for the account. If no $accountID is not provided, it will fallback to default account.

## TransactionDetails( string $detailsTransactionID )
Shows additional information about a specific transaction.

## TransferBaseInfo()
List accounts that can send or receive payment.

## TransferRegisterPayment( float $amount, string $fromAccountId, string $recipientAccountId [, string $fromAccountNote = '' [, string $recipientAccountMessage = '' [, string $transferDate = '' [, string $periodicity = '']]]])
Register a money transfer.

## TransferListRegistered()
List registered money transfers.

## TransferListConfirmed()
List confirmed money transfers.

## TransferDeletePayment( string $transferId )
Delete a money transfer. You will find $transferId in TransferListRegistered() or TransferListConfirmed().

## TransferConfirmPayments()
Execute registered money transfers.

## QuickBalanceAccounts([ string $profileID = '' ])
Lists possible quick balance accounts. If ProfileID is not provided, it will fallback to default profile. 

## QuickBalanceSubscription( string $accountQuickBalanceSubID )
Activates and connects quick balance to account.

To view (quickBalance()) and inactivate (quickBalanceUnsubscription()) quick balance subscriptionID must be provided. The result of this API will return a subscriptionID. 
You should save subscriptionID in a database.

## QuickBalance( string $quickBalanceSubscriptionId )
Gets the quick balance.

## QuickBalanceUnsubscription( string $quickBalanceSubscriptionId [, string $profileID = '' ])
Disables quick balance for account.

## Terminate()
Signing out the user. Also, clears the local cookie and session data.