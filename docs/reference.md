# Reference
For more detailed information, see the phpdoc in [SwedbankJson.php](../src/SwedbankJson.php)

## Profilelist()
Lists available user profiles. Used to include switching between private account and one or more business accounts.

## Reminders()
Get the number of rejected payments, payments signed, unsigned transfers and incoming e-invoices.

## BaseInfo()
Account list grouped by type.

## AccountList([ string $profileID = '' ])
List all bank accounts available to the profile. If no profile set is selected first profile in the list.

## PortfolioList([ string $profileID = '' ])
List all bank accounts available to the profile. If ProfileID is not provided, it will fallback to default profile. 

## AccountDetails([ string $accountID = '' [, int $transactionsPerPage = 0 [, int $page = 1]]])
Shows account details and transactions for the account. If no $accountID is not provided, it will fallback to default account.

## RegisterTransfer($amount, $fromAccountId, $recipientAccountId [, $fromAccountNote = '' [, $recipientAccountMessage = '' [, $transferDate = '' [, $periodicity = 'NONE']]]])
Register a money transfer.

## ListRegisteredTransfers()
List registered money transfers.

## ListConfirmedTransfers()
List confirmed money transfers.

## DeleteTransfer( $transferId )
Delete a money transfer. You will find $transferId in ListRegisteredTransfers() or ListConfirmedTransfers().

## ConfirmTransfers()
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
Signing out the user. Also clears local cookie and session data.