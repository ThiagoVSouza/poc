# Oracle node

## Basic Structure

I am not using a database to store information. I am creating files to simulate a database.

**The api.php** - is the file that holds and execute all the functions.

**blocks.txt** - json file that has a sequence of all blocks generated. 



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

```
get_data($arquivo)
```

Function that simulates a select to a database.
All data is stored on json format.

```
set_data($arquivo,$var)
```

Function used to insert or update data on a txt file.




