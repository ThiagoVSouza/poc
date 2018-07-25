# Oracle node

## Basic Structure

- the oracle node is only accessed by execution nodes.
- I am not doing any authentication of those nodes (in order to deliver it fast I removed this part)
- it simulates a full blockchain. all data is stored in a blockchain format (new hash = md5(current_content + previous_hash)). 
- I am not using a database to store information. I am creating files with a JSON format to simulate a database.
- The code is just functions with no classes or libraries. (the code is also not pretty but the point is that it works).
- Main files in this folder:

```
api.php
``` 
Is the file that holds and execute all the functions.

```
blocks.txt
```
Sample of a Json file generated that has a sequence of all blocks on the blockchain. 

```
blocks_ids.txt
```
Sample of a Json file that has the ids of every dapp generated (shortcut as a table index)

```
config_946684.txt.txt
```
Sample of a Json file that holds basic configuration of a dapp. It's a Shortcut to handle basic information for a dapp (so I dont need to manually read all the blocks to get the information).

```
blocks_946684.txt
```
Json file that holds all blocks from one specific dapp. Its a shortcut just in case I want to retrieve all blocks from an specific dapp.
If I was going to deploy it I would redo this part and add a reference to the smartcontract on the blockchain code.

## Important Functions

```
new_dapp($v)
```

Function used to create a new dapp (when you deploy a new smart contract).

```
new_block($v,$dapp_id)
```

function used to create a new block on the blockchain.
It currently 

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




