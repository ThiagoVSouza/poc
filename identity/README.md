# Identity node

Identity nodes are responsible for creating and validating ids of users. 
Theres no authentication system in place for this POC.
Login and password are stored only on the UI node. The UI node sends requests and identifies who is requesting what.

## Api Main Functions

```
new_id($v)
```
generates a new random hash for it to validate an id.

```
update_id($v)
```
used to update the chain of an id. In case you addeed another node to verify your id you call this function to add it to your profile.

```
get_id($v)
```
get a user contact.


```
get_id($v)
```
get a user contact.

```
action($v)
```
Executes a function on a smart contract

```
deploy($v)
```
Deploys a new smart contract on execution nodes.
Dont need to identify who is deploying it. The deployment is an anonymous thing in this version.



