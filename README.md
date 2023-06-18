# cstorebundle
Pimcore bundle to import/export classification store configuration.
### Like Class Definition, Field Collection and ObjectBrick, 
it is a simple utility to import/export classification store configuration that allows bulk import/export of all classification store configuration.
Commands:
1. `bin/console pimcore:cstore:import -f filepath [--no-override]` : Imports the classification store configuration from provided filepath

With `override` flag, we can choose to replace or ignore the config import for any store, collection and group, if there is a collision/clash with existing names and imported names

`override`: Replace the existing config

`no-override`: Ignore the config

2. `bin/console pimcore:cstore:export -f filepath` : Exports the classification store configuration to the provided filepath
