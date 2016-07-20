# Response samples

* [Profile List](#profile-list)
* [Reminders](#reminders)
* [Baseinfo](#baseinfo)
* [Account List](#account-list)
* [Portfolio List](#portfolio-list)
* [Account Details](#account-details)
* [Register Transfer](#register-transfer)
* [List Registered Transfers](#list-registered-transfers)

##Profile List
    stdClass Object
    (
        [name] => Swedbank AB (publ)
        [bankId] => 99999
        [privateProfile] => stdClass Object
            (
                [activeProfileLanguage] => en
                [id] => cc2087a7d835d6e47aFAKEcc8f3aac3eb0ad8953
                [bankId] => 08999
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
    
##Reminders
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

##Baseinfo
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

##Account List

##Portfolio List

##Account Details

##Register Transfer

##List Registered Transfers