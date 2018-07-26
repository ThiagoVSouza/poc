# Execution Nodes

## Overview

Execution nodes for this current proof of concept

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
new_app2($v)
```
