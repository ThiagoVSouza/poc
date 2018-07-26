# Smart Contract

This folder has all the template files for the deployment of a new one on one smart contract.
It also features a full set of mediation system.

## Dapp1.txt

Bellow are a list of all the functions publicaly available on the smart contract

```
function init($v)
```
Called when the smart contract is deployed for the first time.
```
function condition($v)
```
When one user watns to add a condition to the contract.
```
function read_conditions($v)
```
Retrieves all the conditions saved by the users.
```
function change_buyer($v)
```
When someone watns to change the buyer.
```
function confirm_change_buyer($v)
```
Necessary for the seller to confirm the change if there was a buyer set before
```
function change_seller($v)
```
When someone watns to change the seller.
```
function confirm_change_seller($v)
```
Necessary for the seller to confirm the change if there was a seller set before
```
function change_mediator($v)
```
When someone wants to change the mediator.
```
function confirm_change_mediator($v)
```
Necessary for the seller to confirm the change if there was a mediator set before
```
function end_contract($v)
```
when the buyer wants to end the contract.
```
function confirm_end_contract($v)
```
The seller must confirm the end of the smart contract. After this the status is changed to 3 and can no longer be changed
The change in status is also changed on the blockchain.
```
function invoke_mediation($v)
```
When seller or buyer wants to invoke mediation.
```
function mediation_end($v)
```
The mediator can end the mediation process. Smart Contract resumes normal course.
```
function mediation_change_buyer($v)
```
Mediator removes or changes the buyer

```
function mediation_change_seller($v)
```
Mediator changes the seller
```
function mediation_end_contract($v)
```
Mediator ends the contract. Also changes the status on the blockchain.


## Config.txt

Has a basic configuration, the only important part is the custom name given by the user that is passed on when someone else retrieves the dapp.

## Permissions.txt

basic permissions, essentially the role of users available on this smart contract.
