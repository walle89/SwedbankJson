# Response samples

* [Introduction](#introduction)
* [Profile List](#profile-list)
* [Reminders](#reminders)
* [Baseinfo](#baseinfo)
* [Account List](#account-list)
* [Portfolio List](#portfolio-list)
* [Account Details](#account-details)
* [Register Transfer](#register-transfer)
* [List Registered Transfers](#list-registered-transfers)
* [List Confirmed Transfers](#list-confirmed-transfers)
* [Quick Balance Accounts](#quick-balance-accounts)
* [Quick Balance Subscription](#quick-balance-subscription)
* [Quick Balance](#quick-balance)
* [Quick Balance Unsubscription](#quick-balance-unsubscription)

## Introduction
All response samples are meant to as a reference to what output to be expected from the Swedbank API. It's an additional resource to [the introduction](/INSTALL.md).

Many parameter names and its content are mostly self-explanatory. Parameters that needs additional information, there are noted in a table for each response sample. 

### Note about IDs and URIs
All "id" and "uri" parameters below are per session temporary unique strings. They can not and should not be saved and reused in any way.
Depending on what API is used, there are other parameters that can be used as a persistent id between sessions. For more info, please [read the introduction](/INSTALL.md).

## Profile List
| Parameter | Description |
| --- | --- |
| bankId | Internal reference ID to which bank the customer belongs to. | 
| privateProfile | Containing the user's personal accounts and services. | 
| id | Per session temporary unique strings. Only used for referring an API action. |
| links | Used for internals of the API. New URIs for each session. |
| corporateProfiles | If the user have business accounts, it will be listed here. |

    stdClass Object
    (
        [name] => Swedbank AB (publ)
        [bankId] => 99999
        [privateProfile] => stdClass Object
            (
                [activeProfileLanguage] => en
                [id] => cc2087a7d835d6e47aFAKEcc8f3aac3eb0ad8953
                [bankId] => 99999
                [customerNumber] => 19890306-0000
                [bankName] => Swedbank AB (publ)
                [customerName] => Zoe Green
                [links] => stdClass Object
                    (
                        [next] => stdClass Object
                            (
                                [method] => POST
                                [uri] => /v4/profile/cc2087a7d835d6e47aFAKEcc8f3aac3eb0ad8953
                            )
    
                        [edit] => stdClass Object
                            (
                                [method] => PUT
                                [uri] => /v4/profile/subscription/cc2087a7d835d6e47aFAKEcc8f3aac3eb0ad8953
                            )
    
                    )
    
            )
    
        [corporateProfiles] => Array
            (
            )
    
    )
    
## Reminders
| Parameter | Description |
| --- | --- |
| links | Used for internals of the API. New URIs for each session. |

    stdClass Object
    (
        [rejectedPayments] => stdClass Object
            (
                [links] => stdClass Object
                    (
                        [next] => stdClass Object
                            (
                                [method] => GET
                                [uri] => /v4/payment/rejected
                            )
    
                    )
    
                [count] => 0
            )
    
        [renewableMortgages] => stdClass Object
            (
                [links] => stdClass Object
                    (
                        [next] => stdClass Object
                            (
                                [method] => GET
                                [uri] => /v4/lending/mortgageLoan/overview
                            )
    
                    )
    
                [count] => 0
                [loansNumbers] => Array
                    (
                    )
    
            )
    
        [unsignedPayments] => stdClass Object
            (
                [links] => stdClass Object
                    (
                        [next] => stdClass Object
                            (
                                [method] => GET
                                [uri] => /v4/payment/registered
                            )
    
                    )
    
                [count] => 0
            )
    
        [unsignedTransfers] => stdClass Object
            (
                [links] => stdClass Object
                    (
                        [next] => stdClass Object
                            (
                                [method] => GET
                                [uri] => /v4/transfer/registered
                            )
    
                    )
    
                [count] => 0
            )
    
        [incomingEinvoices] => stdClass Object
            (
                [links] => stdClass Object
                    (
                        [next] => stdClass Object
                            (
                                [method] => GET
                                [uri] => /v4/einvoice/incoming
                            )
    
                    )
    
                [count] => 0
            )
    
        [openSigningOrders] => stdClass Object
            (
                [links] => stdClass Object
                    (
                        [next] => stdClass Object
                            (
                                [method] => GET
                                [uri] => /v4/signing/edocument/registered
                            )
    
                    )
    
                [count] => 0
            )
    
    )

## Baseinfo
| Parameter | Description |
| --- | --- |
| id | Per session temporary unique strings. Only used for referring an API action. |
| fullyFormattedNumber | Persistent unique identifier for the account. |
| perodicity | Alternatives for register periodicity transfers. See [Transfer money](/INSTALL.md#transfer-money) |
| links | Used for internals of the API. New URIs for each session. |

    stdClass Object
    (
        [fromAccountGroup] => Array
            (
                [0] => stdClass Object
                    (
                        [name] => Konton i svenska kronor
                        [groupId] => ACCOUNT_SEK
                        [accounts] => Array
                            (
                                [0] => stdClass Object
                                    (
                                        [balance] => 27 555,90
                                        [name] => Salary
                                        [id] => ee127fa99638283ecFAKEadac85ebc848b19cac2
                                        [accountNumber] => 555 123 456-7
                                        [clearingNumber] => 5555-9
                                        [fullyFormattedNumber] => 5555-9,555 123 456-7
                                        [availableForTags] => Array
                                            (
                                                [0] => DEFAULT_FROM_ACCOUNT
                                            )
    
                                        [isDefault] => 1
                                    )
    
                            )
    
                    )
    
                [1] => stdClass Object
                    (
                        [name] => Sparkonton
                        [groupId] => ACCOUNT_SAVINGS
                        [accounts] => Array
                            (
                                [0] => stdClass Object
                                    (
                                        [balance] => 79 555,52
                                        [name] => Vacation
                                        [id] => aa127fa99638283ecFAKEadac85ebc848b19bve8
                                        [accountNumber] => 555 789 012-3
                                        [clearingNumber] => 5555-9
                                        [fullyFormattedNumber] => 5555-9,555 789 012-3
                                        [availableForTags] => Array
                                            (
                                                [0] => DEFAULT_FROM_ACCOUNT
                                            )
    
                                        [isDefault] => 
                                    )
                            )
    
                    )
    
            )
    
        [recipientAccountGroup] => Array
            (
                [0] => stdClass Object
                    (
                        [name] => Konton i svenska kronor
                        [groupId] => ACCOUNT_SEK
                        [accounts] => Array
                            (
                                [0] => stdClass Object
                                    (
                                        [balance] => 27 555,90
                                        [name] => Salary
                                        [id] => ee127fa99638283ecFAKEadac85ebc848b19cac2
                                        [accountNumber] => 555 123 456-7
                                        [clearingNumber] => 5555-9
                                        [fullyFormattedNumber] => 5555-9,555 123 456-7
                                        [availableForTags] => Array
                                            (
                                            )
    
                                        [isDefault] => 
                                    )
    
                            )
    
                    )
    
                [1] => stdClass Object
                    (
                        [name] => Sparkonton
                        [groupId] => ACCOUNT_SAVINGS
                        [accounts] => Array
                            (
                                [0] => stdClass Object
                                    (
                                        [balance] => 79 555,52
                                        [name] => Vacation
                                        [id] => aa127fa99638283ecFAKEadac85ebc848b19bve8
                                        [accountNumber] => 555 789 012-3
                                        [clearingNumber] => 5555-9
                                        [fullyFormattedNumber] => 5555-9,555 789 012-3
                                        [availableForTags] => Array
                                            (
                                            )
    
                                        [isDefault] => 
                                    )
    
                            )
    
                    )
    
                [2] => stdClass Object
                    (
                        [name] => Signerade mottagare
                        [groupId] => ACCOUNT_SIGNED
                        [accounts] => Array
                            (
                                [0] => stdClass Object
                                    (
                                        [name] => Tonari von Totoro
                                        [id] => abc294c1dd55cFAKE386cb4d41a536ff9c9ae8e7
                                        [accountNumber] => 555 345 678-9
                                        [clearingNumber] => 5555-9
                                        [fullyFormattedNumber] => 5555-9,555 345 678-9
                                        [availableForTags] => Array
                                            (
                                            )
    
                                        [isDefault] => 
                                    )
    
                            )
    
                    )
    
            )
    
        [perodicity] => Array
            (
                [0] => NONE
                [1] => WEEKLY
                [2] => EVERY_OTHER_WEEK
                [3] => MONTHLY
                [4] => EVERY_OTHER_MONTH
                [5] => QUARTERLY
                [6] => SEMI_ANNUALLY
                [7] => ANNUALLY
            )
    
        [addRecipientStatus] => stdClass Object
            (
                [allowed] => 
                [notAllowedMessage] => stdClass Object
                    (
                        [message] => För att lägga till mottagare med Mobilt BankID behöver du aktivera Mobilt BankID för utökad användning.<br><br>Detta gör du i internetbanken på sidan BankID.
                        [headline] => Du har inte aktiverat utökad användning för Mobilt BankID
                    )
    
            )
    
        [links] => stdClass Object
            (
                [next] => stdClass Object
                    (
                        [method] => POST
                        [uri] => /v4/transfer/registered
                    )
    
            )
    
    )

## Account List
| Parameter | Description |
| --- | --- |
| links | Used for internals of the API. New URIs for each session. |
| details | Used for internals of the API. New URIs for each session. |
| id | Per session temporary unique strings. Only used for referring an API action. |
| fullyFormattedNumber | Persistent unique identifier for the account. |

    stdClass Object
    (
        [transactionAccounts] => Array
            (
                [0] => stdClass Object
                    (
                        [selectedForQuickbalance] => 
                        [links] => stdClass Object
                            (
                                [next] => stdClass Object
                                    (
                                        [method] => GET
                                        [uri] => /v4/engagement/transactions/e948eacd2f366c2fd8b8bFAKE7300b39a441248c
                                    )
    
                            )
    
                        [name] => Salary
                        [priority] => 1
                        [id] => e948eacd2f366c2fd8b8bFAKE7300b39a441248c
                        [currency] => SEK
                        [details] => stdClass Object
                            (
                                [links] => stdClass Object
                                    (
                                        [next] => stdClass Object
                                            (
                                                [method] => GET
                                                [uri] => /v4/engagement/account/e948eacd2f366c2fd8b8bFAKE7300b39a441248c
                                            )
    
                                    )
    
                            )
    
                        [balance] => 7 615,90
                        [accountNumber] => 555 123 456-7
                        [clearingNumber] => 5555-9
                        [fullyFormattedNumber] => 5555-9,555 123 456-7
                        [originalName] => Privatkonto
                        [availableForFavouriteAccount] => 1
                        [availableForPriorityAccount] => 1
                        [favouriteAccount] => 1
                    )
    
            )
    
        [transactionDisposalAccounts] => Array
            (
            )
    
        [loanAccounts] => Array
            (
                [0] => stdClass Object
                    (
                        [links] => stdClass Object
                            (
                                [next] => stdClass Object
                                    (
                                        [method] => GET
                                        [uri] => /v4/engagement/loandetail/ae164aca51ebc3FAKEe4e0279842d2d3101951c5
                                    )
    
                            )
    
                        [name] => Bolån
                        [id] => ae164aca51ebc3FAKEe4e0279842d2d3101951c5
                        [currency] => SEK
                        [balance] => -555 555,00
                        [accountNumber] => 555 678 901-2
                        [clearingNumber] => 5555-9
                        [fullyFormattedNumber] => 5555-9,555 678 901-2
                        [availableForFavouriteAccount] => 
                        [availableForPriorityAccount] => 
                    )
    
            )
    
        [savingAccounts] => Array
            (
                [0] => stdClass Object
                    (
                        [selectedForQuickbalance] => 
                        [links] => stdClass Object
                            (
                                [next] => stdClass Object
                                    (
                                        [method] => GET
                                        [uri] => /v4/engagement/transactions/bve8be6cfbc8199a9fFAKEc324d1d528f1c1082
                                    )
    
                            )
    
                        [name] => Vacation
                        [priority] => 2
                        [id] => bve8be6cfbc8199a9fFAKEc324d1d528f1c1082
                        [currency] => SEK
                        [details] => stdClass Object
                            (
                                [links] => stdClass Object
                                    (
                                        [next] => stdClass Object
                                            (
                                                [method] => GET
                                                [uri] => /v4/engagement/account/bve8be6cfbc8199a9fFAKEc324d1d528f1c1082
                                            )
    
                                    )
    
                            )
    
                        [balance] => 79 555,52
                        [accountNumber] => 555 789 012-3
                        [clearingNumber] => 5555-9
                        [fullyFormattedNumber] => 5555-9,555 789 012-3
                        [availableForFavouriteAccount] => 1
                        [availableForPriorityAccount] => 1
                    )
    
            )
    
        [cardAccounts] => Array
            (
            )
    
        [cardCredit] => stdClass Object
            (
            )
    
    )

## Portfolio List
| Parameter | Description |
| --- | --- |
| savingsGoals | Swedbank's saving goal service |
| id | Per session temporary unique strings. Only used for referring an API action. |
| fullyFormattedNumber | Persistent unique identifier for the account. |
| links | Used for internals of the API. New URIs for each session. |

    stdClass Object
    (
        [errorCode] => 0
        [savingsGoals] => Array
            (
                [0] => stdClass Object
                    (
                        [name] => Vacation 2017
                        [id] => 0b24ffb95894912dFAKEc461b8c844cc1c81d354
                        [goalAmount] => stdClass Object
                            (
                                [currencyCode] => SEK
                                [amount] => 30 000,00
                            )
    
                        [targetDate] => 2017-06-26
                        [fullyFormattedNumber] => 5555-9,555 789 012-3
                        [amountLeft] => stdClass Object
                            (
                                [currencyCode] => SEK
                                [amount] => 28 500,00
                            )
    
                        [savedAmount] => stdClass Object
                            (
                                [currencyCode] => SEK
                                [amount] => 1 500,00
                            )
    
                        [links] => stdClass Object
                            (
                                [delete] => stdClass Object
                                    (
                                        [method] => DELETE
                                        [uri] => /v4/savingsgoal/0b24ffb95894912dFAKEc461b8c844cc1c81d354
                                    )
    
                            )
    
                    )
    
            )
    
        [serverTime] => 12:32
        [fundAccounts] => Array
            (
            )
    
        [savingsAccounts] => Array
            (
                [0] => stdClass Object
                    (
                        [details] => stdClass Object
                            (
                                [links] => stdClass Object
                                    (
                                        [next] => stdClass Object
                                            (
                                                [method] => GET
                                                [uri] => /v4/engagement/account/ab62ba92e0712ef68FAKEed11f5b573edcedwa90
                                            )
    
                                    )
    
                            )
    
                        [dispositionRight] => 
                        [name] => Vacation
                        [id] => ab62ba92e0712ef68FAKEed11f5b573edcedwa90
                        [balance] => stdClass Object
                            (
                                [currencyCode] => SEK
                                [amount] => 79 555,52
                            )
    
                        [accountNumber] => 555 789 012-3
                        [clearingNumber] => 5555-9
                        [fullyFormattedNumber] => 5555-9,555 789 012-3
                        [links] => stdClass Object
                            (
                                [next] => stdClass Object
                                    (
                                        [method] => GET
                                        [uri] => /v4/engagement/transactions/ab62ba92e0712ef68FAKEed11f5b573edcedwa90
                                    )
    
                            )
    
                    )
    
            )
    
        [endowmentInsurances] => Array
            (
            )
    
        [investmentSavings] => Array
            (
            )
    
        [equityTraders] => Array
            (
            )
    
    )

## Account Details
| Parameter | Description |
| --- | --- |
| id | Per session temporary unique strings. Only used for referring an API action. |
| links | Used for internals of the API. New URIs for each session. |
| fullyFormattedNumber | Persistent unique identifier for the account. |

    stdClass Object
    (
        [account] => stdClass Object
            (
                [reservedAmount] => -1 759,94
                [availableAmount] => 7 615,90
                [creditGranted] => 0,00
                [quickbalanceSubscription] => stdClass Object
                    (
                        [id] => e9fb6aada670a7FAKEd34ed90297748b8069badb
                        [active] => 
                        [links] => stdClass Object
                            (
                                [next] => stdClass Object
                                    (
                                        [method] => POST
                                        [uri] => /v4/quickbalance/subscription/e9fb6aada670a7FAKEd34ed90297748b8069badb
                                    )
    
                            )
    
                    )
    
                [currencyAccount] => 
                [internalAccount] => 
                [name] => Salary
                [id] => 29baec9d3f2a478866FAKEebb5d3948ee4c4c9d3
                [currency] => SEK
                [balance] => 7 615,90
                [accountNumber] => 555 123 456-7
                [clearingNumber] => 5555-9
                [fullyFormattedNumber] => 5555-9,555 123 456-7
                [expenseControl] => stdClass Object
                    (
                        [status] => ACTIVE
                        [viewCategorizations] => 1
                        [links] => stdClass Object
                            (
                                [delete] => stdClass Object
                                    (
                                        [method] => DELETE
                                        [uri] => /v4/expensecontrol/accounts/29baec9d3f2a478866FAKEebb5d3948ee4c4c9d3
                                    )
    
                                [edit] => stdClass Object
                                    (
                                        [method] => PUT
                                        [uri] => /v4/expensecontrol/accounts/29baec9d3f2a478866FAKEebb5d3948ee4c4c9d3
                                    )
    
                            )
    
                    )
    
                [originalName] => Privatkonto
                [availableForFavouriteAccount] => 
                [availableForPriorityAccount] => 
            )
    
        [numberOfTransactions] => 50
        [reservedTransactions] => Array
            (
                [0] => stdClass Object
                    (
                        [date] => 2016-07-19
                        [description] => SKYDDAT BELOPP
                        [currency] => SEK
                        [amount] => -150,44
                    )
            )
    
        [transactions] => Array
            (
                [0] => stdClass Object
                    (
                        [date] => 2016-07-19
                        [description] => MALMART
                        [currency] => SEK
                        [amount] => -122,23
                        [expenseControlIncluded] => UNAVAILABLE
                    )
    
                [1] => stdClass Object
                    (
                        [id] => ea6c5FAKE305112b955ee1c34618f60279ebea6c
                        [date] => 2016-07-17
                        [description] => VERIZON
                        [currency] => SEK
                        [amount] => -52,83
                        [expenseControlIncluded] => INCLUDED
                        [labelings] => stdClass Object
                            (
                                [links] => stdClass Object
                                    (
                                        [next] => stdClass Object
                                            (
                                                [method] => POST
                                                [uri] => /v4/expensecontrol/transactions/ea6c5FAKE305112b955ee1c34618f60279ebea6c/labels
                                            )
    
                                    )
    
                                [labels] => Array
                                    (
                                        [0] => stdClass Object
                                            (
                                                [links] => stdClass Object
                                                    (
                                                        [delete] => stdClass Object
                                                            (
                                                                [method] => DELETE
                                                                [uri] => /v4/expensecontrol/transactions/ea6c5FAKE305112b955ee1c34618f60279ebea6c/labels71a3d963064FAKEe73d51150cd7c64db6b98adc2
                                                            )
    
                                                    )
    
                                                [name] => Bankkort
                                                [id] => 71a3d963064FAKEe73d51150cd7c64db6b98adc2
                                                [type] => SYSTEM
                                            )
    
                                    )
    
                            )
    
                        [categorizations] => stdClass Object
                            (
                                [links] => stdClass Object
                                    (
                                        [edit] => stdClass Object
                                            (
                                                [method] => PUT
                                                [uri] => /v4/expensecontrol/transactions/ea6c5FAKE305112b955ee1c34618f60279ebea6c/categories
                                            )
    
                                    )
    
                                [categories] => Array
                                    (
                                        [0] => stdClass Object
                                            (
                                                [name] => TV, bredband, telefoni
                                                [group] => ID_ACCOMMODATION
                                                [id] => 7245a544ddc4086a3b291bFAKE19599ade6f9498
                                                [amount] => -52,83
                                            )
    
                                    )
    
                            )
    
                        [accountingDate] => 2016-07-18
                        [accountingBalance] => stdClass Object
                            (
                                [currencyCode] => SEK
                                [amount] => 7 615,90
                            )
    
                    )
    
            )
    
        [uncategorizedExpenseTransactions] => 1
        [uncategorizedIncomeTransactions] => 0
        [uncategorizedSubcategoryId] => eeee5d38920af1ef86FAKE0dfa271c06b1503fff
        [uncategorizedSortOfReceivers] => 1
        [moreTransactionsAvailable] => 1
        [numberOfReservedTransactions] => 2
        [numberOfBankGiroPrognosisTransactions] => 0
        [bankGiroPrognosisTransactions] => Array
            (
            )
    
        [links] => stdClass Object
            (
                [next] => stdClass Object
                    (
                        [method] => GET
                        [uri] => /v4/engagement/transactions/29baec9d3f2a478866FAKEebb5d3948ee4c4c9d3?transactionsPerPage=50&amp;page=2
                    )
    
            )
    
    )

## Register Transfer
| Parameter | Description |
| --- | --- |
| links | Used for internals of the API. New URIs for each session. |
| id | Per session temporary unique strings. Only used for referring an API action. |
| fullyFormattedNumber | Persistent unique identifier for the account. |

    stdClass Object
    (
        [links] => stdClass Object
            (
                [next] => stdClass Object
                    (
                        [method] => PUT
                        [uri] => /v4/transfer/confirmed/Pz8UTp5eH0+Pz8tPz94Pz4yFAKEOltCQDVkZjUwZjFhOj81Pw==
                    )
    
            )
    
        [totalSum] => 5,05
        [transferGroups] => Array
            (
                [0] => stdClass Object
                    (
                        [sum] => 5,05
                        [transfers] => Array
                            (
                                [0] => stdClass Object
                                    (
                                        [links] => stdClass Object
                                            (
                                                [self] => stdClass Object
                                                    (
                                                        [method] => GET
                                                        [uri] => /v4/transfer/0828c9FAKE83269427bfcfdfec143068e615abfc
                                                    )
    
                                                [edit] => stdClass Object
                                                    (
                                                        [method] => PUT
                                                        [uri] => /v4/transfer/0828c9FAKE83269427bfcfdfec143068e615abfc
                                                    )
    
                                            )
    
                                        [id] => 0828c9FAKE83269427bfcfdfec143068e615abfc
                                        [to] => stdClass Object
                                            (
                                                [name] => Tickets
                                                [accountNumber] => 555 098 765-1
                                                [clearingNumber] => 5555-9
                                                [fullyFormattedNumber] => 5555-9,555 098 765-1
                                                [availableForTags] => Array
                                                    (
                                                    )
    
                                            )
    
                                        [from] => stdClass Object
                                            (
                                                [name] => Vacation
                                                [accountNumber] => 555 789 012-3
                                                [clearingNumber] => 5555-9
                                                [fullyFormattedNumber] => 5555-9,555 789 012-3
                                                [availableForTags] => Array
                                                    (
                                                    )
    
                                            )
    
                                        [amount] => 5,05
                                        [periodicity] => NONE
                                        [transferDate] => 
                                        [dateDependency] => DIRECT
                                    )
    
                            )
    
                        [groupingAccount] => stdClass Object
                            (
                                [balance] => 79 555,52
                                [name] => Vacation
                                [accountNumber] => 555 789 012-3
                                [clearingNumber] => 5555-9
                                [fullyFormattedNumber] => 5555-9,555 789 012-3
                                [availableForTags] => Array
                                    (
                                    )
    
                            )
    
                    )
    
            )
    
    )

## List Registered Transfers
See [Register Transfer](#register-transfer).

## List Confirmed Transfers
| Parameter | Description |
| --- | --- |
| id | Per session temporary unique strings. Only used for referring an API action. |
| fullyFormattedNumber | Persistent unique identifier for the account. |
| links | Used for internals of the API. New URIs for each session. |

    stdClass Object
    (
        [totalSum] => 1 250,00
        [transferGroups] => Array
            (
                [0] => stdClass Object
                    (
                        [sum] => 1 250,00
                        [transfers] => Array
                            (
                                [0] => stdClass Object
                                    (
                                        [message] => Vacation 2017
                                        [id] => 583390a87125b958038caFAKE2377696a14f5833
                                        [to] => stdClass Object
                                            (
                                                [name] => Vacation
                                                [accountNumber] => 555 789 012-3
                                                [clearingNumber] => 5555-9
                                                [fullyFormattedNumber] => 5555-9,555 789 012-3
                                                [availableForTags] => Array
                                                    (
                                                    )
    
                                            )
    
                                        [from] => stdClass Object
                                            (
                                                [name] => Salary
                                                [accountNumber] => 555 123 456-7
                                                [clearingNumber] => 5555-9
                                                [fullyFormattedNumber] => 5555-9,555 123 456-7
                                                [availableForTags] => Array
                                                    (
                                                    )
    
                                            )
    
                                        [amount] => 750,00
                                        [periodicity] => MONTHLY
                                        [note] => Vacation 2017
                                        [transferDate] => 2016-07-25
                                        [dateDependency] => DATE_DEPENDENT
                                        [links] => stdClass Object
                                            (
                                                [self] => stdClass Object
                                                    (
                                                        [method] => GET
                                                        [uri] => /v4/transfer/583390a87125b958038caFAKE2377696a14f5833
                                                    )
    
                                                [edit] => stdClass Object
                                                    (
                                                        [method] => PUT
                                                        [uri] => /v4/transfer/583390a87125b958038caFAKE2377696a14f5833
                                                    )
    
                                            )
    
                                    )
    
                            )
    
                        [groupingAccount] => stdClass Object
                            (
                                [balance] => 7 615,90
                                [name] => Salary
                                [accountNumber] => 555 123 456-7
                                [clearingNumber] => 5555-9
                                [fullyFormattedNumber] => 5555-9,555 123 456-7
                                [availableForTags] => Array
                                    (
                                    )
    
                            )
    
                    )
            )
    
    )

## Quick Balance Accounts
| Parameter | Description |
| --- | --- |
| fullyFormattedNumber | Persistent unique identifier for the account. |
| id | Per session temporary unique strings. Only used for referring an API action. |
| links | Used for internals of the API. New URIs for each session. |

    stdClass Object
    (
        [accounts] = Array
            (
                [0] = stdClass Object
                    (
                        [name] = Vacation
                        [currency] = SEK
                        [balance] = 79 555,52
                        [accountNumber] = 555 789 012-3
                        [clearingNumber] = 5555-9
                        [fullyFormattedNumber] = 5555-9,555 789 012-3
                        [quickbalanceSubscription] = stdClass Object
                            (
                                [id] = 237b7c031adf8cfFAKE195fb0cc2468d2d4f9031
                                [active] = 
                                [links] = stdClass Object
                                    (
                                        [next] = stdClass Object
                                            (
                                                [method] = POST
                                                [uri] = /v4/quickbalance/subscription/237b7c031adf8cfFAKE195fb0cc2468d2d4f9031
                                            )
    
                                    )
    
                            )
    
                    )
    
                [2] = stdClass Object
                    (
                        [name] = Salery
                        [currency] = SEK
                        [balance] = 7 615,90
                        [accountNumber] = 555 123 456-7
                        [clearingNumber] = 5555-9
                        [fullyFormattedNumber] = 5555-9,555 123 456-7
                        [quickbalanceSubscription] = stdClass Object
                            (
                                [id] = a8f9a9187ddbcbeFAKE965a4d33cdfbd8a2b7204
                                [active] = 
                                [links] = stdClass Object
                                    (
                                        [next] = stdClass Object
                                            (
                                                [method] = POST
                                                [uri] = /v4/quickbalance/subscription/a8f9a9187ddbcbeFAKE965a4d33cdfbd8a2b7204
                                            )
    
                                    )
    
                            )
    
                    )
    
            )
    
    )

## Quick Balance Subscription
| Parameter | Description |
| --- | --- |
| subscriptionId | See [Quick balance](/INSTALL.md#quick-balance). Save this ID. |

    stdClass Object
    (
        [subscriptionId] => lDVcX_iRc71oPAMDdSR_XU70LrslhdeT6Eltp1EL-10=
        [unsubscribedSubscriptions] => Array
            (
            )
    
    )

## Quick Balance
    stdClass Object
    (
        [currency] => SEK
        [balance] => 7 615,90
        [balanceForCustomer] => 1
        [remindersExists] => 
        [numberOfReminders] => 0
    )

## Quick Balance Unsubscription
See [Quick Balance Subscription](#quick-balance-subscription)