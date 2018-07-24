# Oracle node

## Basic Structure

- the oracle node is only accessed by execution nodes.
- it simulates a full blockchain. all data is stored in a blockchain format (content+hash / this hash includes the previous hash int it to create a chain).
- I am not using a database to store information. I am creating files to simulate a database.


```
The api.php
``` 
Is the file that holds and execute all the functions.

```
blocks.txt
```
Json file that has a sequence of all blocks generated. 

```
blocks_ids.txt
```
Json file that has the ids of every dapp generated (shortcut as an index)

```
config_946684.txt.txt
```
Json file that holds basic configuration of a dapp. Shortcut to handle basic information for a dapp (so I dont need to manually read all the blocks to get the information)

```
blocks_946684.txt
```
Json file that holds all blocks from one specific dapp. Its a shortcut just in case I want to retrieve all blocks from an specific dapp.


## Important Functions

```
new_dapp($v)
```

Function used to create a new dapp (when you deploy a new smart contract).

```
new_block($v,$dapp_id)
```

function used to create a new block on the blockchain.

```
sign($v)
```

function used by execution nodes to sign a smart contract hash.
If it is a new hash it verifies if the last one has been signed by all nodes.
If the node is the last one on the chain list it frees the dapp for a new hash.
This prevents a breach of security (theres proabbly better ways to do it)

```
get_data($arquivo)
```

Function that simulates a select to a database.
All data is stored on json format.

```
set_data($arquivo,$var)
```

Function used to insert or update data on a txt file.



