# Execution Nodes

## Overview

Execution nodes for this current proof of concept.

The node also has a copy of the files in the smartcontract folder. It is used as a template for deploying a new dapp.

## Main functions

```
new_app($v)
```
Called the first time a new smart contract is deployed by an identity node.

```
new_app2($v)
```
Similar function to new_app, but instead of requesting the creation of a new smart contract on the oracle it will just sign an existing smart contract for the first time.
This function is important as you cant just sign the hash, because using this function you are sending a copy of the initial version of the smart contract

```
get_app($v)
```
Used by a user that wants to retrieve the info of an existing Dapp.

```
execute_app($v)
```
This is the core function for executing a smart contract. Lots of explanations inside the code.

```
get($v1)
```
DAPP function (get current dapp stored data)
	
```
set($z)
```  
DAPP function (set current dapp stored data)

```
profile($v1)
```  
DAPP function (get current dapp profiles)

```
change_profile($v1,$v2)
```  
DAPP function (update current dapp profiles)
